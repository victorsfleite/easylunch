<template>
    <button :disabled="loading" @click.prevent.stop="$emit('click', $event)">
        <span v-if="loading"><i class="fa fa-spin fa-spinner"></i></span>
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        loading: { default: false, type: Boolean },
    },
};
</script>
