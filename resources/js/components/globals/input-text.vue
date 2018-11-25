<template>
    <div class="form-group">
        <label v-if="label" :class="{ 'text-danger': form && form.errors.has(field) }">{{ label }}</label>

        <div class="input-group" v-if="group">
            <div class="input-group-prepend" v-if="groupPrepend">
                <span class="input-group-text">{{ groupPrepend }}</span>
            </div>

            <input :type="type" class="form-control"
                   :value="value"
                   @input="$emit('input', $event.target.value)"
                   :placeholder="placeholder"
                   :class="{ [inputClass]: inputClass, 'is-invalid': form && form.errors.has(field) }"
                   :disabled="disabled"
                   :readonly="readonly">

            <div class="input-group-append" v-if="groupAppend">
                <span class="input-group-text">{{ groupAppend }}</span>
            </div>

        </div>

        <input v-else
               :type="type" class="form-control"
               :min="min"
               :value="value"
               @input="$emit('input', $event.target.value)"
               :placeholder="placeholder"
               :class="{
                   [inputClass]: inputClass,
                   'is-invalid': form && form.errors.has(field),
                   'left-icon': leftIcon,
                   'right-icon': rightIcon,
                }"
               :disabled="disabled"
               :readonly="readonly">
        <i v-if="leftIcon" class="left-icon" :class="leftIcon"></i>
        <i v-if="rightIcon" class="right-icon" :class="rightIcon"></i>

        <p class="invalid-feedback" v-if="form && form.errors.has(field)">{{ form.errors.get(field) }}</p>
    </div>
</template>

<style lang="sass" scoped>
.form-group
    position: relative
    input.left-icon
        padding-left: 36px
    i.left-icon
        position: absolute
        top: 10px
        left: 10px
        opacity: 0.5
    input.right-icon
        padding-right: 36px
    i.right-icon
        position: absolute
        top: 10px
        right: 10px
        opacity: 0.5
</style>


<script>
export default {
    props: {
        label: { default: null },
        type: { default: 'text' },
        min: { default: null },
        placeholder: { default: null },
        form: { default: null },
        field: { default: null },
        value: { default: null },
        disabled: { default: false },
        readonly: { default: false },
        group: { default: false },
        groupPrepend: { default: null },
        groupAppend: { default: null },
        inputClass: { default: null },
        leftIcon: { default: null },
        rightIcon: { default: null },
    },
};
</script>
