<template>
    <div>
        <template v-if="!innerMenu">
            <div v-if="!$user.is_chef">
                <h1 class="text-center">Ainda não cadastramos menu hoje. Tente novamente mais tarde</h1>
            </div>

            <div v-if="$user.is_chef" class="d-flex align-items-center flex-column mt-5">
                <h1 class="text-center mb-4">Nenhum menu cadastrado para hoje. Deseja cadastrar agora?</h1>
                <a :href="$route('menus.create')" class="btn btn-lg btn-primary">Criar Menu de Hoje!</a>
            </div>
        </template>

        <template v-if="innerMenu">
            <h1 class="mb-0"> Menu de {{ innerMenu.date | date }}
                <a v-if="$user.is_chef || $user.is_admin" :href="$route('menus.edit', { menu: menu.id })" class="btn btn-sm btn-default">
                    <i class="fas fa-pencil-alt"></i>
                    Editar
                </a>
            </h1>
            <h6 class="subtitle text-muted mb-5"> Pedidos encerram exatamente às {{ timeLimit | date('HH:mm') }} </h6>

            <div class="row" v-if="innerMenu">
                <div class="col-md-4">
                    <img :src="innerMenu.image.original" class="img-thumbnail mb-3"
                        v-if="innerMenu.image && innerMenu.image.original">

                    <div v-html="innerMenu.description"></div>
                </div>

                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <h4 class="mb-0">
                            Pedidos ({{ orders.length }})
                            <small class="mb-0 subtitle text-black-50 text-uppercase" v-if="$user.is_chef || $user.is_admin">(Total: R$ {{ innerMenu.income_preview.toFixed(2) }})</small>
                        </h4>

                        <button type="button" @click="addNewOrder" class="btn btn-primary ml-auto" v-if="allowedToAddOrder && !showOrderForm">
                            <i class="fa fa-check-circle mr-2"></i>
                            Adicionar Pedido
                        </button>
                    </div>

                    <div class="card border-0 shadow-sm mb-5" v-if="showOrderForm">
                        <div class="card-body">
                            <orders-form :resource="order" @saved="savedOrder" @canceled="showOrderForm = false"
                                :options="menu.options"></orders-form>
                        </div>
                    </div>

                    <empty v-if="orders.length === 0" title="Não há pedidos ainda"></empty>

                    <order-card v-for="order of orders" :order="order" :key="order.id">
                        <template slot="actions">
                            <div class="float-right" v-if="!order.completed_at">
                                <button class="btn btn-sm btn-outline-secondary"
                                    @click="editOrder(order)"
                                    v-if="order.owner_id === $user.id"
                                    title="Editar">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>

                                <button-loading class="btn btn-sm btn-outline-success"
                                    :loading="completing(order)"
                                    @click="complete(order)"
                                    v-if="$user.is_chef" title="Marcar com Terminada">
                                    <i class="fa fa-thumbs-up" v-if="!completing(order)"></i>
                                    Pronto
                                </button-loading>

                                <button-loading class="btn btn-sm btn-outline-danger"
                                    :loading="removing(order)"
                                    @click="remove(order)"
                                    v-if="order.owner_id === $user.id" title="Remover Pedido">
                                    <i class="far fa-trash-alt" v-if="!removing(order)"></i>
                                </button-loading>
                            </div>
                        </template>
                    </order-card>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import moment from 'moment';
import OrderCard from '@/components/orders/card';

export default {
    components: { OrderCard },

    props: {
        menu: { type: Object },
    },

    data() {
        return {
            orders: (this.menu && this.menu.orders) || [],
            order: { menu_id: this.menu && this.menu.id },
            completingIds: [],
            removingIds: [],
            interval: null,
            innerMenu: this.menu,
            allowed_time: '10:30',
            showOrderForm: false,
        };
    },

    computed: {
        ordersUrl() {
            return this.$route('orders.index', { menu: this.menu.id });
        },

        addOrder() {
            return this.$route('orders.create', { menu: this.menu.id });
        },

        allowedToAddOrder() {
            return this.$user.is_admin || this.$user.is_impersonated || (!this.$user.is_chef && this.isMenuOfToday && this.timeIsAllowed);
        },

        isMenuOfToday() {
            return moment(this.menu.date).isSame(moment().format('YYYY-MM-DD'));
        },

        timeLimit() {
            return moment().format('YYYY-MM-DD') + ' ' + moment(this.allowed_time, 'HH:mm').format('HH:mm');
        },

        menuTime() {
            return moment(this.menu.date).format('YYYY-MM-DD') + ' ' + moment().format('HH:mm');
        },

        timeIsAllowed() {
            return moment(this.menuTime).isBefore(this.timeLimit);
        },
    },

    created() {
        if (this.menu) {
            this.interval = setInterval(() => {
                this.refresh();
            }, 60000);
        }
    },

    destroy() {
        if (this.interval) {
            clearInterval(this.interval);
        }
    },

    methods: {
        async complete(order) {
            try {
                this.completingIds.push(order.id);
                const { data: completed } = await this.$axios.put(this.$route('orders.complete', { menu: this.menu.id, order: order.id }));
                order.completed_at = completed.completed_at;
                this.refresh();
            } finally {
                this.removeIdOf(order, 'completingIds');
            }
        },

        async remove(order) {
            try {
                this.removingIds.push(order.id);
                const { data: removed } = await this.$axios.delete(this.$route('orders.destroy', { menu: this.menu.id, order: order.id }));
                this.refresh();
            } finally {
                this.removeIdOf(order, 'removingIds');
            }
        },

        completing(order) {
            return this.completingIds.includes(order.id);
        },

        removing(order) {
            return this.removingIds.includes(order.id);
        },

        removeIdOf(order, list) {
            this[list] = this[list].filter(id => id !== order.id);
        },

        async refresh() {
            const { data: menu } = await this.$axios.get(this.$route('menus.show', { menu: this.menu.id }));
            this.orders = menu.data.orders;
            this.innerMenu = menu.data;
        },

        addNewOrder() {
            this.showOrderForm = true;
            this.order = { menu_id: this.menu.id };
        },

        editOrder(order) {
            this.order = order;
            this.showOrderForm = true;
        },

        savedOrder() {
            this.showOrderForm = false;
            this.refresh();
        },
    },
};
</script>
