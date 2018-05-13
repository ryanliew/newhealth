export const lang_switch = {
	created() {
		window.events.$on('lang', function(){
			this.$forceUpdate();
		}.bind(this));
	}
}