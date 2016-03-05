import {Injectable} from 'angular2/core';
import {Http, Response} from 'angular2/http';
import {Observable} from 'rxjs/Observable';

import {Match} from './match';
import {DateTimeService} from '../date-time/date-time.service';
import {HelperService} from '../helper/helper.service';

@Injectable()
export class MatchInfoService {
  private url = 'http://localhost/Convocatorias-Bucaneros-Angular2/api/api.php';

  constructor(private http: Http, private dateTimeService: DateTimeService, private helperService: HelperService) { }

  getNextMatch(): any {
    var url = this.url + '/matches/last' //TODO La llamada a la api debería ser `next` en vez de `last`

    return this.http.get(url)
        .map(res => res.json())
        .do(data => data[0].dateTime = this.dateTimeService.getCompleteDateTime(data[0].dateTime))
        .catch(this.helperService.handleError);
  }

  getConfirmedPlayersByMatchId(matchId: number): any {
    var url = this.url + '/matches/' + matchId + '/players/confirmed';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  getNotConfirmedPlayersByMatchId(matchId: number): any {
    var url = this.url + '/matches/' + matchId + '/players/notConfirmed';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  getInjuredPlayersByMatchId(matchId: number): any {
    var url = this.url + '/matches/' + matchId + '/players/injured';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }
}
