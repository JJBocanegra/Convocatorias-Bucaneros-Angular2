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
  private playerCopy: Player;
  private editingPlayer: boolean = false;

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

  showNickname() {
    if (!this.player.nickname) {
      return 'No tiene';

    }

    return this.player.nickname;
  }

  showBirthDate() {
    let formattedBirthDate;
    let age;

    if (!this.player.birthDate) {
      return 'Desconocida';
    }

    formattedBirthDate = this.showFormatedDate(this.player.birthDate);
    age = this.calculateAge(this.player.birthDate)

    return formattedBirthDate + ' (' + age + ' años)';
  }

  calculateAge(birthDate: string): string {
    return this.dateTimeService.calculateAge(birthDate);
  }

  editPlayer(): void {
    this.editingPlayer = true;
    this.playerCopy = JSON.parse(JSON.stringify(this.player));
  }

  saveEditedPlayer(): void {
    this.editingPlayer = false;

    this.playerService.updatePlayer(this.player)
      .subscribe(
        player => {
          this.player = player;
        },
        error => { }
      );
  }

  cancelEditedPlayer(): void {
    this.editingPlayer = false;
    this.player = this.playerCopy;
  }
}
