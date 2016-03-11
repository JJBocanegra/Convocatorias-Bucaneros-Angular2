import {Injectable} from 'angular2/core';

declare var moment: any;

@Injectable()
export class DateTimeService {
  constructor() {
    moment.locale('es',  {
      months: [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
      ],
      weekdays: [
        'lunes', 'martes', 'miércoles', 'jueves',
        'viernes', 'sábado', 'domingo'
      ]
    });
  }

  getCompleteDateTime(dateTime: string): string {
    if (dateTime.indexOf(':') === -1) {
      return moment(dateTime, 'YYYY-MM-DD').format('dddd DD [de] MMMM [de] YYYY');
    }

    return moment(dateTime, 'YYYY-MM-DD HH:mm').format('dddd DD [de] MMMM [de] YYYY [a las] HH:mm[h]');
  }
}
