import {bootstrap} from '@angular/platform-browser-dynamic';
import {AppComponent} from './app.component';
import {HTTP_PROVIDERS} from '@angular/http';
import {ROUTER_PROVIDERS} from '@angular/router-deprecated';

import {DateTimeService} from './date-time/date-time.service';
import {HelperService} from './helper/helper.service';

import 'rxjs/Rx';

bootstrap(AppComponent, [
  HTTP_PROVIDERS,
  ROUTER_PROVIDERS,
  DateTimeService,
  HelperService,
]);
