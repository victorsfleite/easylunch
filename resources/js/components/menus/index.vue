<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <input-text v-model="search" left-icon="fa fa-search"></input-text>
            </div>

            <div class="col-md-4 ml-auto d-flex mb-3">
                <div class="ml-auto">
                    <a href="/menus/create" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        New Menu
                    </a>
                </div>
            </div>
        </div>

        <resource-table
            url="/menus"
            :columns="columns"
            :search="search"
            :options="tableOptions"
            default-sort="-id">
        </resource-table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            search: null,
            columns: ['id', 'date', 'description'],
            tableOptions: {
                sortable: ['id', 'date', 'description'],
            },
        };
    },
};
</script>
