<template>
    <div class="card mb-4 border-0 shadow-sm">

        <div class="card-body">
            <h5 class="card-title mb-3">
                Perfil
            </h5>

            <div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right"> Nome </label>
                    <div class="col-md-6">
                        <input-text class="mb-0" v-model="profileForm.name" :form="profileForm" field="name" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right"> E-mail </label>
                    <div class="col-md-6">
                        <input-text class="mb-0" v-model="profileForm.email" :form="profileForm" field="email" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-3 col-md-6">
                        <button-loading class="btn btn-primary" @click="updateProfile" :loading="profileForm.submitting">
                            <i class="far fa-save with-text mr-1"></i>
                            Salvar
                        </button-loading>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        user: { type: Object, required: true },
    },

    data() {
        return {
            profileForm: new Form(this.user),
        };
    },

    methods: {
        async updateProfile() {
            const { data: updated } = await this.profileForm.put(
                this.$route('users.profile-update', { user: this.user.id })
            );

            this.profileForm = new Form(updated);
            this.$toasted.success('Perfil atualizado com sucesso!');
        },
    },
};
</script>

