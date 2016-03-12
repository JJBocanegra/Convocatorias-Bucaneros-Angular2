import {Injectable} from 'angular2/core';
import {Http, Response} from 'angular2/http';
import {Observable} from 'rxjs/Observable';
import {CONFIG} from '../CONFIG';
import {DateTimeService} from '../date-time/date-time.service';
import {HelperService} from '../helper/helper.service';

@Injectable()
export class MatchInfoService {

  constructor(
    private http: Http, 
    private dateTimeService: DateTimeService,
    private helperService: HelperService) { }

  getNextMatch(): any {
    var url = CONFIG.apiUrl + '/matches/next' //TODO La llamada a la api debería ser `next` en vez de `last`

    return this.http.get(url)
        .map(res => res.json()[0])
        .do(match => match.dateTime = this.dateTimeService.getCompleteDateTime(match.dateTime))
        .catch(this.helperService.handleError);
  }

  getConfirmedPlayersByMatchId(matchId: number): any {
    var url = CONFIG.apiUrl + '/matches/' + matchId + '/players/confirmed';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  getNotConfirmedPlayersByMatchId(matchId: number): any {
    var url = CONFIG.apiUrl + '/matches/' + matchId + '/players/notConfirmed';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  getInjuredPlayersByMatchId(matchId: number): any {
    var url = CONFIG.apiUrl + '/matches/' + matchId + '/players/injured';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  confirmPlayer(matchId: number, playerId: number): any {
    var url = CONFIG.apiUrl + '/matches/' + matchId + '/players/confirmed/add/' + playerId;

    return this.http.get(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  addInjuredPlayer(matchId: number, playerId: number): any {
    var url = CONFIG.apiUrl + '/matches/' + matchId + '/players/injured/add/' + playerId;

    return this.http.get(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  removeInjuredPlayer(matchId: number, playerId: number): any {
    var url = CONFIG.apiUrl + '/matches/' + matchId + '/players/injured/remove/' + playerId;

    return this.http.get(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  removeConfirmedPlayer(matchId: number, playerId: number): any {
    var url = CONFIG.apiUrl + '/matches/' + matchId + '/players/confirmed/remove/' + playerId;

    return this.http.get(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }
}
