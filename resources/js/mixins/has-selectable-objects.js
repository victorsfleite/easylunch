export default {
    data() {
        return {
            selectedObjects: [],
            objectKey: 'id',
        };
    },

    methods: {
        isSelected(object) {
            return this.selectedObjects.some(obj => obj[this.objectKey] == object[this.objectKey]);
        },

        beforeSelect(object) {},
        selectObject(object) {
            this.beforeSelect(object);
            this.selectedObjects.push(object);
            this.afterSelect(object);
        },
        afterSelect(object) {},

        toggleSelection(object) {
            if (this.isSelected(object)) {
                return this.removeSelection(object);
            }

            this.selectObject(object);
        },

        beforeRemoveSelection(object) {},
        removeSelection(object) {
            this.beforeRemoveSelection(object);
            this.selectedObjects = this.selectedObjects.filter(obj => obj[this.objectKey] !== object[this.objectKey]);
            this.afterRemoveSelection(object);
        },
        afterRemoveSelection(object) {},
    },
};
