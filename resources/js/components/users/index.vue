<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <input-text v-model="search" left-icon="fa fa-search" input-class="border-0 shadow-sm"
                    placeholder="Pesquisar...">
                </input-text>
            </div>

            <div class="col-md-4 ml-auto d-flex mb-3">
                <div class="ml-auto">
                    <a href="/users/create" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        Novo Usuário
                    </a>
                </div>
            </div>
        </div>

        <resource-table class="border-0 shadow-sm"
            url="/users"
            :columns="columns"
            :search="search"
            :options="tableOptions"
            :can-show="false"
            default-sort="-id">
            <template slot="role" slot-scope="{ row: user }">
                <h5><span class="badge" :class="'badge-'+roleBadge(user.role)">{{ user.role | capitalize }}</span></h5>
            </template>

            <template slot="more-actions" slot-scope="{ resource: user }">
                <a :href="$route('users.impersonate', { user: user.id })" class="btn btn-sm bg-transparent text-black-50"
                    v-tooltip="{title: 'Personificar Usuário', boundary: 'window'}">
                    <i class="fa fa-user-secret"></i>
                </a>
            </template>
        </resource-table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            search: null,
            columns: ['id', 'name', 'email', 'role'],
            tableOptions: {
                sortable: ['id', 'name', 'email', 'role'],
                headers: {
                    name: 'Nome',
                    role: 'Perfil',
                },
            },
        };
    },

    methods: {
        roleBadge(userRole) {
            switch (userRole) {
                case 'admin':
                    return 'success';
                case 'chef':
                    return 'primary';
                default:
                    return 'secondary';
            }
            return user.role === 'admin';
        },
    },
};
</script>
