import {
beforeEach,
describe,
expect,
it,
} from 'angular2/testing';

import {Observable} from 'rxjs/Observable';

import {CallComponent} from './call.component';
import {CallService} from './call.service';
import {PlayerService} from '../player/player.service';
import {Router} from 'angular2/router';
import 'rxjs/Rx';

import {MockMatchInfoService} from './call.service.mock';
import {MockPlayerService} from '../player/player.service.mock';

describe('MatchInfoComponent', () => {
  let matchInfo: CallComponent;
  let mockMatchInfoService: MockMatchInfoService;
  let mockPlayerService: MockPlayerService;

  beforeEach(() => {
    mockMatchInfoService = new MockMatchInfoService();
    mockPlayerService = new MockPlayerService();
    matchInfo = new CallComponent(mockMatchInfoService, mockPlayerService, null);
  });

  it('should get next match', () => {
    matchInfo.getNextMatch();
    expect(matchInfo.call).toEqual(mockMatchInfoService.match);
    expect(matchInfo.confirmedPlayers).toEqual(mockMatchInfoService.confirmedPlayers);
  });
});
