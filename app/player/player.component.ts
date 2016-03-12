import {Component, OnInit} from 'angular2/core';
import {RouteParams} from 'angular2/router';
import {Player} from './player';
import {PlayerService} from './player.service';
import {DateTimeService} from '../date-time/date-time.service';

@Component({
  selector: 'player',
  templateUrl: 'app/player/player.html',
  providers: [PlayerService, DateTimeService],
})
export class PlayerInfo implements OnInit {
  public player: Player;

  constructor(
    private playerService: PlayerService,
    private dateTimeService: DateTimeService,
    private routeParams: RouteParams) { }

  ngOnInit(): void {
    let playerId = this.routeParams.get('id');
    this.getPlayerById(playerId);
  }

  getPlayerById(playerId: string): any {
    this.playerService.getPlayerById(playerId)
      .subscribe(
        player => {
          this.player = player;
        },
        error => { }
      );
  }

  showFormatedDate(birthDate: string): string {
    return this.dateTimeService.getBirthDate(birthDate);
  }

  calculateAge(birthDate: string): string {
    return this.dateTimeService.calculateAge(birthDate);
  }
}
