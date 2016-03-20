import {Component, OnInit} from 'angular2/core';
import {Player} from './player';
import {PlayerService} from './player.service';

@Component({
  selector: 'new-player',
  templateUrl: 'app/player/new-player.html',
  providers: [PlayerService],
})
export class NewPlayer implements OnInit {
  public player: Player = <Player> {};

  constructor(
    private playerService: PlayerService) { }

  ngOnInit(): void {
  }

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

          this.player = <Player> {};
          console.log("Jugador insertado con éxito");
        },
        error => { }
      );
  }
}
