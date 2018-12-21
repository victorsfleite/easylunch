<template lang="pug">
    div
        div
            input-textarea(:form="form", field="description", label="Descrição do Pedido", v-model="form.description")

        .d-flex.align-items-center
            button.btn.btn-default.ml-auto.mr-2(@click="$emit('canceled')") Cancelar
            button-loading.btn.btn-primary(@click='update', v-if='form.id', :loading='form.submitting')
                | Atualizar
            button-loading.btn.btn-primary(@click='create', v-if='!form.id', :loading='form.submitting')
                | Criar
</template>

<script>
export default {
    props: {
        resource: { default: null },
    },

    data() {
        return {
            form: new Form(this.resource || {}, 'multipart'),
        };
    },

    methods: {
        async create() {
            const { data: created } = await this.form.post(this.$route('orders.store', { menu: this.form.menu_id }));

            this.$toasted.success('Pedido criado com sucesso.');
            this.$emit('saved', created.data);
        },

        async update() {
            const { data: updated } = await this.form.put(
                this.$route('orders.update', {
                    order: this.form.id,
                    menu: this.form.menu_id,
                })
            );

            this.form = new Form(updated.data);
            this.$toasted.success('Pedido atualizado com sucesso.');
            this.$emit('saved', updated.data);
        },
    },
};
</script>
