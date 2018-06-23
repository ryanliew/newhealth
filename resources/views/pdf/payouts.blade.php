@extends('layouts.pdf')

@section('title')
	Payouts statement
@endsection

@section('extra')
	Date: {{ $date }}
@endsection

@section('content')
	<h3>Payouts for RM</h3>
	<table class="has-border">
		<thead>
			<tr>
				<th>Name</th>
				<th>Bank name</th>
				<th>Bank SORT/SWIFT Code</th>
				<th>Bank address</th>
				<th>Account No.</th>
				<th>Amount (RM)</th>
			</tr>
		</thead>
		<tbody>
			@foreach($payouts as $key => $payout)
				@if(!$payout->is_std)
					<tr>
						<td cellspacing="0" cellpadding="0">{{ $payout->name }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->bank_name }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->bank_swift }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->bank_address }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->account_no }}</td>
						<td cellspacing="0" cellpadding="0">
							<div class="amount text-right">RM{{ number_format($payout->amount, 2, ".", ",") }}</div>
						</td>
					</tr>
				@endif
			@endforeach
			<tr>
				<td colspan="5" class="text-right with-bg"><b class="pr-50">Total (RM): </b></td>
				<td>
					<div class="currency"><b>RM</b></div>
					<div class="amount text-right"><b>{{ number_format($payouts->sum(function($payout){ return $payout->is_std ? 0 : $payout->amount; }), 2, ".", ",") }}</b></div>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="page-break"></div>
	<h3>Payouts for USD</h3>
	<table class="has-border">
		<thead>
			<tr>
				<th>Name</th>
				<th>Bank name</th>
				<th>Bank SORT/SWIFT Code</th>
				<th>Bank address</th>
				<th>Account No.</th>
				<th>Amount (RM)</th>
			</tr>
		</thead>
		<tbody>
			@foreach($payouts as $key => $payout)
				@if($payout->is_std)
					<tr>
						<td cellspacing="0" cellpadding="0">{{ $payout->name }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->bank_name }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->bank_swift }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->bank_address }}</td>
						<td cellspacing="0" cellpadding="0">{{ $payout->account_no }}</td>
						<td cellspacing="0" cellpadding="0"><div class="amount text-right">USD{{ number_format($payout->amount, 2, ".", ",") }}</div></td>
					</tr>
				@endif
			@endforeach
			<tr>
				<td colspan="5" class="text-right with-bg"><b class="pr-50">Total (USD): </b></td>
				<td>
					<div class="currency"><b>USD</b></div>
					<div class="amount text-right"><b>{{ number_format($payouts->sum(function($payout){ return !$payout->is_std ? 0 : $payout->amount; }), 2, ".", ",") }}</b></div>
				</td>
			</tr>
		</tbody>
	</table>
@endsection
