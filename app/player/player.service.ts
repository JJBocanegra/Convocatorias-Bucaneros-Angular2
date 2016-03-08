import {Injectable} from 'angular2/core';
import {Player} from './player';

@Injectable()
export class PlayerService {
  private url = 'http://localhost/Convocatorias-Bucaneros-Angular2/api/api.php';

  constructor() { }

  getFullNameWithoutLastSurnameOfPlayers(players: Player[]) :Player[] {
    var player;

    for (var i = 0, length = players.length; i < length; i++) {
      player = players[i];
      player.fullName = player.name + ' ' + player.firstSurname;
    }

    return players;
  }
}
