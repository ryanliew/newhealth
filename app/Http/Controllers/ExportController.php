<?php

namespace App\Http\Controllers;

use App\ExcelExports\PurchaseExports;
use App\ExcelExports\TransactionExports;
use App\Purchase;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ExportController extends Controller
{
    public function transactions()
    {
    	$user = User::find(request()->user);

    	$transactions = Controller::VueTableListResult($user->transactions(), false);

        $title = 'commissions_report';
        $date = $transactions->min('date')->format("jS M Y") . " - " . $transactions->max('date')->format("jS M Y");

    	if(request()->has(['start', 'end']))
    	{
    		$title .= "_" . request()->start . '_' . request()->end ;
            $date = Carbon::parse(request()->start)->format("jS M Y") . " - " . Carbon::parse(request()->end)->format("jS M Y");
    	}

        // return view('pdf.transactions', ['transactions' => $transactions, 'date' => $date, 'user' => $user]);
        if(request()->type == 'pdf')
        {

        	$pdf = App::make('dompdf.wrapper');
        	$pdf->loadView('pdf.transactions', ['transactions' => $transactions, 'date' => $date, 'user' => $user]);
        	return $pdf->download($title . '.pdf');
        }

        if(request()->type == 'excel')
        {	
        	$collection = collect([["No", "Date", "Description", "Amount (USD)", "Amount (MYR)"]]);

        	foreach($transactions as $key => $transaction)
        	{
        		$description = __('transaction.' . $transaction->description, ['name' => $transaction->target->name]);

        		$collection->push(["No" => $key + 1,
        							"Date" => $transaction->date,
        							"Description" => $description,
        							"Amount (USD)" => $transaction->is_std ? $transaction->amount : 0,
        							"Amount (MYR)" => $transaction->is_std ? 0 : $transaction->amount
        							]);
        	}

        	return new TransactionExports($collection);
        }
    }

    public function purchases()
    {
        $user = User::find(request()->user);

        if($user->is_admin)
        {
            $purchases = Controller::VueTableListResult(Purchase::with('packages', 'payment')
                                                    ->select('purchases.id as id',
                                                            'payment_id',
                                                            'purchases.created_at as created_at',
                                                            'purchases.total_price as total_price',
                                                            'purchases.status as status',
                                                            'purchases.is_std as is_std',
                                                            'purchases.total_price_std as total_price_std',
                                                            'user_id',
                                                            'users.name as user_name')
                                                    ->leftJoin('users', 'users.id', '=', 'user_id'),
                                                    false
                                                );
        }
        else
        {
            $purchases = Controller::VueTableListResult($user->purchases()->with('packages', 'payment')
                                                        ->select('purchases.id as id',
                                                                'payment_id',
                                                                'purchases.created_at as created_at',
                                                                'purchases.total_price as total_price',
                                                                'purchases.total_price_std as total_price_std',
                                                                'purchases.is_std as is_std',
                                                                'purchases.status as status',
                                                                'user_id',
                                                                'users.name as user_name')
                                                        ->leftJoin('users', 'users.id', '=', 'user_id'),
                                                        false
                                                    );
        }

        $title = 'purchases_report';

        if(request()->has(['start', 'end']))
        {
            $title .= request()->start . '_' . request()->end ;
        }

        // return view('pdf.purchases', ['purchases' => $purchases]);
        if(request()->type == 'pdf')
        {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('pdf.purchases', ['purchases' => $purchases]);
            return $pdf->download($title . '.pdf');
        }

        if(request()->type == 'excel')
        {   
            $collection = collect([["No", "Made by", "Purchase date", "Total payable (USD)", "Total payable (MYR)", "Status"]]);

            foreach($purchases as $key => $purchase)
            {
                $collection->push([$key + 1,
                                    $purchase->user_name,
                                    $purchase->created_at->toDateString(),
                                    $purchase->is_std ? $purchase->total_price_std : 0,
                                    $purchase->is_std ? 0 : $purchase->total_price,
                                    title_case($purchase->status)
                                    ]);
            }

            return new PurchaseExports($collection);
        }
    }
}
