import axios from 'axios';

export default class {
    constructor() {
        this.sending_invoices = false;
        this.sending_invoice_to_user = null;
    }

    async sendInvoices(range) {
        try {
            this.sending_invoices = true;
            return await axios.post(route('orders.send-invoices'), range);
        } finally {
            this.sending_invoices = false;
        }
    }

    async sendInvoiceToUser(range, user) {
        try {
            this.sending_invoice_to_user = user.id;
            return await axios.post(route('orders.send-invoice-to-user', { user: user.id }), range);
        } finally {
            this.sending_invoice_to_user = null;
        }
    }
}
