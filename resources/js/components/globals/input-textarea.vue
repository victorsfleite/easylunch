<template>
    <div class="form-group">
        <label v-if="label" :class="{ 'text-danger': form && form.errors.has(field) }">{{ label }}</label>
        <textarea class="form-control"
            :min="min"
            :value="value"
            @input="$emit('input', $event.target.value)"
            :placeholder="placeholder"
            :class="{ [inputClass]: inputClass, 'is-invalid': form && form.errors.has(field) }"
            :disabled="disabled"
            :readonly="readonly"></textarea>

        <p class="invalid-feedback" v-if="form && form.errors.has(field)">{{ form.errors.get(field) }}</p>
    </div>
</template>

<script>
export default {
    props: {
        label: { default: null },
        min: { default: null },
        rows: { default: 3 },
        placeholder: { default: null },
        form: { default: null },
        field: { default: null },
        value: { default: null },
        disabled: { default: false },
        readonly: { default: false },
        inputClass: { default: '' },
    },
};
</script>
