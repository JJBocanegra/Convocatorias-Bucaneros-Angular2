import {Injectable} from 'angular2/core';

declare let moment: any;

@Injectable()
export class DateTimeService {
  constructor() {
    moment.updateLocale('es', {
      months: [
        'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
        'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre',
      ],
      weekdays: [
        'lunes', 'martes', 'miércoles', 'jueves',
        'viernes', 'sábado', 'domingo',
      ],
    });
  }

  getCompleteDateTime(dateTime: string): string {
    try {
      if (dateTime === null || dateTime === undefined || dateTime === '') {
        return null;
      }

      if (dateTime.indexOf(':') === -1) {
        return moment(dateTime, 'YYYY-MM-DD').format('dddd DD [de] MMMM [de] YYYY');
      }

      return moment(dateTime, 'YYYY-MM-DD HH:mm').format('dddd DD [de] MMMM [de] YYYY [a las] HH:mm[h]');
    } catch (error) {
      console.error(error);
    }
  }

  getBirthDate(date: string): string {
    try {
      if (date === null || date === undefined || date === '') {
        return null;
      }

      return moment(date, 'YYYY-MM-DD').format('DD [de] MMMM [de] YYYY');
    } catch (error) {
      console.error(error);
    }
  }

  calculateAge(birthDate: string): string {
    if (birthDate === null || birthDate === undefined || birthDate === '') {
      return null;
    }

    let birth = moment(birthDate, 'YYYY-MM-DD');
    let today = moment();

    return today.diff(birth, 'years');
  }
}
