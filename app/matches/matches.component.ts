import {Component, OnInit} from 'angular2/core';
import {Router} from 'angular2/router';
import {Match} from '../match/match';
import {MatchesService} from '../matches/matches.service';

@Component({
  selector: 'matches',
  templateUrl: 'app/matches/matches.html',
  providers: [MatchesService],
})
export class MatchesComponent implements OnInit {
  public matches: Match[] = [];

  constructor(
    private matchesService: MatchesService,
    private router: Router) { }

  ngOnInit(): void {
    this.getMatches();
  }

  getMatches(): void {
    this.matchesService.getMatches()
      .subscribe(
        matches => {
          this.matches = matches;
        },
        error => {}
      );
  }

  goToMatchInfo(match: Match) {
    this.router.navigate(['Call', {id: match.matchId}]);
  }
}
