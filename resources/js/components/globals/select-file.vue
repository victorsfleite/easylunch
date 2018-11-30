<template>
    <div>
        <div ref="container" @click="selectFile" class="select-file text-white cursor-pointer d-flex flex-column align-items-center justify-content-center"
            :title="title"
            data-toggle="tooltip"
            data-placement="top"
            :style="{ width, height: width, background }"
            :class="{ 'bg-success': file && !isImage, 'has-errors': error, rounded, 'rounded-circle': circled }">

            <slot name="icon" v-if="!isImage">
                <i :class="fileIcon"></i>
            </slot>

            <i class="check-icon text-danger cursor-pointer fas fa-fw fa-times-circle" v-if="file"
                @click.prevent.stop="remove"></i>
            <slot v-if="!isImage"></slot>
            <input ref="input" :accept="accept" type="file" hidden @change="onChange">
        </div>

        <small class="text-danger mt-1" v-if="error">{{ error }}</small>
    </div>
</template>

<style lang="scss">
.select-file {
    background-color: grey;
}
</style>


<style lang="scss" scoped>
@import '~styles/variables';

.select-file {
    position: relative;
    border: 1px solid transparent;
    overflow: hidden;

    &.has-errors {
        border: 1px solid $danger;
    }

    .check-icon {
        position: absolute;
        font-size: 1.2em;
        top: 0;
        right: 0;
        opacity: 0;
        transition: opacity 0.1s ease;
        background: white;
        border: 1px solid white;
        border-radius: 30px;
        width: 19px;
    }

    &:hover {
        .check-icon {
            opacity: 0.5;

            &:hover {
                opacity: 1;
                cursor: pointer;
            }
        }
    }
}
</style>


<script>
export default {
    props: {
        width: { default: '100px' },
        image: { default: null },
        media: { default: null },
        accept: { default: '*' },
        icon: { default: 'fa fa-3x fa-file' },
        rounded: { default: true },
        circled: { default: false },
        iconMap: {
            default: () => ({
                pdf: 'fa fa-3x fa-file-pdf',
                zip: 'fa fa-3x fa-file-archive',
                csv: 'fa fa-3x fa-file-csv',
                doc: 'fa fa-3x fa-file-word',
                docx: 'fa fa-3x fa-file-word',
                xlsx: 'fa fa-3x fa-file-excel',
                xls: 'fa fa-3x fa-file-excel',
                audio: 'fa fa-3x fa-file-audio',
                video: 'fa fa-3x fa-file-video',
            }),
            type: Object,
        },
        imageTypes: {
            default: () => ['image/'],
        },
        replaceOnSelection: { default: true },
        error: { default: null },
    },

    data() {
        return {
            reader: null,
            source: this.image,
            file: this.media,
            tooltip: null,
        };
    },

    mounted() {
        this.updateTooltip();
    },

    computed: {
        title() {
            return this.file && (this.file.file_name || this.file.name);
        },
        fileIcon() {
            const type =
                this.file &&
                Object.keys(this.iconMap).find(iconType => {
                    const mimeType = this.file.type || this.file.mime_type || '';
                    return mimeType.includes(iconType);
                });
            return this.iconMap[type] || this.icon;
        },
        isImage() {
            const mimeTypeImage =
                this.file &&
                this.imageTypes.some(imageType => {
                    const mimeType = this.file.type || this.file.mime_type || '';
                    return mimeType.includes(imageType);
                });
            return mimeTypeImage || this.image;
        },
        background() {
            if (!this.isImage) return '';
            return `url(${this.source}) center center / cover no-repeat`;
        },
    },

    watch: {
        title() {
            this.title ? this.$nextTick(() => this.updateTooltip(), 100) : this.tooltip.tooltip('dispose');
        },
    },

    methods: {
        selectFile() {
            this.$refs.input.click();
        },

        onChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.$emit('update:error', null);
            this.file = file;
            this.reader = new FileReader();
            this.reader.onload = this.onFileLoad.bind(this);
            this.reader.readAsDataURL(this.file);
        },

        onFileLoad() {
            this.source = this.reader.result;
            this.$emit('change', this.file);
        },

        updateTooltip() {
            if (this.tooltip) {
                this.tooltip.tooltip('dispose');
            }

            if (this.$refs.container) {
                this.tooltip = $(this.$refs.container).tooltip({ boundary: 'window' });
            }
        },

        remove() {
            this.file = null;
            this.$emit('change', this.file);
            this.$refs.input.value = '';
            this.tooltip && this.tooltip.tooltip('dispose');
        },
    },
};
</script>
