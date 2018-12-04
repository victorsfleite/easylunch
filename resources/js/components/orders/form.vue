<template lang="pug">
    div
        .row.mb-0
            .col-md-4.mb-3
                h3.mb-0 Novo Pedido
            .col-md-8.mb-3
                .d-flex.align-items-center
                    a.btn.btn-default.ml-auto.mr-2(:href="$route('menus.show', { menu: form.menu_id })") Voltar ao Menu
                    button-loading.btn.btn-primary(@click='update', v-if='form.id', :loading='form.submitting')
                        | Atualizar
                    button-loading.btn.btn-primary(@click='create', v-if='!form.id', :loading='form.submitting')
                        | Criar
        div
            input-textarea(:form="form", field="description", label="Descrição do Pedido", v-model="form.description")
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

            this.$toasted.success('Resource created');
            window.location.href = this.$route('menus.show', { menu: this.form.menu_id });
        },

        async update() {
            const { data: updated } = await this.form.put(
                this.$route('orders.update', {
                    order: this.form.id,
                    menu: this.form.menu_id,
                })
            );

            this.form = new Form(updated.data);
            this.$toasted.success('Resource updated');
            window.location.href = this.$route('menus.show', { menu: this.form.menu_id });
        },
    },
};
</script>
