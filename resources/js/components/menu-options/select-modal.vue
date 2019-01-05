<template lang="pug">
    modal(ref="modal", body-classes="bg-light")
        span(slot="title") {{ showForm ? 'Nova Opção' : 'Menu de Opções' }}

        div(v-if="!showForm")
            .d-flex.align-items-center.mb-3(v-if="canManageOptions")
                input-text.mb-0.mr-3(left-icon="fa fa-search", v-model="search", placeholder="Filtrar opções...")
                button.btn.btn-primary.ml-auto(@click="addOption", v-if="options.length && canManageOptions")
                    i.fa.fa-plus.fa-fw
                    |  Opção

            .card.mb-0.mt-1.shadow-sm.cursor-pointer(v-if="!fetching", v-for="option of avaialableOptions", :key="option.id", @click="toggle(option)",
                :class="isChecked(option) ? 'border-primary' : 'border-white'")
                .card-body.p-2.d-flex.align-items-center.flex-wrap
                    i.fa-fw.mr-2(:class="getOptionIconClass(option)")
                    span.mr-2 {{ option.name }}
                    span.text-muted.ml-auto R$ {{ option.price.toFixed(2) }}
                    .btn-group.ml-2(v-if="canManageOptions")
                        button.btn.btn-sm.btn-default(@click.stop="edit(option)",
                            v-tooltip.hover="{title: 'Editar', delay: 200}")
                            i.fa.fa-pencil-alt
                        button-loading.btn.btn-sm.btn-default(@click.stop="remove(option)", :loading="isRemoving(option)",
                            v-tooltip.hover="{title: 'Excluir', delay: 200}")
                            i.far.fa-trash-alt(v-if="!isRemoving(option)")

            empty(v-if="!fetching && !options.length", title="Nehuma opção econtrada")
                button.btn.btn-primary(@click="addOption", v-if="!search") Adicionar Opção

            empty(v-if="!fetching && options.length && !avaialableOptions.length", title="Você selecionou todas as opções")

            loading(v-if="fetching", text="Carregando...")

        .d-flex.align-items-center(v-if="showForm")
            input-text.mb-0.mr-3.w-100(:form="form", field="name", label="Nome da Opção", v-model="form.name")
            input-text.mb-0(type="number", step="0.01", :form="form", field="price", label="Preço", v-model="form.price", style="width: 100px")

        .d-flex.align-items-center(slot="footer")
            span.mx-3(v-if="selectedOptions.length && !showForm") {{ selectedOptions.length }} opção(ões) selecionada(s)
            button.btn.btn-default.ml-2(v-if="!showForm", @click="close") Fechar
            button.btn.btn-default.ml-2(v-if="!showForm", @click="selectAll") Selecionar Todos
            button.btn.btn-primary.ml-2(v-if="!showForm", @click="okClick") Adicionar

            button.btn.btn-default.ml-2(v-if="showForm", @click="back")
                i.fa.fa-fw.fa-arrow-left
                |  Voltar
            button-loading.btn.btn-primary.ml-2(v-if="showForm", :loading="form.submitting", @click="saveOption") Salvar

</template>

<script>
import ModalComponent from '@/mixins/modal-component';

export default {
    mixins: [ModalComponent],

    props: {
        initialOptions: { default: () => [], type: Array },
        except: { default: () => [], type: Array },
    },

    data() {
        console.log('initial data', this.initialOptions);
        return {
            search: null,
            selectedOptions: [],
            removingIds: [],
            showForm: false,
            fetching: false,
            form: new Form(),
            options: this.initialOptions || [],
        };
    },

    created() {
        if (!this.options.length) {
            this.fetchOptions();
        }
    },

    computed: {
        canManageOptions() {
            return this.$user.is_chef || this.$user.is_admin;
        },

        avaialableOptions() {
            console.log('options', this.options);
            return this.options.filter(({ id }) => !this.except.map(i => i.id).includes(id));
        },
    },

    watch: {
        search: _.debounce(function() {
            if (!this.search) this.search = null;
            this.fetchOptions();
        }, 500),
    },

    methods: {
        async fetchOptions() {
            this.fetching = true;
            const params = { search: this.search };
            const { data: options } = await this.$axios.get(this.$route('options.index'), { params });
            this.options = options.data;
            this.fetching = false;
        },

        toggle(option) {
            if (this.isChecked(option)) {
                return (this.selectedOptions = this.selectedOptions.filter(({ id }) => option.id !== id));
            }

            this.selectedOptions.push(option);
        },

        isChecked(option) {
            return this.selectedOptions.some(o => o.id === option.id);
        },

        getOptionIconClass(option) {
            if (this.isChecked(option)) return 'fas fa-check-square text-primary';
            return 'far fa-square text-muted opacity-50';
        },

        okClick() {
            this.$emit('selected', this.selectedOptions);
            this.close();
            this.selectedOptions = [];
        },

        selectAll() {
            this.selectedOptions = this.avaialableOptions;
        },

        addOption() {
            this.form = new Form();
            this.showForm = true;
        },

        edit(option) {
            this.form = new Form(option);
            this.showForm = true;
        },

        async remove(option) {
            this.form = new Form(option);
            this.removingIds.push(option.id);
            await this.form.destroy(this.$route('options.index'));
            this.removingIds = this.removingIds.filter(id => id !== option.id);
            this.fetchOptions();
        },

        isRemoving({ id }) {
            return this.removingIds.includes(id);
        },

        back() {
            this.showForm = false;
        },

        async saveOption() {
            const response = await this.form.save(this.$route('options.index'));
            this.back();
            this.fetchOptions();
        },
    },
};
</script>
