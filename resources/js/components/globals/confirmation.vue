<template lang="pug">
    modal(ref="modal", :centered="true", effect="zoomin")
        span(slot="title" v-if="innerTitle") {{ innerTitle }}
        .lead(v-if="innerMessage") {{ innerMessage }}
        div(slot="footer")
            button.btn.bg-transparent(type='button', @click="no") {{ innerNoLabel }}
            button-loading.btn.ml-2(
                :class="{'btn-danger': innerDangerous, 'btn-primary': !innerDangerous}"
                type='button',
                @click="yes",
                :loading="loading") {{ innerYesLabel }}
</template>

<style lang="sass" scoped>
.modal.zoomin
    transform: scale(1.2)
    opacity: 0
    transition: all .2s ease
    &.show
        transform: scale(1)
        opacity: 1
        transition: all .2s ease
>>> .modal-header
    padding-bottom: 0
</style>

<script>
export default {
    props: {
        title: { default: 'Confirmation' },
        message: { default: 'Are you sure you want to perform this action?' },
        yesLabel: { default: "Yes, I'm sure" },
        noLabel: { default: "No, I'm not" },
        async: { default: true },
        dangerous: { default: false },
    },

    data() {
        return {
            resolve: null,
            reject: null,
            loading: false,
            innerTitle: this.title,
            innerMessage: this.message,
            innerYesLabel: this.yesLabel,
            innerNoLabel: this.noLabel,
            innerDangerous: this.dangerous,
        };
    },

    methods: {
        ask(config = {}) {
            this.setup(config);
            this.$refs.modal.open();
            return new Promise((resolve, reject) => {
                this.resolve = resolve;
                this.reject = reject;
            });
        },
        close() {
            this.loading = false;
            this.$refs.modal.close();
        },
        yes() {
            this.resolve(true);
            if (!this.async) return this.close();
            this.loading = true;
        },
        no() {
            this.reject(false);
            this.close();
        },
        setup(config = {}) {
            this.innerMessage = config.message !== undefined ? config.message : this.innerMessage;
            this.innerTitle = config.title !== undefined ? config.title : this.innerTitle;
            this.innerNoLabel = config.noLabel !== undefined ? config.noLabel : this.innerNoLabel;
            this.innerYesLabel = config.yesLabel !== undefined ? config.yesLabel : this.innerYesLabel;
            this.innerDangerous = config.dangerous !== undefined ? config.dangerous : this.innerDangerous;
        },
        stopLoading() {
            this.loading = false;
        },
    },
};
</script>
