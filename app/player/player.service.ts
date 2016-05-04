import {Injectable} from '@angular/core';
import {Http, Response, Headers, RequestOptions} from '@angular/http';
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
    let url = CONFIG.apiUrl + '/players/' + playerId;

    return this.http.get(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  getPlayerByIdFromPlayerList(playerId: number, players: Player[]): Player {
    for (let i = players.length - 1; i >= 0; i--) {
      if (players[i].playerId === playerId) {
        return players[i];
      }
    }

    return null;
  }

  getPlayersFullNames(players: Player[]): Player[] {
    let player;

    if (players.length === 0) {
      return players;
    }

    for (let i = players.length - 1; i >= 0; i--) {
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

  updatePlayer(player: Player): any {
    let url = CONFIG.apiUrl + '/players/' + player.playerId;

    let body = JSON.stringify(player);
    let headers = new Headers({'Content-Type': 'application/json'});
    let options = new RequestOptions({headers: headers});

    return this.http.put(url, body, options)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  createPlayer(player: Player): any {
    let url = CONFIG.apiUrl + '/players';

    let body = JSON.stringify(player);
    let headers = new Headers({'Content-Type': 'application/json'});
    let options = new RequestOptions({headers: headers});

    return this.http.post(url, body, options)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  addPlayerToCurrentSeason(player: Player): any {
    let url = CONFIG.apiUrl + '/season/current/players/add/' + player.playerId;

    return this.http.post(url, null)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  private sortPlayers(players: Player[]): Player[] {
    let sortedPlayers = players.sort(function (a: any, b: any): number {
      let comparison: number;

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
    });

    return sortedPlayers;
  }
}
