import {Injectable} from '@angular/core';
import {Http, Response} from '@angular/http';
import {Observable} from 'rxjs/Observable';

import {CONFIG} from '../shared/index';
import {HelperService} from '../helper/index';

@Injectable()
export class CallService {

  constructor(
    private http: Http,
    private helperService: HelperService) { }

  getConfirmedPlayersByMatchId(matchId: number): any {
    let url = CONFIG.apiUrl + '/matches/' + matchId + '/players/confirmed';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  getNotConfirmedPlayersByMatchId(matchId: number): any {
    let url = CONFIG.apiUrl + '/matches/' + matchId + '/players/notConfirmed';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  getInjuredPlayersByMatchId(matchId: number): any {
    let url = CONFIG.apiUrl + '/matches/' + matchId + '/players/injured';

    return this.http.get(url)
        .map(res => res.json())
        .catch(this.helperService.handleError);
  }

  confirmPlayer(matchId: number, playerId: number): any {
    let url = CONFIG.apiUrl + '/matches/' + matchId + '/players/confirmed/add/' + playerId;

    return this.http.post(url, null)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  addInjuredPlayer(matchId: number, playerId: number): any {
    let url = CONFIG.apiUrl + '/matches/' + matchId + '/players/injured/add/' + playerId;

    return this.http.post(url, null)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  removeInjuredPlayer(matchId: number, playerId: number): any {
    let url = CONFIG.apiUrl + '/matches/' + matchId + '/players/injured/remove/' + playerId;

    return this.http.delete(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }

  removeConfirmedPlayer(matchId: number, playerId: number): any {
    let url = CONFIG.apiUrl + '/matches/' + matchId + '/players/confirmed/remove/' + playerId;

    return this.http.delete(url)
        .map(res => res.json()[0])
        .catch(this.helperService.handleError);
  }
}
