<html>
	<body>
		<style>

			.container {
				width: 100%;
			}

			.header {
				margin: 0 auto;
				width: 80%;
				display: block;
				text-align: center;
			}

			.header-details {
				width: 100%;
				display: block;
			}

			.contacts {
				width: 100%;
				text-align: center;
				display: block;
			}

			.contents {
				width: 100%;
				display: block;
			}

			.text-right {
				text-align: right;
			}

			.pr-5 {
				padding-right: 5px;
			}

			.pl-5 {
				padding-left: 5px;
			}

			.mt-15 {
				margin-top: 15px;
			}

			table {
				width: 100%;
				border-collapse: collapse;
			}

			th {
				border-bottom: 2px  solid black;
			}

			td {
				border-bottom: 1px solid black;
				padding: 5px 0;
				text-align: left;
			}

			tfoot td {
				border-bottom: 0px solid transparent;
			}

			tfoot td.has-border {
				border: 2px solid black;
			}

			.title {
				text-align: center;
			}


		</style>
		@yield('style')
		
		<div class="container">
			<div class="header">
				<div class="header-image">
					<img src="{{ url('/img/mail/email-logo.png') }}" width="150px">
				</div>
				<div class="header-details">
					<b> NEWLEAF PLANATION BERHAD </b><span style="font-size:12px">(1251569-U)</span><br>
	                Suite E-7-1, Block E, Plaza Mont Kiara, No 2 Jalan Kiara, 50480 Kuala Lumpur<br>
	                <div class="contacts">
	                	| <b>T</b> +603 6201 6336 | <b>F</b> +603 6201 7337 | <b>W</b> www.newleaf.com.my |
	               	</div>
				</div>
			</div>
		</div>
		<br>
		<hr>

		<div class="content">

			@yield('content')

		</div>


	</body>
</html>