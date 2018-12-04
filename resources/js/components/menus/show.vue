<template>
    <div>
        <h1 class="mb-5">Menu de {{ innerMenu.date | date }}</h1>

        <div class="row">
            <div class="col-md-4">
                <img :src="innerMenu.image.original" class="img-thumbnail mb-3" v-if="innerMenu.image.original">

                <div v-html="innerMenu.description"></div>
            </div>

            <div class="col-md-8">
                <div class="d-flex align-items-center mb-3">
                    <h4 class="mb-0">
                        Pedidos ({{ orders.length }})
                        <small class="mb-0 subtitle text-black-50 text-uppercase" v-if="$user.is_chef || $user.is_admin">(Total: R$ {{ innerMenu.income_preview.toFixed(2) }})</small>
                    </h4>

                    <a :href="addOrder" class="btn btn-primary ml-auto">
                        <i class="fa fa-check-circle mr-2"></i>
                        Adicionar Pedido
                    </a>
                </div>

                <empty v-if="orders.length === 0" title="Não há pedidos ainda"></empty>

                <div class="card border-0 shadow-sm mb-3" v-for="order of orders" :key="order.id">
                    <div class="card-body px-3 pt-3 pb-0">
                        <b>{{ order.owner && order.owner.name }}: </b>
                        <span>{{ order.description }}</span>
                    </div>

                    <div class="px-3 py-2">
                        <span class="text-success fs-sm" v-if="order.completed_at">
                            <i class="fa fa-check-double"></i>
                            Concluído {{ order.completed_at | from_now }}
                        </span>

                        <span class="text-black-50 fs-sm" v-if="!order.completed_at">
                            <i class="far fa-question-circle"></i>
                            Pendente
                        </span>

                        <div class="float-right" v-if="!order.completed_at">
                            <a :href="$route('orders.edit', { menu: menu.id, order: order.id })"
                                v-if="order.owner_id === $user.id"
                                class="btn btn-sm btn-outline-secondary" title="Editar">
                                <i class="fa fa-pencil-alt"></i>
                            </a>

                            <button-loading :loading="completing(order)" @click="complete(order)"
                                v-if="$user.is_chef"
                                class="btn btn-sm btn-outline-success" title="Marcar com Terminada">
                                <i class="fa fa-thumbs-up" v-if="!completing(order)"></i>
                                Pronto
                            </button-loading>

                            <button-loading :loading="removing(order)" @click="remove(order)"
                                v-if="order.owner_id === $user.id"
                                class="btn btn-sm btn-outline-danger" title="Remover Pedido">
                                <i class="far fa-trash-alt" v-if="!removing(order)"></i>
                            </button-loading>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        menu: { required: true, type: Object },
    },

    data() {
        return {
            orders: this.menu.orders,
            completingIds: [],
            removingIds: [],
            interval: null,
            innerMenu: this.menu,
        };
    },

    computed: {
        ordersUrl() {
            return this.$route('orders.index', { menu: this.menu.id });
        },

        addOrder() {
            return this.$route('orders.create', { menu: this.menu.id });
        },
    },

    created() {
        this.interval = setInterval(() => {
            this.refresh();
        }, 60000);
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
    },
};
</script>
