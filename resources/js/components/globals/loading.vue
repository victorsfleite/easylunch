<template lang="pug">
    .d-flex.align-items-center.flex-column.text-black-50
        i.fa.fa-spinner.fa-spin.fa-3x
        span(v-if="text") {{ text }}
</template>

<script>
export default {
    props: {
        text: { default: 'Loading...' },
    },
};
</script>
