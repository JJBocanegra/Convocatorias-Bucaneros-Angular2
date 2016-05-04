import {Injectable} from 'angular2/core';
import {Http, Response} from 'angular2/http';
import {Observable} from 'rxjs/Observable';
import {CONFIG} from '../CONFIG';
import {DateTimeService} from '../date-time/date-time.service';
import {HelperService} from '../helper/helper.service';

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
