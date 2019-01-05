<template>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body px-3 pt-3 pb-0">
            <b>{{ order.owner && order.owner.name }}:</b>
            <span>{{ order.description }}</span>
            <div class="d-flex flex-wrap">
                <span class="badge badge-secondary mr-2" v-for="option of order.options" :key="option.id">
                    {{ option.name }} - R$ {{ (+option.pivot.price).toFixed(2) }}
                </span>
            </div>
        </div>

        <div class="px-3 py-2">
            <span class="text-success fs-sm" v-if="order.completed_at">
                <i class="fa fa-check-double"></i>
                Concluído {{ order.completed_at | from_now }}
            </span>

            <span class="text-black-50 fs-sm" v-if="!order.completed_at">
                <i class="far fa-question-circle"></i>
                Pendente
            </span>

            <span class="text-black-50 fs-sm ml-3" v-if="showTimestamps">Criado {{ order.created_at | date('LLLL') }}</span>

            <span class="text-black-50 fs-sm ml-3">
                Preço: R$ {{ order.price.toFixed(2) }}
            </span>

            <slot name="actions"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        order: { required: true, type: Object },
        showTimestamps: { default: false },
    },
};
</script>
