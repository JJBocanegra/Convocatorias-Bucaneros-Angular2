import {Injectable} from '@angular/core';
import {Http, Response} from '@angular/http';
import {Observable} from 'rxjs/Observable';

import {CONFIG} from '../shared/index';
import {HelperService} from '../helper/index';

@Injectable()
export class MatchesService {

  constructor(
    private http: Http,
    private helperService: HelperService) { }

  getMatches(): any {
    let url = CONFIG.apiUrl + '/matches';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }
}
