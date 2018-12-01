<template lang="pug">
    div
        h3.mb-3 Relatório de Pedidos

        .row
            .col-md-4
                .form-group
                    v-date-picker(mode="range", v-model="reportRange", @input="fetchReports")
                        input.form-control(
                            slot-scope="props"
                            :value="props.inputValue"
                            placeholder="Teste"
                            @change="props.updateValue($event.target.value)")

        .card
            table.table.mb-0
                thead
                    tr
                        th.border-top-0 Dia
                        th.border-top-0 Nº de Pedidos
                        th.border-top-0 Total
                tbody
                    tr(v-for="(report, date) of reports", :key="report.id")
                        td
                            a(:href="$route('menus', { date })") {{ date | date }}
                        td {{ report.count_orders }}
                        td R$ {{ report.total.toFixed(2) }}
                    //- tr
                    //-     td
                    //-         a(href="#") 10/12/2018
                    //-     td 5
                    //-     td R$ 50,00
                    //- tr
                    //-     td
                    //-         a(href="#") 10/12/2018
                    //-     td 5
                    //-     td R$ 50,00
                    tr.text-uppercase.font-weight-bold.text-success
                        td.text-right(colspan="2") TOTAL :
                        td R$ {{ total.toFixed(2) }}
</template>

<script>
import moment from 'moment';

export default {
    data() {
        return {
            reports: null,
            reportRange: {
                start: moment()
                    .startOf('isoWeek')
                    .toDate(),
                end: moment()
                    .endOf('isoWeek')
                    .toDate(),
            },
        };
    },

    created() {
        this.fetchReports();
    },

    computed: {
        total() {
            if (!this.reports) return 0;
            const total = 0;
            return Object.keys(this.reports)
                .map(date => this.reports[date].total)
                .reduce((a, b) => a + b, 0);
        },
    },

    methods: {
        async fetchReports() {
            const { data: reports } = await this.$axios.post(this.$route('menus.get-reports'), { ...this.reportRange });
            this.reports = reports;
        },
    },
};
</script>
