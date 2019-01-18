<template>
    <modal ref="modal" size="lg" body-classes="bg-light rounded-bottom" :has-footer="false">
        <span slot="title">
            <slot> Pedidos entre {{ dateRange.start | date('DD/MM/YYYY') }} e {{ dateRange.end | date('DD/MM/YYYY') }} </slot>
        </span>
        <order-card v-for="order of orders" :key="order.id" :order="order" :show-timestamps="true">
            <div slot="actions" class="float-right" v-if="$user.is_admin">
                <button-loading class="btn btn-sm"
                    :class="paidStatusClassesFor(order)"
                    @click="togglePaid(order)"
                    @mouseover.native="hover(order)"
                    @mouseleave.native="unhover(order)"
                    :loading="isMarking(order)">
                    <i class="fa fa-fw" :class="paidStatusIconFor(order)" v-if="!isMarking(order)"></i>
                    {{ paidStatusLabelFor(order) }}
                </button-loading>
            </div>
        </order-card>
    </modal>
</template>

<script>
import ModalComponent from '@/mixins/modal-component';
import OrderCard from '@/components/orders/card';

export default {
    components: { OrderCard },

    mixins: [ModalComponent],

    props: {
        orders: { default: () => [], type: Array },
        dateRange: { default: () => ({ start: null, end: null }), type: Object },
    },

    data() {
        return {
            payingOrders: [],
            hoveringOrders: [],
        };
    },

    methods: {
        async togglePaid(order) {
            try {
                this.payingOrders.push(order.id);
                this.unhover(order);

                const { data: updated } = await this.$axios.put(this.$route('orders.mark-paid', { order: order.id }), {
                    paid: !order.paid_at,
                });

                this.$nextTick(() => (order.paid_at = updated.data.paid_at));
            } finally {
                this.payingOrders.splice(this.payingOrders.indexOf(order.id), 1);
            }
        },

        isMarking(order) {
            return this.payingOrders.indexOf(order.id) > -1;
        },

        isHovering(order) {
            return this.hoveringOrders.indexOf(order.id) > -1;
        },

        paidStatusLabelFor(order) {
            if (this.isMarking(order)) return order.paid_at ? 'Despagando...' : 'Pagando...';
            if (this.isHovering(order)) return order.paid_at ? 'Despagar' : 'Pagar';
            return order.paid_at ? 'Pago' : 'Não pago';
        },

        paidStatusClassesFor(order) {
            if (this.isHovering(order)) return order.paid_at ? 'btn-danger' : 'btn-success';
            if (this.isMarking(order)) return order.paid_at ? 'btn-outline-danger' : 'btn-outline-success';
            return order.paid_at ? 'btn-success' : 'btn-outline-danger';
        },

        paidStatusIconFor(order) {
            if (this.isHovering(order)) return order.paid_at ? 'fa-exclamation-circle' : 'fa-check';
            return order.paid_at ? 'fa-check' : 'fa-exclamation-circle';
        },

        hover(order) {
            this.hoveringOrders.push(order.id);
        },

        unhover(order) {
            this.hoveringOrders = this.hoveringOrders.filter(id => id !== order.id);
        },
    },
};
</script>
