<template>
	<div>
		<transition name="slide-fade" mode="out-in">
			<div class="row">
				<div class="col">
					<div class="card">
						<div class="card-header">
							<div class="row align-items-center">
								<div class="col">
									{{ 'post.marketing_material' | trans }}
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table">
									<tr>
										<td>{{ 'post.newleaf_flyer' | trans  }}</td>
										<td><a class="button" target="_blank" href="/materials/Newleaf Flyer.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>{{ 'post.advisor_incentive_program' | trans  }}</td>
										<td><a class="button" target="_blank" href="/materials/Advisor Incentive Program2.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>{{ 'post.newleaf_purchase_form' | trans  }}</td>
										<td><a class="button" target="_blank" href="/materials/Newleaf Purchase Form.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>{{ 'post.yield_projection' | trans  }}</td>
										<td><a class="button" target="_blank" href="/materials/YIELD PROJECTION.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</transition>
	</div>
</template>

<script>
	export default {
		props: []

	}
</script>