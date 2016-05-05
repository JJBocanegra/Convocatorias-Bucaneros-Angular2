import {bootstrap} from '@angular/platform-browser-dynamic';
import {AppComponent} from './app.component';
import {HTTP_PROVIDERS} from '@angular/http';
import {ROUTER_PROVIDERS} from '@angular/router-deprecated';
import 'rxjs/Rx';

import {DateTimeService} from './date-time/index';
import {HelperService} from './helper/index';

bootstrap(AppComponent, [
  HTTP_PROVIDERS,
  ROUTER_PROVIDERS,
  DateTimeService,
  HelperService,
]);
