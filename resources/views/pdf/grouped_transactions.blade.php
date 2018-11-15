@extends('layouts.pdf')

@section('title')
	Commission statement
@endsection

@section('to')
	
@endsection

@section('extra')
	
@endsection

@section('content')
	@foreach($users as $user)
		@if($user->transactions->count() > 0)
			<div class="container">
				<div class="header">
					<div class="row">
						<div class="header-title">
							Commission statement
						</div>
						<div class="header-image">
							<img src="{{ url('/img/mail/email-logo.png') }}" width="200px" />
						</div>
					</div>
					<div class="clearfix" style="height: 15px;"></div>
					<div class="row">
						<div class="company-details">
							Newleaf Plantation Berhad <span style="font-size:10px">(1251569-U)</span><br>
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
			                <br>
							<b>TO:</b><br>
							{{ $user->name }}<br>
							{{ $user->identification }}<br>
							{{ $user->bank_name }}<br>
							{{ $user->account_no }}<br>
							@if($user->bank_swift){{ $user->bank_swift }}<br>@endif
							{{ $user->bank_address }}
							<br>
						</div>
						<div class="extra-details">
							Date: {{ $user->transactions->min('date')->format('jS M Y') }} - {{ $user->transactions->max('date')->format('jS M Y') }}
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
			<br>
			<table class="has-border">
				<thead>
					<tr>
						<th>Date</th>
						<th>Description</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					@foreach($user->transactions as $key => $transaction)
					<tr>
						<td cellspacing="0" cellpadding="0">{{ $transaction->date->format("j/n/Y") }}</td>
						<td cellspacing="0" cellpadding="0">@if(!is_null($transaction->target)) {{ __('transaction.' . $transaction->description, ['name' => $transaction->target->referral_code]) }} @endif</td>
						<td cellspacing="0" cellpadding="0">
							<div class="currency">{{ $transaction->is_std ? 'USD' : 'RM'}}</div>
							<div class="amount text-right">{{ number_format($transaction->amount, 2, ".", ",") }}</div>
						</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="2" class="text-right with-bg"><b class="pr-50">Total (RM): </b></td>
						<td>
							<div class="currency"><b>RM</b></div>
							<div class="amount text-right"><b>{{ number_format($user->transactions->sum(function($transaction){ return $transaction->is_std ? 0 : $transaction->amount; }), 2, ".", ",") }}</b></div>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="text-right with-bg"><b class="pr-50">Total (USD): </b></td>
						<td>
							<div class="currency"><b>USD</b></div>
							<div class="amount text-right"><b>{{ number_format($user->transactions->sum(function($transaction){ return !$transaction->is_std ? 0 : $transaction->amount; }), 2, ".", ",") }}</b></div>
						</td>
					</tr>
				</tbody>
			</table>
		@endif
		@if($user->transactions->count() > 0 && $user !== $users->last())
			<div class="page-break"></div>
		@endif
	@endforeach
@endsection