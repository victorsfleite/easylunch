<template lang="pug">
    div
        div
            input-textarea(:form="form", field="description", label="Descrição do Pedido", v-model="form.description")

            .text-right
                b Total do Pedido:
                b.ml-3.text-success R$ {{ (10 + total).toFixed(2) }}

            .form-row(v-if="options.length")
                .col-12.col-sm-6.col-lg-4(v-for="option of availableOptions", :key="option.id")
                    .card.bg-light.mt-1.cursor-pointer(@click="toggleSelection(option)", :class="{'border-primary': isSelected(option)}")
                        .card-body.px-2.py-1.d-flex.align-items-center
                            i.fa-fw.mr-1(:class="isSelected(option) ? 'fas fa-check-square text-primary ' : 'far fa-square text-muted'")
                            small.mb-0
                                span {{ option.name }} -
                                b  R$ {{ option.price.toFixed(2) }}

        .d-flex.align-items-center.mt-3
            button.btn.btn-default.ml-auto.mr-2(@click="$emit('canceled')") Cancelar
            button-loading.btn.btn-primary(@click='update', v-if='form.id', :loading='form.submitting')
                | Atualizar
            button-loading.btn.btn-primary(@click='create', v-if='!form.id', :loading='form.submitting')
                | Enviar
</template>

<script>
import HasSelectableObjects from '@/mixins/has-selectable-objects';

export default {
    mixins: [HasSelectableObjects],

    props: {
        resource: { default: null },
        options: { default: () => [] },
    },

    data() {
        return {
            form: new Form(this.resource || {}, 'multipart'),
            availableOptions: [],
        };
    },

    mounted() {
        this.availableOptions = this.options.map(option => {
            this.$set(option, 'pivot', { price: option.price });
            return option;
        });
        this.selectedObjects = this.form.options || [];
    },

    watch: {
        selectedObjects() {
            this.form.options = this.selectedObjects;
        },
    },

    computed: {
        total() {
            return this.selectedObjects.reduce((p, c) => p + parseFloat(c.pivot.price), 0);
        },
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
