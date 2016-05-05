import {Injectable} from '@angular/core';
import {Http, Response} from '@angular/http';
import {Observable} from 'rxjs/Observable';

import {CONFIG} from '../shared/index';
import {DateTimeService} from '../date-time/index';
import {HelperService} from '../helper/index';

@Injectable()
export class MatchService {

  constructor(
    private http: Http,
    private dateTimeService: DateTimeService,
    private helperService: HelperService) { }

  getMatchById(matchId: number): any {
      let url = CONFIG.apiUrl + '/matches/' + matchId;

      return this.http.get(url)
          .map(res => res.json()[0])
          .do(match => match.dateTime = this.dateTimeService.getCompleteDateTime(match.dateTime))
          .catch(this.helperService.handleError);
    }

  getNextMatch(): any {
    let url = CONFIG.apiUrl + '/matches/next';

    return this.http.get(url)
        .map(res => res.json()[0])
        .do(match => match.dateTime = this.dateTimeService.getCompleteDateTime(match.dateTime))
        .catch(this.helperService.handleError);
  }
}
