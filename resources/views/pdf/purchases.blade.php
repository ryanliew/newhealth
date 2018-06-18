@extends('layouts.pdf')

@section('content')
	<div class="title">
		<h2>Purchases report</h2>
	</div>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Made by</th>
				<th>Purchase date</th>
				<th>Total payable</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			@foreach($purchases as $key => $purchase)
			<tr>
				<td cellspacing="0" cellpadding="0">{{ $key + 1 }}</td>
				<td cellspacing="0" cellpadding="0">{{ $purchase->user_name }}</td>
				<td cellspacing="0" cellpadding="0">{{ $purchase->created_at->toDateString() }}</td>
				<td cellspacing="0" cellpadding="0">{{ $purchase->is_std ? 'USD' : 'MYR' }} {{ $purchase->is_std ? $purchase->total_price_std : $purchase->total_price }}</td>
				<td cellspacing="0" cellpadding="0">{{ $purchase->status }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>

	<table class="mt-15">
		<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td class="text-right"><b class="pr-5">Total (MYR): </b></td>
				<td class="has-border pl-5">{{ $purchases->sum(function($purchase){ return $purchase->is_std ? 0 : $purchase->total_price; }) }}</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td class="text-right"><b class="pr-5">Total (USD): </b></td>
				<td class="has-border pl-5">{{ $purchases->sum(function($purchase){ return !$purchase->is_std ? 0 : $purchase->total_price_std; }) }}</td>
			</tr>
		</tfoot>
	</table>
@endsection