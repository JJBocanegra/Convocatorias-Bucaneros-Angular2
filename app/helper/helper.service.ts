import {Injectable} from 'angular2/core';
import {Response} from 'angular2/http';
import {Observable} from 'rxjs/Observable';

@Injectable()
export class HelperService {
  constructor() { }

  handleError(error: Response) {
    // in a real world app, we may send the error to some remote logging infrastructure
    // instead of just logging it to the console
    console.error(error.text());
    return Observable.throw(error.json().error || error.text());
  }

  indexOfObject(array: any[], key: any, value: any): any {
    for (var i = array.length - 1; i >= 0; i--) {
      if (array[i][key] == value) {
        return i;
      }
    }
  }
}
