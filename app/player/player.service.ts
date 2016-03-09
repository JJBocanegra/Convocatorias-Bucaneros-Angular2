import {Injectable} from 'angular2/core';
import {Player} from './player';

@Injectable()
export class PlayerService {
  private url = 'http://localhost/Convocatorias-Bucaneros-Angular2/api/api.php';

  constructor() { }

  getPlayerById(playerId: number, players: Player[]): Player {
    for (var i = players.length - 1; i >= 0; i--) {
      if (players[i].playerId === playerId) {
        return players[i];
      }
    }

    return null;
  }

  getPlayersFullNames(players: Player[]) :Player[] {
    var player;

    for (var i = players.length - 1; i >= 0; i--) {
      player = players[i];
      player = this.getPlayerFullName(player);
    }

    return players;
  }

  getPlayerFullName(player: Player) :Player {
    player.fullName = player.name + ' ' + player.firstSurname;

    return player;
  }
}
