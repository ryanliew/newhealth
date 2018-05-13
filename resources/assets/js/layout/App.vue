<template>
	<div>
		<header class="site-header">
		    <div class="container-fluid">
		        <a href="#" class="site-logo">
		            <img class="hidden-md-down" src="/img/favicon.png" alt="">
		            <img class="hidden-lg-down" src="img/logo-2-mob.png" alt="">
		        </a>
		
		        <button id="show-hide-sidebar-toggle" class="show-hide-sidebar">
		            <span>toggle menu</span>
		        </button>
		
		        <button class="hamburger hamburger--htla">
		            <span>toggle menu</span>
		        </button>
		        <div class="site-header-content">
		            <div class="site-header-content-in">
		                <div class="site-header-shown">
		                    
		                    <language-switcher></language-switcher>
		
		                    <div class="dropdown user-menu">
		                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		                            <img src="img/avatar-2-64.png" alt="">
		                        </button>
		                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
		                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-user"></span>{{ 'nav.profile' | trans }}</a>
		                            <!-- <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-cog"></span>{{ 'nav.settings' | trans }}</a> -->
		                            <div class="dropdown-divider"></div>
		                            <a @click="logout" class="dropdown-item"><span class="font-icon glyphicon glyphicon-log-out"></span>{{ 'nav.logout' | trans }}</a>
		                        </div>
		                    </div>
		
		                    <button type="button" class="burger-right">
		                        <i class="font-icon-menu-addl"></i>
		                    </button>
		                </div><!--.site-header-shown-->
		
		                <div class="mobile-menu-right-overlay"></div>
		            </div><!--site-header-content-in-->
		        </div><!--.site-header-content-->
		    </div><!--.container-fluid-->
		</header><!--.site-header-->
		
		<side-nav></side-nav>
		

		<div class="page-content" id="body-content">
			<div class="container-fluid">
				<router-view></router-view>
			</div><!--.container-fluid-->
		</div><!--.page-content-->

		<flash></flash>
	</div>	
</template>

<script>
	import LanguageSwitcher from "../components/LanguageSwitcher.vue";
	export default {
		props: [''],
		data() {
			return {

			};
		},

		components: { LanguageSwitcher },

		methods: {
			logout() {
				axios.post('/logout')
					.then(response => this.onLogoutSuccess(response))
					.catch(response => this.onLogoutError(response));
			},

			onLogoutSuccess(response) {
				user = '';
				window.location.href = "/register";
			},

			onLogoutError(response) {
				user = '';
				window.location.href = "/register";
			}
		}	
	}
</script>