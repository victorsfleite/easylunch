<template lang="pug">
    .form-group
        label(v-if='label', :class="{ 'text-danger': form && form.errors.has(field) }") {{ label }}
        select.form-control(v-model='selected', @change='onSelect', :class="{ [inputClass]: inputClass, 'is-invalid': form && form.errors.has(field) }", :disabled='disabled', :readonly='readonly')
            option(:value='null', selected='', disabled='', v-if='placeholder') {{ placeholder }}
            option(v-for='option of options', :value='getValueFor(option)', :key='option[trackBy]') {{ option[labeledBy]  }}
        p.invalid-feedback(v-if='form && form.errors.has(field)') {{ form.errors.get(field) }}
</template>

<script>
export default {
    props: {
        label: { default: null }, // the label of the field
        labeledBy: { default: 'name' }, // the property name of the option object to be displayed in the options
        trackBy: { default: 'id' }, // the property name of the option object to be used as value of the component
        objectValue: { default: false }, // TRUE if should return the object instead of the trackBy value
        value: { default: null },
        options: { default: () => [] },
        placeholder: { default: null },
        form: { default: null },
        field: { default: null },
        disabled: { default: false },
        readonly: { default: false },
        inputClass: { default: '' },
    },
    data() {
        return {
            selected: this.value,
        };
    },
    watch: {
        value() {
            this.selected = this.value;
        },
    },
    methods: {
        onSelect() {
            this.$emit('input', this.selected);
        },
        getValueFor(obj) {
            if (this.objectValue) return obj;
            return obj[this.trackBy];
        },
    },
};
</script>
