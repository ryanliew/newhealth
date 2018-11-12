@extends('layouts.pdf')

@section('title')
	Commission statement
@endsection

@section('to')
	<br>
	<b>TO:</b><br>
	{{ $user->name }}<br>
	{{ $user->identification }}<br>
	{{ $user->bank_name }}<br>
	{{ $user->account_no }}<br>
	@if($user->bank_swift){{ $user->bank_swift }}<br>@endif
	{{ $user->bank_address }}
@endsection

@section('extra')
	Date: {{ $date }}
@endsection

@section('content')

	<table class="has-border">
		<thead>
			<tr>
				<th>Date</th>
				<th>Description</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			@foreach($transactions as $key => $transaction)
			<tr>
				<td cellspacing="0" cellpadding="0">{{ $transaction->date->format("j/n/Y") }}</td>
				<td cellspacing="0" cellpadding="0">{{ __('transaction.' . $transaction->description, ['name' => $transaction->target->referral_code]) }}</td>
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
					<div class="amount text-right"><b>{{ number_format($transactions->sum(function($transaction){ return $transaction->is_std ? 0 : $transaction->amount; }), 2, ".", ",") }}</b></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-right with-bg"><b class="pr-50">Total (USD): </b></td>
				<td>
					<div class="currency"><b>USD</b></div>
					<div class="amount text-right"><b>{{ number_format($transactions->sum(function($transaction){ return !$transaction->is_std ? 0 : $transaction->amount; }), 2, ".", ",") }}</b></div>
				</td>
			</tr>
		</tbody>
	</table>
@endsection