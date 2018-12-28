<template lang="pug">
    div
        .row.mb-0
            .col-md-4.mb-3
                h3.mb-0 {{ this.resource ? 'Editar' : 'Novo' }} Usuário
            .col-md-8.mb-3
                .d-flex.align-items-center
                    a.btn.btn-default.ml-auto.mr-2(:href="$route('users')") Voltar Pra Lista
                    button-loading.btn.btn-primary(@click='update', v-if='form.id', :loading='form.submitting')
                        | Atualizar
                    button-loading.btn.btn-primary(@click='create', v-if='!form.id', :loading='form.submitting')
                        | Criar
        div
            input-text(:form="form", field="name", label="Nome", v-model="form.name")
            input-text(:form="form", field="email", type="email" label="Email", v-model="form.email")
            input-select(:form="form", field="role", label="Perfil", :options="userRoles", v-model="form.role", track-by="value", placeholder="Selecione um Perfil")
            input-text(:form="form", field="password", label="Senha", type="password" v-model="form.password")
            input-text(:form="form", field="password_confirmation", label="Confirmar Senha", type="password", v-model="form.password_confirmation")
</template>

<script>
export default {
    props: {
        resource: { default: null },
    },

    data() {
        return {
            form: new Form(this.resource || {}, 'multipart'),
            userRoles: [],
        };
    },

    async created() {
        const { data: roles } = await this.$axios.get(this.$route('users.roles'));
        this.userRoles = roles;
    },

    methods: {
        async create() {
            const { data: created } = await this.form.post(this.$route('users.store'));

            this.$toasted.success('Resource created');
            window.location.href = this.$route('users');
        },

        async update() {
            const { data: updated } = await this.form.put(
                this.$route('users.update', {
                    user: this.form.id,
                })
            );

            this.form = new Form(updated.data);
            this.$toasted.success('Resource updated');
            window.location.href = this.$route('users');
        },
    },
};
</script>
