<template>
	<div>
	 	<input type="text" class="form-control" name="referrer_code" v-model="referrer_id" autofocus :placeholder="$options.filters.trans('auth.referrer')" @change="searchForReferrer"/>
	 	<small class="form-text text-muted">
			{{ 'auth.referrer-name' | trans({'name': referrer_name}) }}
	 	</small>
	 </div>
</template>

<script>
	export default {
		props: [''],
		data() {
			return {
				referrer_name: this.$options.filters.trans('auth.referrer-fill'),
				referrer_id: ''
			};
		},

		methods: {
			searchForReferrer() {
				axios.get('/internal/referrer?code=' + this.referrer_id)
					.then(response => this.setReferrer(response));
			},

			setReferrer(response) {
				this.referrer_name = '';
				if(response.data) {
					this.referrer_name = response.data;
				}
			}
		}	
	}
</script>