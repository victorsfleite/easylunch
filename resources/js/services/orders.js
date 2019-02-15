import axios from 'axios';
import laroute from '@/plugins/laroute';

export default class {
    constructor() {
        this.sending_invoices = false;
    }

    async sendInvoices(range) {
        try {
            this.sending_invoices = true;
            return await axios.post(laroute.route('orders.send-invoices'), range);
        } finally {
            this.sending_invoices = false;
        }
    }
}