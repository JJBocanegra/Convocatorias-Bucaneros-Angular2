import {Component} from 'angular2/core';
import {RouteConfig, ROUTER_DIRECTIVES} from 'angular2/router';
import {MatchInfo} from './match-info/match-info.component';
import {PlayerInfo} from './player/player.component';
import {NewPlayer} from './player/new-player.component';

@Component({
  selector: 'my-app',
  templateUrl: 'app/app.html',
  directives: [MatchInfo, NewPlayer, ROUTER_DIRECTIVES],
})
@RouteConfig([
  {path:'/convocatoria', name: 'MatchInfo', component: MatchInfo},
  {path:'/jugador/:id', name: 'PlayerInfo', component: PlayerInfo},
  {path:'/nuevo-jugador', name: 'NewPlayer', component: NewPlayer},
])
export class AppComponent {}
