import {Component} from '@angular/core';

import {Player} from './player';
import {PlayerService} from './player.service';

@Component({
  selector: 'new-player',
  templateUrl: 'app/player/new-player.component.html',
  providers: [PlayerService],
})
export class NewPlayer {
  public player: Player = {} as Player;

  constructor(
    private playerService: PlayerService) { }

  createPlayer(): any {
    this.playerService.createPlayer(this.player)
      .subscribe(
        createdPlayer => {
          this.playerService.addPlayerToCurrentSeason(createdPlayer)
          .subscribe(
            playerSeason => {
              console.log(playerSeason);
            }
          );

          this.player = {} as Player;
          console.log('Jugador insertado con éxito');
        }
      );
  }
}
