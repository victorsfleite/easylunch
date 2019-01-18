<template lang="pug">
    div
        .row
            .form-group.col-md-6
                label.form-label Selecione Período
                v-date-picker(mode="range", v-model="reportRange", @input="onChangeRange")
                    input.form-control(
                        slot-scope="props"
                        :value="props.inputValue"
                        placeholder="Teste"
                        @change="props.updateValue($event.target.value)")
        .row.mt-3
            .col
                h3.mb-3 Relatório de Pedidos
                .card.mb-5
                    table.table.table-hover.mb-0
                        thead
                            tr
                                th.border-top-0 Dia
                                th.border-top-0 Nº de Pedidos
                                th.border-top-0 Total
                        tbody
                            tr(v-for="(report, date) of reports", :key="report.id", @click="showDayOrders(report, date)")
                                td {{ date | date }}
                                td {{ report.count_orders }}
                                td R$ {{ report.total.toFixed(2) }}
                            tr.text-uppercase.font-weight-bold.text-success
                                td.text-right TOTAL :
                                td {{ totalOrdersReport }}
                                td R$ {{ total.toFixed(2) }}
            .col-12.col-md-6
                div(v-if="!$user.is_chef")
                    h3.mb-3 Relatório por Usuários
                    .card
                        table.table.table-hover.mb-0
                            thead
                                tr
                                    th.border-top-0 Usuário
                                    th.border-top-0 Nº de Pedidos
                                    th.border-top-0 Total
                            tbody
                                tr.cursor-pointer(v-for="user of users", :key="user.id", @click="showOrders(user.orders)")
                                    td {{ user.name }}
                                    td {{ user.orders.length }}
                                    td R$ {{ user.total_amount }}
                                tr.text-uppercase.font-weight-bold.text-success
                                    td.text-right TOTAL :
                                    td {{ totalOrdersUsersReport }}
                                    td R$ {{ totalUsers }}

        orders-list-modal(ref="ordersListModal", v-if="orders", :orders="orders", :date-range="reportRange")
            span(v-if="day") Pedidos do dia {{ day | date('DD/MM/YYYY') }}
</template>

<script>
import moment from 'moment';
import OrdersListModal from '@/components/orders/list-modal';

export default {
    components: { OrdersListModal },

    data() {
        return {
            reports: null,
            users: null,
            orders: [],
            reportRange: {
                start: moment()
                    .startOf('isoWeek')
                    .toDate(),
                end: moment()
                    .endOf('isoWeek')
                    .subtract(2, 'days')
                    .toDate(),
            },
            day: null,
        };
    },

    created() {
        this.fetchReports();
        this.fetchUserReports();
    },

    computed: {
        total() {
            if (!this.reports) return 0;
            return Object.keys(this.reports)
                .map(date => this.reports[date].total)
                .reduce((a, b) => a + b, 0);
        },

        totalUsers() {
            return !this.users ? 0 : this.users.reduce((a, b) => a + b.total_amount, 0);
        },

        totalOrdersReport() {
            if (!this.reports) return 0;
            return Object.keys(this.reports)
                .map(date => this.reports[date].count_orders)
                .reduce((a, b) => a + b, 0);
        },

        totalOrdersUsersReport() {
            return this.users && this.users.reduce((a, b) => a + b.orders.length, 0);
        },
    },

    methods: {
        async fetchReports() {
            const { data: reports } = await this.$axios.post(this.$route('reports.orders'), { ...this.reportRange });
            this.reports = reports;
        },

        async fetchUserReports() {
            const { data: users } = await this.$axios.post(this.$route('reports.users'), { ...this.reportRange });
            this.users = users.data;
        },

        onChangeRange() {
            this.fetchReports();
            this.fetchUserReports();
        },

        showOrders(orders) {
            this.orders = orders;
            this.day = null;
            this.$refs.ordersListModal.open();
        },

        showDayOrders(report, date) {
            this.orders = report.orders;
            this.day = date;
            this.$refs.ordersListModal.open();
        },
    },
};
</script>
