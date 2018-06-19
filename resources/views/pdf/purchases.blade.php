@extends('layouts.pdf')

@section('title')
	Purchases report
@endsection

@section('content')

	<table class="has-border">
		<thead>
			<tr>
				<th>Made by</th>
				<th>Purchase date</th>
				<th>Status</th>
				<th>Total payable</th>
			</tr>
		</thead>
		<tbody>
			@foreach($purchases as $key => $purchase)
			<tr>
				<td cellspacing="0" cellpadding="0">{{ $purchase->user_name }}</td>
				<td cellspacing="0" cellpadding="0">{{ $purchase->created_at->format("j/n/Y") }}</td>
				<td cellspacing="0" cellpadding="0">{{ title_case($purchase->status) }}</td>
				<td cellspacing="0" cellpadding="0">
					<div class="currency">{{ $purchase->is_std ? 'USD' : 'RM' }}</div>
					<div class="amount text-right">{{ number_format($purchase->is_std ? $purchase->total_price_std : $purchase->total_price, 2, ".", ",") }}</td>
			</tr>
			@endforeach
			<tr>
				<td colspan="3" class="text-right with-bg"><b class="pr-50">Total (RM): </b></td>
				<td class="has-border pl-5">
					<div class="currency"><b>RM</b></div>
					<div class="amount text-right"><b>{{ number_format($purchases->sum(function($purchase){ return $purchase->is_std ? 0 : $purchase->total_price; }), 2, ".", ",") }}</b></div>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="text-right with-bg"><b class="pr-50">Total (USD): </b></td>
				<td>
					<div class="currency"><b>USD</b></div>
					<div class="amount text-right"><b>{{  number_format($purchases->sum(function($purchase){ return !$purchase->is_std ? 0 : $purchase->total_price_std; }), 2, ".", ",") }}</b></div>
				</td>
			</tr>
		</tbody>
	</table>

	<table class="mt-15">
		<tfoot>
			
		</tfoot>
	</table>
@endsection