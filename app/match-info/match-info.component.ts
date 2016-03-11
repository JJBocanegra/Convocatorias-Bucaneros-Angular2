import {Component, OnInit} from 'angular2/core';
import {Match} from './match';
import {Player} from '../player/player';
import {MatchInfoService} from './match-info.service';
import {PlayerService} from '../player/player.service';
import {HelperService} from '../helper/helper.service';

@Component({
  selector: 'match-info',
  templateUrl: 'app/match-info/match-info.html',
  providers: [MatchInfoService, PlayerService],
})
export class MatchInfo implements OnInit {
  public confirmedPlayers: Player[] = [];
  public injuredPlayers: Player[] = [];
  public matches: Match[] = [];
  public nextMatch: Match;
  public notConfirmedPlayers: Player[] = [];
  public selectedPlayer: number;

  constructor(
    private matchInfoService: MatchInfoService,
    private playerService: PlayerService,
    private helperService: HelperService) { }

  ngOnInit(): void {
    this.getNextMatch();
  }

  getNextMatch(): void {
    this.matchInfoService.getNextMatch()
      .subscribe(
        response => {
          this.nextMatch = response[0];
          this.getPlayers();
        },
        error => {}
      );
  }

  getPlayers(): void {
    this.getConfirmedPlayers();
    this.getNotConfirmedPlayers();
    this.getInjuredPlayers();
  }

  getConfirmedPlayers(): void {
    this.matchInfoService.getConfirmedPlayersByMatchId(this.nextMatch.matchId)
      .subscribe(
        players => {
          this.confirmedPlayers = this.playerService.getPlayersFullNames(players);
        },
        error => {}
      );
  }

  confirmPlayer(player: Player): void {
    this.matchInfoService.confirmPlayer(this.nextMatch.matchId, player.playerId)
    .subscribe(
      player => {
        this.getConfirmedPlayers();
        this.getNotConfirmedPlayers();
      },
      error => {}
    );
  }

  removeConfirmedPlayer(confirmedPlayer): void {
    this.matchInfoService.removeConfirmedPlayer(this.nextMatch.matchId, confirmedPlayer.playerId)
    .subscribe(
      player => {
        this.getConfirmedPlayers();
        this.getNotConfirmedPlayers();
      },
      error => {}
    );
  }

  addConfirmedPlayerToInjuredPlayers(confirmedPlayer): void {
    this.removeConfirmedPlayer(confirmedPlayer);
    this.addInjuredPlayer(confirmedPlayer);
  }

  getNotConfirmedPlayers(): void {
    this.matchInfoService.getNotConfirmedPlayersByMatchId(this.nextMatch.matchId)
      .subscribe(
        players => {
          this.notConfirmedPlayers = this.playerService.getPlayersFullNames(players);
        },
        error => {}
      );
  }

  getInjuredPlayers(): void {
    this.matchInfoService.getInjuredPlayersByMatchId(this.nextMatch.matchId)
      .subscribe(
        players => {
          this.injuredPlayers = this.playerService.getPlayersFullNames(players);
        },
        error => {}
      );
  }

  addInjuredPlayer(player: Player): void {
    this.matchInfoService.addInjuredPlayer(this.nextMatch.matchId, player.playerId)
    .subscribe(
      player => {
        this.getInjuredPlayers();
        this.getNotConfirmedPlayers();
      },
      error => {}
    );
  }

  confirmInjuredPlayer(injuredPlayer): void {
    this.removeInjuredPlayer(injuredPlayer);
    this.confirmPlayer(injuredPlayer);
  }

  removeInjuredPlayer(injuredPlayer): void {
    this.matchInfoService.removeInjuredPlayer(this.nextMatch.matchId, injuredPlayer.playerId)
    .subscribe(
      player => {
        this.getInjuredPlayers();
        this.getNotConfirmedPlayers();
      },
      error => {}
    );
  }
}
