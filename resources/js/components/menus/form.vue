<template lang="pug">
    div
        .row.mb-0
            .col-md-4.mb-3
                h3.mb-0 Menu Form
            .col-md-8.mb-3
                .d-flex.align-items-center
                    a.btn.btn-default.ml-auto.mr-2(:href="$route('menus')") Voltar Pra Lista
                    button-loading.btn.btn-primary(@click='update', v-if='form.id', :loading='form.submitting')
                        | Atualizar
                    button-loading.btn.btn-primary(@click='create', v-if='!form.id', :loading='form.submitting')
                        | Criar
        div
            select-file.d-flex.justify-content-center(
                @change="form.new_image = $event"
                :image="form.image && form.image.original"
                accept="image/*",
                width="200px",
                icon="fa fa-3x fa-camera") Imagem
            input-text(type="date", :form="form", field="date", label="Data", v-model="form.date")
            input-texteditor(:form="form", field="description", label="Descrição do Menu", v-model="form.description")

            pre form: {{ form }}
</template>

<script>
import moment from 'moment';

export default {
    props: {
        resource: { default: null },
    },

    data() {
        return {
            form: new Form(this.resource || {}, 'multipart'),
        };
    },

    created() {
        if (!this.form.id) {
            this.form.date = moment().format('YYYY-MM-DD');
        }
    },

    methods: {
        async create() {
            this.treatDescription();
            const { data: created } = await this.form.post(this.$route('menus.store'));

            this.$toasted.success('Resource created');
            window.location.href = this.$route('menus');
        },

        async update() {
            this.treatDescription();
            const { data: updated } = await this.form.put(
                this.$route('menus.update', {
                    menu: this.form.id,
                })
            );

            this.form = new Form(updated.data);
            this.$toasted.success('Resource updated');
            window.location.href = this.$route('menus');
        },

        treatDescription() {
            if (this.form.description === '<p><br></p>') {
                this.form.description = '';
            }
        },
    },
};
</script>
