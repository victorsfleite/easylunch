<template>
    <div>
        <h1>Menu de {{ menu.date | date }}</h1>

        <div class="row">
            <div class="col-md-4">
                <img :src="menu.image.original" class="img-thumbnail mb-3" v-if="menu.image.original">

                <div v-html="menu.description"></div>
            </div>

            <div class="col-md-8">
                <p>The Orders will be listed here</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        menu: { required: true, type: Object },
    },
};
</script>
