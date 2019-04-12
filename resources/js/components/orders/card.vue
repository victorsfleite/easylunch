<template>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body px-3 pt-3 pb-0">
            <div class="d-inline">
                <select-dropdown v-if="$user.is_admin && users.length"
                    v-model="order.owner"
                    :options="users"
                    placeholder="Selecionar Usuário"
                    search-placeholder="Pesquisar..."
                    searchable>
                </select-dropdown>
                <b v-else>{{ order.owner && order.owner.name }}</b>
            </div>:

            <span>{{ order.description }}</span>
            <div class="d-flex flex-wrap">
                <span class="badge badge-secondary mr-2" v-for="option of order.options" :key="option.id">
                    {{ option.name }} - R$ {{ (+option.pivot.price).toFixed(2) }}
                </span>
            </div>
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

            <span class="text-black-50 fs-sm ml-3" v-if="showTimestamps">Criado {{ order.created_at | date('LLLL') }}</span>

            <span class="text-black-50 fs-sm ml-3">
                Preço: R$ {{ order.price.toFixed(2) }}
            </span>

            <slot name="actions"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        order: { required: true, type: Object },
        showTimestamps: { default: false },
        users: { type: Array, default: () => [] },
    },

    watch: {
        async 'order.owner.id'(value) {
            try {
                const { data } = await this.$axios.put(this.$route('orders.update-owner', { order: this.order.id }), { user_id: value });
                this.$emit('ownerUpdated', data);
                this.$toasted.success('Dono do pedido atualizado com sucesso.');
            } catch (error) {
                this.$toasted.error('Ops! Não foi possível atualizar o dono do pedido.');
            }
        },
    },
};
</script>
