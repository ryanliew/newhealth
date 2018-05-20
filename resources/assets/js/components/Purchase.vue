<template>
	<div>
		<div class="card">
			<div class="card-header">
				{{ 'make new purchase' | trans }}
			</div>
			<div class="card-body">
				<h5 class="card-title mb-3">Select from the packages below:</h5>
				<table class="table">
					<tbody>
						<tr v-for="(package, index) in packages">
							<td>{{ package.tree_count }} {{ 'auth.tree' | trans_choice({'value' : package.tree_count})  }}</td>
							<td>x</td>
							<td><text-input v-model="form.packages[index].amount" 
									:defaultValue="form.packages[index].amount"
									:required="false"
									type="number"
									label="label"
									name="amount"
									:editable="true"
									:focus="true"
									:hideLabel="true">
								</text-input>
							</td>
							<td>
								x
							</td>
							<td>
								{{ package.price }}	
							</td>
							<td>
								=
							</td>
							<td>
								{{ getPackagePrice(form.packages[index].amount, package.price) }}
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><b>TOTAL</b></td>
							<td>=</td>
							<td>{{ totalPrice }}</td>
						</tr>
					</tbody>
				</table>
				<div class="row mr-5">
					<div class="col-sm"></div>
					<div class="col-sm-auto">
						<button class="btn btn-primary btn-lg" @click="submitForm">Checkout</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: [],
		data() {
			return {
				packages: [],
				form: new Form({
					packages: [],
					user_id: window.user.id	
				})
			};
		},

		mounted() {
			this.getPackages();
		},

		methods: {
			getPackages() {
				axios.get('/api/packages')
					.then(response => this.setPackages(response.data));
			},

			setPackages(data) {
				this.form.packages = data.map(pack => {
					let obj = {};
					obj['amount'] = 0;
					obj['id'] = pack.id;
					obj['price'] = pack.price;

					return obj;
				});

				this.packages = data;
			},

			submitForm() {
				this.form.post('/api/purchases');
			},

			getPackagePrice(amount = 0, price = 0) {
				return amount * price;
			}
		},

		computed: {
			totalPrice() {
				return _.sumBy(this.form.packages, function(pack){
					return this.getPackagePrice(pack.amount, pack.price);
				}.bind(this))
			}
		}	
	}
</script>