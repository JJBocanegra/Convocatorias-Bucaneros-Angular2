import {Component} from '@angular/core';
// import {RouteConfig, ROUTER_DIRECTIVES} from '@angular/router-deprecated';

import {CallComponent} from './call/index';
import {MatchesComponent} from './matches/index';
import {PlayerInfo, NewPlayer} from './player/index';

@Component({
  selector: 'my-app',
  templateUrl: 'app/app.component.html',
  // directives: [CallComponent, NewPlayer, ROUTER_DIRECTIVES],
})
// @RouteConfig([
//   {path: '/proxima-convocatoria', name: 'NextCall', component: CallComponent, useAsDefault: true},
//   {path: '/convocatoria', name: 'Calls', component: MatchesComponent},
//   {path: '/convocatoria/:id', name: 'Call', component: CallComponent},
//   {path: '/jugador/:id', name: 'PlayerInfo', component: PlayerInfo},
//   {path: '/nuevo-jugador', name: 'NewPlayer', component: NewPlayer},
// ])
export class AppComponent {}
