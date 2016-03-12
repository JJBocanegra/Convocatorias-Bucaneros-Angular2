import {Component, OnInit} from 'angular2/core';
import {RouteParams} from 'angular2/router';
import {Player} from './player';
import {PlayerService} from './player.service';

@Component({
  selector: 'player',
  templateUrl: 'app/player/player.html',
  providers: [PlayerService],
})
export class PlayerInfo implements OnInit {
  public player: Player;

  constructor(
    private playerService: PlayerService,
    private routeParams: RouteParams) { }

  ngOnInit(): void {
    let playerId = this.routeParams.get('id');
    this.getPlayerById(playerId);
  }

  getPlayerById(playerId: string) {
    this.playerService.getPlayerById(playerId)
      .subscribe(
        player => {
          this.player = player;
        },
        error => { }
      );
  }
}
