<template>
	<div class="dropdown dropdown-lang">
        <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="flag-icon" :class="current.flag"></span>
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <a v-for="language in languages" class="dropdown-item" :class="language.current" @click="setLocale(language)"><span class="flag-icon" :class="language.flag"></span>{{ language.name }}</a>
        </div>
    </div>
</template>

<script>
	import translations from '../vue-translations.js';
	export default {
		props: [''],
		data() {
			return {
				languages: [
					{flag: 'flag-icon-us', name: 'English', code: 'en', current: ''},
					{flag: 'flag-icon-cn', name: '中文', code: 'zh', current: ''},
				],

				current: {flag: 'flag-icon-us', name: 'English', code: 'en', current: 'current'}
			};
		},

		mounted() {
			window.events.$on("defaultzh", this.setZhAsDefault());
		},

		methods: {
			setLocale(language) {
				lang.setLocale(language.code);
				this.current = language;

				window.events.$emit("lang");
			},

			setZhAsDefault(){
				this.current = {flag: 'flag-icon-cn', name: '中文', code: 'zh', current: 'current'}
			}
		}	
	}
</script>