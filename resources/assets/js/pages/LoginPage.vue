<template>
	<div>
		<LoginComponent>
		</LoginComponent>
	</div>
</template>

<script>
	import LoginComponent from '../components/LoginComponent';
	
	export default {
		props: [''],

		components: { LoginComponent },

		data() {
			return {

			};
		}	
	}
</script>