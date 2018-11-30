<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <input-text v-model="search" left-icon="fa fa-search" input-class="border-0 shadow-sm"></input-text>
            </div>

            <div class="col-md-4 ml-auto d-flex mb-3">
                <div class="ml-auto">
                    <a href="/orders/create" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        New Order
                    </a>
                </div>
            </div>
        </div>

        <resource-table class="border-0 shadow-sm"
            url="/orders"
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
            columns: ['id', 'description', 'owner_id', 'menu_id', 'completed_at'],
            tableOptions: {
                sortable: ['id', 'description', 'owner_id', 'menu_id', 'completed_at'],
            },
        };
    },
};
</script>
