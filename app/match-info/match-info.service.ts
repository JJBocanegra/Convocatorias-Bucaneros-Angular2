import {MATCHES} from './match-info.mock';
import {Match} from './match';
import {Injectable} from 'angular2/core';
import {Http} from 'angular2/http';
import 'rxjs/add/operator/map';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class MatchInfoService {
  private url = 'api/api.php';
  public lastMatch;

  constructor(private http: Http){}

  getMatches() {
    return Promise.resolve(MATCHES);
  }

  getLastMatch() {
    return Observable.create(observer => {
      this.http.get('api/api.php/matches/last')
        .map(res => res.json())
        .subscribe(
          data => {
            observer.next(data);
            observer.complete();
          },
          err => observer.error(err)
        );
    });
  }
}
