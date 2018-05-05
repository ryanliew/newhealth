<template>
	<div>
		<header class="site-header">
		    <div class="container-fluid">
		        <a href="#" class="site-logo">
		            <img class="hidden-md-down" src="img/logo-2.png" alt="">
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
		                    
		                    <div class="dropdown dropdown-lang">
		                        <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		                            <span class="flag-icon flag-icon-us"></span>
		                        </button>
		                        <div class="dropdown-menu dropdown-menu-right">
		                            <div class="dropdown-menu-col">
		                                <a class="dropdown-item current" href="#"><span class="flag-icon flag-icon-us"></span>English</a>
		                                <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-cn"></span>中文</a>
		                            </div>
		                        </div>
		                    </div>
		
		                    <div class="dropdown user-menu">
		                        <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		                            <img src="img/avatar-2-64.png" alt="">
		                        </button>
		                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
		                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-user"></span>Profile</a>
		                            <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-cog"></span>Settings</a>
		                            <div class="dropdown-divider"></div>
		                            <a @click="logout" class="dropdown-item"><span class="font-icon glyphicon glyphicon-log-out"></span>Logout</a>
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

		<div class="mobile-menu-left-overlay"></div>
		<nav class="side-menu side-menu-compact">
		    <ul class="side-menu-list">
		        <li class="brown opened">
		            <a href="#">
		                <i class="font-icon font-icon-home"></i>
		                <span class="lbl">Overview</span>
		            </a>
		        </li>
		    </ul>
		</nav><!--.side-menu-->

		<div class="page-content" id="body-content">
			<div class="container-fluid">
				<router-view></router-view>
			</div><!--.container-fluid-->
		</div><!--.page-content-->
	</div>	
</template>

<script>
	export default {
		props: [''],
		data() {
			return {

			};
		},

		methods: {
			logout() {
				axios.post('/logout')
					.then(response => this.onLogoutSuccess(response))
					.catch(response => this.onLogoutError(response));
			},

			onLogoutSuccess(response) {
				user = '';
				this.$router.push('/login');
			},

			onLogoutError(response) {
				user = '';
				this.$router.push('/login');
			}
		}	
	}
</script>