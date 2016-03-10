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
        response => {
          var players = this.playerService.getPlayersFullNames(response);
          this.confirmedPlayers = players;
        },
        error => {}
      );
  }

  getNotConfirmedPlayers(): void {
    this.matchInfoService.getNotConfirmedPlayersByMatchId(this.nextMatch.matchId)
      .subscribe(
        response => {
          var players = this.playerService.getPlayersFullNames(response);
          this.notConfirmedPlayers = players;
        },
        error => {}
      );
  }

  getInjuredPlayers(): void {
    this.matchInfoService.getInjuredPlayersByMatchId(this.nextMatch.matchId)
      .subscribe(
        response => {
          var players = this.playerService.getPlayersFullNames(response);
          this.injuredPlayers = players;
        },
        error => {}
      );
  }

  confirmPlayer(): void {
    this.matchInfoService.confirmPlayer(this.nextMatch.matchId, this.selectedPlayer)
    .subscribe(
      player => {
        this.getConfirmedPlayers();
        this.getNotConfirmedPlayers();
      },
      error => {}
    );
  }

  addInjuredPlayer(): void {
    this.matchInfoService.addInjuredPlayer(this.nextMatch.matchId, this.selectedPlayer)
    .subscribe(
      player => {
        this.getInjuredPlayers();
        this.getNotConfirmedPlayers();
      },
      error => {}
    );
  }
}
