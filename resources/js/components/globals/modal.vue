<template>
    <div class="modal" :class="effect">
        <div class="modal-dialog" :class="{'modal-dialog-centered': centered, ['modal-' + size]: true}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <slot name="title">Modal title</slot>
                    </h5>

                    <small><slot name="subtitle"></slot></small>

                    <button class="close" type="button" @click="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <slot></slot>
                </div>

                <div class="modal-footer" v-if="hasFooter">
                    <slot name="footer"><button class="btn btn-default" type="button" data-dismiss="modal">Close</button><button class="btn btn-primary" type="button">Save changes</button></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        size: { default: 'md', type: String }, // sizes: 'sm', 'md', 'lg'
        centered: { default: false },
        effect: { default: 'fade' },
        hasFooter: { default: true },
    },
    methods: {
        open() {
            this.$emit('opened');
            $(this.$el).modal('show');
        },
        close() {
            this.$emit('closed');
            $(this.$el).modal('hide');
        },
    },
    mounted() {
        const el = $(this.$el).detach();
        $('body').prepend(el);
        $(this.$el).on('hide.bs.modal', (event) => { this.$emit('hide', event) });
        $(this.$el).on('hidden.bs.modal', (event) => { this.$emit('hidden', event) });
        $(this.$el).on('show.bs.modal', (event) => { this.$emit('show', event) });
        $(this.$el).on('shown.bs.modal', (event) => {
            this.$emit('shown', event);
            $('body').addClass('modal-open');
        });
    }
}
</script>

<style lang="sass" scoped>
    .close
        outline: none
</style>
