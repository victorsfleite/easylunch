<template lang="pug">
    div
        .row.mb-3
            .col-md-4.mb-3
                h3.mb-0 {{ this.resource ? 'Editar Menu' : 'Novo Menu' }}
            .col-md-8.mb-3
                .d-flex.align-items-center
                    div.ml-auto
                        a.btn.btn-default.ml-2(v-if="form.id", :href="$route('menus.show', { menu: this.form.id })") Pedidos do Menu
                        a.btn.btn-default.ml-2(:href="$route('menus')") Voltar Pra Lista
                    button-loading.btn.btn-primary.ml-2(@click='update', v-if='form.id', :loading='form.submitting')
                        | Atualizar
                    button-loading.btn.btn-primary.ml-2(@click='create', v-if='!form.id', :loading='form.submitting')
                        | Criar

        .row
            .col-12.col-md-4
                select-file.mb-3.d-flex.justify-content-center(
                    @change="form.new_image = $event"
                    :image="form.image && form.image.original"
                    accept="image/*",
                    width="200px",
                    icon="fa fa-3x fa-camera") Imagem

            .col-12.col-md-8
                input-text(type="date", :form="form", field="date", label="Data", v-model="form.date")
                input-texteditor(:form="form", field="description", label="Descrição do Menu", v-model="form.description")

                select-options-modal(ref="optionsModal", @selected="onSelectedOptions", :except="form.options")

                .d-flex.align-items-center.mb-3
                    h4.mb-0 Opções
                    button.btn.btn-secondary.btn-sm.mb-1.ml-auto(@click="addOptions")
                        i.fa.fa-plus
                        |  Adicionar Opções

                .card.mt-2(v-for="option of form.options", :key="option.id")
                    .card-body.p-2.d-flex.flex-wrap.align-items-center
                        | {{ option.name }}
                        .ml-auto.d-flex.align-items-center(style="max-width: 120px")
                            span.mr-1 R$
                            input-text.mb-0(type="number", v-model="option.pivot && option.pivot.price" input-class="form-control-sm")
                            button.btn.btn-sm.btn-danger.ml-1.mb-0(@click="removeOption(option)",
                            v-tooltip.hover="'Remover Opção'")
                                i.fa.fa-times
</template>

<script>
import moment from 'moment';
import SelectOptionsModal from '@/components/menu-options/select-modal';

export default {
    components: { SelectOptionsModal },

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

        addOptions() {
            this.$refs.optionsModal.open();
        },

        onSelectedOptions(options) {
            options.forEach(option => this.$set(option, 'pivot', { price: option.price }));
            if (!this.form.options) this.$set(this.form, 'options', []);
            this.form.options = this.form.options.concat(options);
        },

        removeOption(option) {
            this.form.options = this.form.options.filter(({ id }) => option.id !== id);
        },
    },
};
</script>
