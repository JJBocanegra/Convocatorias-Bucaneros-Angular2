import {Component, OnInit} from 'angular2/core';
import {Match} from './match';
import {MatchInfoService} from './match-info.service';

@Component({
  selector: 'match-info',
  templateUrl: 'app/match-info/match-info.html',
  providers: [MatchInfoService],
})
export class MatchInfo implements OnInit {
  public matches: Match[];
  public lastMatch: Object = {};

  constructor(private _matchInfoService: MatchInfoService) {
  }

  ngOnInit() {
    this.getMatches();
    this.getLastMatch();
  }

  getMatches() {
    this._matchInfoService.getMatches().then(matches => this.matches = matches);
  }

  getLastMatch() {
    this._matchInfoService.getLastMatch()
      .subscribe(
        response => {
          console.log(response[0]);
          this.lastMatch = response[0];
        },
        error => console.log(error)
      );
  }
}
