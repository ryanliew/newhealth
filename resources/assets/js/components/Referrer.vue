<template>
	<div>
	 	<input type="text" class="form-control" name="referrer_code" v-model="referrer_id" autofocus :placeholder="$options.filters.trans('auth.referrer')" @keydown="searchForReferrer"/>
	 	<small class="form-text text-muted">
			{{ 'auth.referrer_name' | trans({'name': referrer_name}) }}
	 	</small>
	 </div>
</template>

<script>
	import _ from 'lodash';
	export default {
		props: ['app_locale', 'referrer'],
		data() {
			return {
				referrer_name: '',
				referrer_id: this.referrer
			};
		},

		created() {
			lang.setLocale(this.app_locale);
			if(this.referrer) {
				this.searchForReferrer();
			}
		},

		methods: {
			searchForReferrer: _.throttle(function() {
				axios.get('/internal/referrer?code=' + this.referrer_id)
					.then(response => this.setReferrer(response));
			}, 1000),

			setReferrer(response) {
				this.referrer_name = '';
				if(response.data) {
					this.referrer_name = this.$options.filters.trans(response.data);
				}
			}
		}	
	}
</script>