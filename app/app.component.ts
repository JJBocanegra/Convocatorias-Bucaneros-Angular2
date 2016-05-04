import {Component} from 'angular2/core';
import {RouteConfig, ROUTER_DIRECTIVES} from 'angular2/router';
import {CallComponent} from './call/call.component';
import {MatchesComponent} from './matches/matches.component';
import {PlayerInfo} from './player/player.component';
import {NewPlayer} from './player/new-player.component';

@Component({
  selector: 'my-app',
  templateUrl: 'app/app.html',
  directives: [CallComponent, NewPlayer, ROUTER_DIRECTIVES],
})
@RouteConfig([
  {path:'/proxima-convocatoria', name: 'NextCall', component: CallComponent, useAsDefault: true},
  {path:'/convocatoria', name: 'Calls', component: MatchesComponent},
  {path:'/convocatoria/:id', name: 'Call', component: CallComponent},
  {path:'/jugador/:id', name: 'PlayerInfo', component: PlayerInfo},
  {path:'/nuevo-jugador', name: 'NewPlayer', component: NewPlayer},
])
export class AppComponent {}
