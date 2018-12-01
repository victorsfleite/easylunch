import moment from 'moment';

export default function(date, locale = 'pt-br') {
    if (!date) return '';
    moment.locale(locale);
    return moment(date).fromNow();
}
