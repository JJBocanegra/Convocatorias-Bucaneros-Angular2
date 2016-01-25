import {Component} from 'angular2/core';
import {OnInit} from 'angular2/core';
import {MatchInfo} from './match-info/match-info.component';

@Component({
  selector: 'my-app',
  templateUrl: 'app/app.html',
  directives: [MatchInfo],
})
export class AppComponent implements OnInit {
  ngOnInit() {};
}
