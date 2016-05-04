import {Component, OnInit} from 'angular2/core';
import {Router} from 'angular2/router';
import {RouteParams} from 'angular2/router';
import {Match} from '../match/match';
import {Player} from '../player/player';
import {CallService} from './call.service';
import {MatchService} from '../match/match.service';
import {PlayerService} from '../player/player.service';

@Component({
  selector: 'call',
  templateUrl: 'app/call/call.html',
  providers: [CallService, MatchService, PlayerService],
})
export class CallComponent implements OnInit {
  public confirmedPlayers: Player[] = [];
  public injuredPlayers: Player[] = [];
  public call: Match;
  public notConfirmedPlayers: Player[] = [];
  public selectedPlayer: number;

  constructor(
    private callService: CallService,
    private matchService: MatchService,
    private playerService: PlayerService,
    private router: Router,
    private routeParams: RouteParams) { }

  ngOnInit(): void {
    let callId = this.routeParams.get('id');

    if (callId === null) {
      this.getNextMatch();
    } else {
      this.getMatchById(parseInt(callId));
    }
  }

  getMatchById(matchId: number): void {
    this.matchService.getMatchById(matchId)
      .subscribe(
        call => {
          this.call = call;
          this.getPlayers();
        }
      );
  }

  getNextMatch(): void {
    this.matchService.getNextMatch()
      .subscribe(
        call => {
          this.call = call;
          this.getPlayers();
        }
      );
  }

  getPlayers(): void {
    this.getConfirmedPlayers();
    this.getNotConfirmedPlayers();
    this.getInjuredPlayers();
  }

  getConfirmedPlayers(): void {
    this.callService.getConfirmedPlayersByMatchId(this.call.matchId)
      .subscribe(
        players => {
          this.confirmedPlayers = this.playerService.getPlayersFullNames(players);
        }
      );
  }

  confirmPlayer(player: Player): void {
    this.confirmPlayerById(player.playerId);
  }

  confirmPlayerById(playerId: number): void {
    this.callService.confirmPlayer(this.call.matchId, playerId)
    .subscribe(
      player => {
        this.getConfirmedPlayers();
        this.getNotConfirmedPlayers();
      }
    );
  }

  removeConfirmedPlayer(confirmedPlayer: Player): void {
    this.callService.removeConfirmedPlayer(this.call.matchId, confirmedPlayer.playerId)
    .subscribe(
      player => {
        this.getConfirmedPlayers();
        this.getNotConfirmedPlayers();
      }
    );
  }

  addConfirmedPlayerToInjuredPlayers(confirmedPlayer: Player): void {
    this.removeConfirmedPlayer(confirmedPlayer);
    this.addInjuredPlayer(confirmedPlayer);
  }

  getNotConfirmedPlayers(): void {
    this.callService.getNotConfirmedPlayersByMatchId(this.call.matchId)
      .subscribe(
        players => {
          this.notConfirmedPlayers = this.playerService.getPlayersFullNames(players);
        }
      );
  }

  getInjuredPlayers(): void {
    this.callService.getInjuredPlayersByMatchId(this.call.matchId)
      .subscribe(
        players => {
          this.injuredPlayers = this.playerService.getPlayersFullNames(players);
        }
      );
  }

  addInjuredPlayer(player: Player): void {
    this.addInjuredPlayerById(player.playerId);
  }

  addInjuredPlayerById(playerId: number): void {
    this.callService.addInjuredPlayer(this.call.matchId, playerId)
    .subscribe(
      player => {
        this.getInjuredPlayers();
        this.getNotConfirmedPlayers();
      }
    );
  }

  confirmInjuredPlayer(injuredPlayer: Player): void {
    this.removeInjuredPlayer(injuredPlayer);
    this.confirmPlayer(injuredPlayer);
  }

  removeInjuredPlayer(injuredPlayer: Player): void {
    this.callService.removeInjuredPlayer(this.call.matchId, injuredPlayer.playerId)
    .subscribe(
      player => {
        this.getInjuredPlayers();
        this.getNotConfirmedPlayers();
      }
    );
  }

  goToPlayerInfo(player: Player): void {
    this.router.navigate(['PlayerInfo', {id: player.playerId}]);
  }
}
