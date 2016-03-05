import {Component, OnInit} from 'angular2/core';
import {Match} from './match';
import {MatchInfoService} from './match-info.service';

@Component({
  selector: 'match-info',
  templateUrl: 'app/match-info/match-info.html',
  providers: [MatchInfoService],
})
export class MatchInfo implements OnInit {
  public confirmedPlayers: Object[] = [];
  public injuredPlayers: Object[] = [];
  public matches: Match[] = [];
  public nextMatch: Match;
  public notConfirmedPlayers: Object[] = [];

  constructor(private matchInfoService: MatchInfoService) { }

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
        error => console.log(error)
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
          this.confirmedPlayers = response;
        },
        error => console.log(error)
      );
  }

  getNotConfirmedPlayers(): void {
    this.matchInfoService.getNotConfirmedPlayersByMatchId(this.nextMatch.matchId)
      .subscribe(
        response => {
          this.notConfirmedPlayers = response;
        },
        error => console.log(error)
      );
  }

  getInjuredPlayers(): void {
    this.matchInfoService.getInjuredPlayersByMatchId(this.nextMatch.matchId)
      .subscribe(
        response => {
          this.injuredPlayers = response;
        },
        error => console.log(error)
      );
  }
}
