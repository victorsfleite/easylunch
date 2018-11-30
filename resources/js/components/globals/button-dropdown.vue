<template lang="pug">
    span
        button.btn.dropdown-toggle(
            data-toggle="dropdown",
            :class="buttonClasses",
            @click="$emit('click', $event)")
            slot

        .dropdown-menu(:class="dropdownClasses")
            slot(name="items")
</template>

<script>
export default {
    props: {
        buttonClasses: { default: 'btn-default' },
        dropdownClasses: { default: '' },
    }
}
</script>
