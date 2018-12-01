<template>
    <div class="form-group">
        <label v-if="label" :class="{ 'text-danger': form && form.errors.has(field) }">{{ label }}</label>
        <div ref="editor" class="form-control ql-container ql-bubble"
            :class="{ focused, 'is-invalid': form && form.errors.has(field) }">
        </div>

        <p class="invalid-feedback d-block" v-if="form && form.errors.has(field)">{{ form.errors.get(field) }}</p>
    </div>
</template>

<style lang="sass">
.form-group
    .form-control.ql-container
        .ql-editor
            padding: 0 !important
            min-height: 48px
</style>


<script>
import Quill from 'quill';
import 'quill/dist/quill.bubble.css';

export default {
    props: {
        label: { default: null },
        placeholder: { default: null },
        form: { default: null },
        field: { default: null },
        value: { default: null },
        disabled: { default: false },
        readonly: { default: false },
        inputClass: { default: '' },
        options: { default: () => ({}), type: Object },
    },

    data() {
        return {
            editor: null,
            focused: false,
            quillOptions: {
                theme: 'bubble',
                readOnly: this.readonly,
                placeholder: this.placeholder,
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote'],
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                    ],
                },
            },
        };
    },

    watch: {
        disabled() {
            this.editor.enable(!this.disabled);
        },
    },

    mounted() {
        this.editor = new Quill(this.$refs.editor, { ...this.quillOptions, ...this.options });
        this.editor.on('text-change', this.onChange.bind(this));
        this.editor.on('selection-change', range => (this.focused = range !== null));
        this.editor.container.firstChild.innerHTML = this.value;
        this.editor.enable(!this.disabled);
    },

    methods: {
        onChange() {
            this.$emit('input', this.editor.container.firstChild.innerHTML);
        },
    },
};
</script>
