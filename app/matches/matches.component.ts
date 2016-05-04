import {Component, OnInit} from '@angular/core';
import {Router} from '@angular/router-deprecated';
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
        }
      );
  }

  goToMatchInfo(match: Match): void {
    this.router.navigate(['Call', {id: match.matchId}]);
  }
}
