@extends('layouts.pdf')

@section('title')
	RECEIPT G{{ sprintf("%05d", $purchase->id) }}
@endsection

@section('to')
	<br>
	<b>TO:</b><br>
	{{ $user->name }}<br>
	{{ $user->address }}
@endsection

@section('extra')
	Date: {{ $purchase->updated_at->format("jS M Y") }}
@endsection

@section('content')
	<table>
		<thead>
			<tr>
				<th style="padding: 10px 0; border-top: 2px solid black; border-bottom: 2px solid black">Description</th>
				<th style="padding: 10px 0; border-top: 2px solid black; border-bottom: 2px solid black">Quantity</th>
				<th style="padding: 10px 0; border-top: 2px solid black; border-bottom: 2px solid black">Amount ({{ $purchase->is_std ? "USD" : "RM" }})</th>
			</tr>
		</thead>
		<tbody>
			@foreach($purchase->packages as $package)
				<tr>
					<td style="padding-top:10px;padding-bottom: 200px;">{{ __('purchase.receipt_description', ['number' => $package->tree_count])}}</td>
					<td class="text-center" style="padding-top:10px;padding-bottom: 200px;">{{ $package->pivot->amount }}</td>
					<td class="text-center" style="padding-top:10px;padding-bottom: 200px;">{{ $package->pivot->total_price }}</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<td style="border-top:1px solid black"></td>
				<td style="border-top:1px solid black"></td>
				<td style="padding: 15px 0;border-top:1px solid black;border-bottom: 3px solid black" class="text-center">
					<b>{{ number_format($purchase->total_price, 2, ".", ",") }}</b>
				</td>
			</tr>
		</tfoot>
@endsection