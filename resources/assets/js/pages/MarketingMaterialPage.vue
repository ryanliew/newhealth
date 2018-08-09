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
										<td>Newleaf Flyer 新叶宣传单</td>
										<td><a class="button" target="_blank" href="/materials/Newleaf Flyer 新叶宣传单.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>Newleaf Corporate Brochure 新叶集团企业宣传册</td>
										<td><a class="button" target="_blank" href="/materials/Newleaf Corporate Brochure 新叶集团企业宣传册.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>Purchasing Procedure 购买流程</td>
										<td><a class="button" target="_blank" href="/materials/Purchasing Procedure 购买流程.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>Purchase Form 购买表格</td>
										<td><a class="button" target="_blank" href="/materials/Purchase Form 购买表格.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>Yield Projection 种植收益预计</td>
										<td><a class="button" target="_blank" href="/materials/Yield Projection 种植收益预计.jpg" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>终生免费榴莲宣传单</td>
										<td><a class="button" target="_blank" href="/materials/终生免费榴莲宣传单.jpg" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>User manual</td>
										<td><a class="button" target="_blank" href="/materials/User Manual.pdf" download>{{ 'post.download' | trans }}</a></td>
									</tr>
									<tr>
										<td>用户指南</td>
										<td><a class="button" target="_blank" href="/materials/用户指南.pdf" download>{{ 'post.download' | trans }}</a></td>
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