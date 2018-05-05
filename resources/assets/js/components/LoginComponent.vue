<template>
	<div class="container">
	    <div class="row justify-content-center">
	        <div class="col-md-8">
	            <div class="card">
	                <div class="card-header">{{ 'auth.login' | trans }}</div>

	                <div class="card-body">
	                    <form @submit.prevent="submit" 
							@keydown="form.errors.clear($event.target.name)" 
							@input="form.errors.clear($event.target.name)"
							@keyup.enter="submit">

	                        <div class="form-group row">
	                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ 'auth.email' | trans }}</label>

	                            <div class="col-md-6">
	                                <input id="email" type="email" class="form-control" v-model="form.email" name="email" required autofocus>
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ 'auth.password' | trans }}</label>

	                            <div class="col-md-6">
	                                <input id="password" type="password" class="form-control" v-model="form.password" name="password" required>
	                            </div>
	                        </div>

	                        <div class="form-group row">
	                            <div class="col-md-6 offset-md-4">
	                                <div class="checkbox">
	                                    <label>
	                                        <input type="checkbox" v-model="form.remember" name="remember"> {{ 'auth.remember' | trans }}
	                                    </label>
	                                </div>
	                            </div>
	                        </div>

	                        <div class="form-group row mb-0">
	                            <div class="col-md-8 offset-md-4">
	                                <button type="submit" class="btn btn-primary">
	                                    {{ 'auth.login' | trans }}
	                                </button>

	                                <a class="btn btn-link" href="/password/reset">
	                                    {{ 'auth.forgot-password' | trans }}
	                                </a>
	                            </div>
	                        </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>

<script>
	export default {
		props: [''],
		data() {
			return {
				form: new Form({
					email: '',
					password: '',
					remember: ''
				}),
				action: '/login'	
			};
		},

		methods: {
			submit() {
				this.form.post(this.action)
					.then(response => this.onLoginSuccess(response))
					.catch(response => this.onLoginError(response));
			},

			onLoginSuccess(response) {
				this.$router.push(intended);
			},

			onLoginError(response) {
				this.$router.push(intended);
			}
		}	
	}
</script>