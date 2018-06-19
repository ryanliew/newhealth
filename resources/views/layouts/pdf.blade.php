<html>
	<body>
		<style>
			body {
				font-family: "Verdana";
			}

			* {
				box-sizing: border-box;
				-moz-box-sizing: border-box;
				-webkit-box-sizing: border-box;
			}

			
			.container {
				width: 100%;
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

			.row {
				margin-left: -15px;
				margin-right: -15px;
			}

			.row::before {
				display: table;
				content: " ";
			}

			.header-title {
				text-transform: uppercase;
				font-weight: bold;
				font-size: 25px;
				width: 50%;
				float: left;
				text-align: left;
				position: relative;
				padding: 0 15px;
			}

			.header-image {
				width: 50%;
				float: left;
				position: relative;
				padding: 0 15px;
				margin-bottom: 40px;
			}

			.company-details, .extra-details {
				float: left;
				width: 50%;
				padding: 0 15px;
			}

			.currency {
				float: left;
				padding-left: 10px;
			}

			.amount {
				float: right;
				padding-right: 10px;
			}

			.clearfix {
				clear: both;
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

			.pr-15 {
				padding-right: 15px;
			}

			.pr-50 {
				padding-right: 50px;
			}

			.mt-15 {
				margin-top: 15px;
			}

			table.phone td {
				border-bottom: none;
			}

			table {
				width: 100%;
				border-collapse: collapse;
			}

			table.phone {
				width: 200px;
			}

			table.has-border {
				border: 2px solid black;
			}

			th {
				text-align: center;
			}

			th, .with-bg {
				background-color: #b4c5e7;
			}

			table.has-border td, table.has-border th {
				border-bottom: 1px solid black;
				border-right: 1px solid black;
				padding: 5px;
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
				<div class="row">
					<div class="header-title">
						@yield('title')
					</div>
					<div class="header-image">
						<img src="{{ url('/img/mail/email-logo.png') }}" width="200px" />
					</div>
				</div>
				<div class="clearfix" style="height: 15px;"></div>
				<div class="row">
					<div class="company-details">
						Newleaf Plantation Berhad<br>
		                Suite E-7-1, Block E, Plaza Mont Kiara<br>
		                No 2 Jalan Kiara<br>
		                50480 Kuala Lumpur, Malaysia<br>
		                <br>
		                <table class="phone">
		                	<tbody>
		                		<tr>
		                			<td>
		                				Phone:
		                			</td>
		                			<td>
		                				+603-62016336
		                			</td>
		                		</tr>
		                		<tr>
		                			<td>
		                				Fax:
		                			</td>
		                			<td>
		                				+603-62017337
		                			</td>
		                		</tr>
		                	</tbody>
		                </table>
		                @yield('to')
					</div>
					<div class="extra-details">
						@yield('extra')
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<br>

		<div class="content">

			@yield('content')

		</div>


	</body>
</html>