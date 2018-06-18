@extends('layouts.pdf')

@section('content')
	<div class="title">
		<h2>Commission report</h2>
	</div>

	<table>
		<thead>
			<tr>
				<th>No</th>
				<th>Date</th>
				<th>Description</th>
				<th>Amount</th>
			</tr>
		</thead>
		<tbody>
			@foreach($transactions as $key => $transaction)
			<tr>
				<td cellspacing="0" cellpadding="0">{{ $key + 1 }}</td>
				<td cellspacing="0" cellpadding="0">{{ $transaction->date }}</td>
				<td cellspacing="0" cellpadding="0">{{ __('transaction.' . $transaction->description, ['name' => $transaction->target->name]) }}</td>
				<td cellspacing="0" cellpadding="0">{{ $transaction->is_std ? 'USD' : 'MYR'}} {{ $transaction->amount }}</td>
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
				<td class="has-border pl-5">{{ $transactions->sum(function($transaction){ return $transaction->is_std ? 0 : $transaction->amount; }) }}</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td class="text-right"><b class="pr-5">Total (USD): </b></td>
				<td class="has-border pl-5">{{ $transactions->sum(function($transaction){ return !$transaction->is_std ? 0 : $transaction->amount; }) }}</td>
			</tr>
		</tfoot>
	</table>
@endsection