<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <input-text v-model="search" left-icon="fa fa-search" input-class="border-0 shadow-sm"
                    placeholder="Pesquisar...">
                </input-text>
            </div>

            <div class="col-md-4 ml-auto d-flex mb-3" v-if="$user.is_chef || $user.is_admin">
                <div class="ml-auto">
                    <a href="/menus/create" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Novo Menu
                    </a>
                </div>
            </div>
        </div>

        <resource-table class="border-0 shadow-sm"
            :url="$route('menus.index')"
            :columns="columns"
            :search="search"
            :can-delete="$user.is_chef || $user.is_admin"
            :can-edit="$user.is_chef || $user.is_admin"
            :options="tableOptions"
            default-sort="-date">
            <template slot="date" slot-scope="{ row: menu }">
                <a class="d-block" :href="routeToMenu(menu)" style="width: 180px">{{ menu.date | date }}</a>
            </template>

            <template slot="description" slot-scope="{ row: menu }">
                <div v-html="menu.description"></div>
            </template>

            <template slot="income" slot-scope="{ row: menu }">
                <div style="width: 80px">R$ {{ menu.income }},00</div>
            </template>

            <template slot="empty-table">
                <empty title="Nenhum menu ainda" height="250px" class="mt-4"></empty>
                <a :href="$route('menus.create')" class="btn btn-lg btn-primary d-block mt-3 mb-5 mx-auto w-25">Criar Menu</a>
            </template>
        </resource-table>
    </div>
</template>

<script>
export default {
    props: {
        date: { default: null },
    },

    data() {
        return {
            search: this.date,
            columns: ['id', 'date', 'image.original', 'description', 'orders.length', 'income'],
            tableOptions: {
                sortable: ['id', 'date', 'description'],
                headers: {
                    date: 'Data',
                    description: 'Descrição',
                    income: 'Fatura',
                    'image.original': 'Imagem',
                    'orders.length': 'Pedidos',
                },
                images: ['image.original'],
            },
        };
    },

    methods: {
        routeToMenu(menu) {
            return this.$route('menus.show', { menu: menu.id });
        },
    },
};
</script>
