<template>
    <div class="card mb-4 card-default">
        <div class="card-header">
            Change Password
        </div>

        <div class="card-body">
            <div role="form">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right"> Current Password </label>
                    <div class="col-md-6">
                        <input-text class="mb-0" v-model="passwordForm.current_password" :form="passwordForm" field="current_password" type="password" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right"> New Password </label>
                    <div class="col-md-6">
                        <input-text class="mb-0" v-model="passwordForm.password" :form="passwordForm" field="password" type="password" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right"> Confirm Password </label>
                    <div class="col-md-6">
                        <input-text class="mb-0" v-model="passwordForm.password_confirmation" :form="passwordForm" field="password_confirmation" type="password" />
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-3 col-md-6">
                        <button-loading class="btn btn-primary" @click="updatePassword" :loading="passwordForm.submitting">
                            <i class="far fa-save with-text mr-1"></i>
                            Update Password
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
            passwordForm: new Form(),
        };
    },

    methods: {
        async updatePassword() {
            const { data: updated } = await this.passwordForm.put(
                this.$route('user.password.update', { user: this.user.id })
            );

            this.passwordForm = new Form();
            this.$toasted.success('Password successfully updated!');
        },
    },
};
</script>

