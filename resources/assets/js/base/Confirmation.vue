<template>
    <div class="modal confirmation" id="confirmation" tabindex="-1" role="dialog">
        <div class="modal-background" @click="cancel">

        </div>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationLabel">{{ 'confirmation.confirmation' | trans }}</h5>
                    <button type="button" class="close" aria-label="Close" @click="cancel">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ message | trans }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cancel">{{ 'confirmation.cancel' | trans }}</button>
                    <button type="button" class="btn btn-primary" v-html="buttonContent" @click="confirm"></button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message', 'loading'],

        data() {
            return {
                show: false
            };
        },

        methods: {
            confirm() {
                this.$emit('confirmed');
            },

            cancel() {
                this.$emit('canceled');
            }
        },

        computed: {
            buttonContent() {
                return this.loading ? "<i class='fa fa-circle-o-notch fa-spin'></i>" : this.$options.filters.trans('confirmation.confirm');
            },
        }   
    }
</script>