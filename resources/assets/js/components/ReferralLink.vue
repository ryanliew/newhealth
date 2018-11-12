<template>
	<div class="mb-3">
		<div class="input-group">
			<div class="input-group-prepend">
				<button type="button" class="btn btn-sm btn-info" @click="copyLink">
					{{ 'user.copy-referral' | trans }}
				</button>
			</div>
			<input class="form-control" type="text" :id="'copy-' + code" :value="url">
		</div>
	</div>
</template>

<script>
	export default {
		props: ['code'],
		data() {
			return {
		
			};
		},

		methods: {
			copyLink() {
				console.log("code: " + this.code);
				let input = document.getElementById("copy-"+this.code);
				input.select();
				document.execCommand("Copy");

				flash(this.$options.filters.trans("user.copied-link"));
			}
		},

		computed: {
			url() {
				return window.location.protocol + "//" + window.location.hostname + '/register?referrer=' + this.code;
			}
		}	
	}
</script>