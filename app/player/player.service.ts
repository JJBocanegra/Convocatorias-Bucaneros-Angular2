import {Injectable} from 'angular2/core';
import {Http, Response} from 'angular2/http';
import {Observable} from 'rxjs/Observable';
import {CONFIG} from '../CONFIG';
import {Player} from './player';
import {HelperService} from '../helper/helper.service';

@Injectable()
export class PlayerService {
  constructor(
    private http: Http,
    private helperService: HelperService) { }

  getPlayerById(playerId: any): any {
    var url = CONFIG.apiUrl + '/players/' + playerId;

    return this.http.get(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  getPlayerByIdFromPlayerList(playerId: number, players: Player[]): Player {
    for (var i = players.length - 1; i >= 0; i--) {
      if (players[i].playerId === playerId) {
        return players[i];
      }
    }

    return null;
  }

  getPlayersFullNames(players: Player[]): Player[] {
    var player;

    for (var i = players.length - 1; i >= 0; i--) {
      player = players[i];
      player = this.getPlayerFullName(player);
    }

    players = this.sortPlayers(players);

    return players;
  }

  getPlayerFullName(player: Player): Player {
    player.fullName = player.name + ' ' + player.firstSurname;

    return player;
  }

  private sortPlayers(players: Player[]): Player[] {
    var sortedPlayers = players.sort(function (a: any, b: any) {
      var comparison;

      comparison = a.firstSurname.localeCompare(b.firstSurname);
      if (comparison !== 0) {
        return comparison;
      }

      comparison = a.secondSurname.localeCompare(b.secondSurname);
      if (comparison !== 0) {
        return comparison;
      }

      comparison = a.name.localeCompare(b.name);
      if (comparison !== 0) {
        return comparison;
      }
    })

    return sortedPlayers;
  }
}
