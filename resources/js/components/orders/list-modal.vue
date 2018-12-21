<template>
    <modal ref="modal" size="lg" body-classes="bg-light rounded-bottom" :has-footer="false">
        <span slot="title">Pedidos entre {{ dateRange.start | date('DD/MM/YYYY') }} e {{ dateRange.end | date('DD/MM/YYYY') }}</span>
        <order-card v-for="order of orders" :key="order.id" :order="order" :show-timestamps="true"></order-card>
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
};
</script>
