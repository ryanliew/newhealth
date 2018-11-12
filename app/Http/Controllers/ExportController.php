<?php

namespace App\Http\Controllers;

use App\ExcelExports\PayoutExports;
use App\ExcelExports\PurchaseExports;
use App\ExcelExports\TransactionExports;
use App\ExcelExports\UserExports;
use App\Purchase;
use App\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

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

    public function users()
    {
        $users = Controller::VueTableListResult(User::with('contacts'));

        $title = 'users_data';

        if(request()->has(['start', 'end']))
        {
            $title .= request()->start . '_' . request()->end ;
        }

        if(request()->type == 'excel')
        {   
            $collection = collect([["Newleaf ID", 
                                    "Name", 
                                    "Passport/NRIC", 
                                    "Email Address", 
                                    "Address",
                                    "Contact No", 
                                    "Nationality",
                                    "Gender",
                                    "Bank Name",
                                    "Bank SORT/SWIFT",
                                    "Bank Address",
                                    "Bank Account Type",
                                    "Bank Account No.",
                                    "Nominee Name",
                                    "Nominee Passport/NRIC",
                                    "Nominee Address",
                                    "Nominee Contact No.",
                                    "Tree Purchases",
                                    "Payment Status",
                                    "Payment Amount",
                                    "Date of purchase",
                                    "Date of payment",
                                    "Date of verification",
                                    "ID verification status"
                                ]]);

            foreach($users as $key => $user)
            {
                $purchase = $user->purchases()->first();
                $payment = $user->payments()->first();

                $payment_amount = "N/A";

                if($payment)
                {
                    if($payment->is_std)
                        $payment_amount = $payment->amount_std;
                    else
                        $payment_amount = $payment->amount;
                }
                $collection->push([$user->referral_code,
                                    $user->name,
                                    $user->identification . "",
                                    $user->email,
                                    $user->address,
                                    $user->contact_number . "",
                                    $user->nationality,
                                    $user->gender,
                                    $user->bank_name,
                                    $user->bank_swift . "",
                                    $user->bank_address,
                                    $user->account_type,
                                    $user->account_no . "",
                                    $user->beneficiary_name,
                                    $user->beneficiary_identification,
                                    $user->beneficiary_address,
                                    $user->beneficiary_contact . "",
                                    $user->tree_count,
                                    $purchase ? $purchase->status : "N/A",
                                    floatval($payment_amount),
                                    $purchase ? $purchase->created_at->toDateString() : "N/A",
                                    $payment ?  $payment->updated_at->toDateString() : "N/A",
                                    $purchase && $purchase->is_verified ? $purchase->updated_at->toDateString() : "N/A",
                                    $user->id_status
                                    ]);
            }

            return new UserExports($collection);
        }
    }

    public function payouts()
    {
        $date = Carbon::parse(request()->month);

        if(!request()->month)
            $date = Carbon::now();

        $query = DB::table('users')
                    ->leftJoin('transactions', 'users.id', '=', 'transactions.user_id')
                    ->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', [$date->month, $date->year])
                    ->select(DB::raw('sum(transactions.amount) as amount, user_id, name, bank_name, account_no, bank_address, bank_swift, payout_status, is_std'))
                    ->groupBy('users.id', 'transactions.payout_status', 'is_std')
                    ->orderByDesc('amount');

        $payouts = Controller::VueTableListResult($query);

        $title = "payouts_report_" . strtolower($date->format("M_Y"));

        // return view('pdf.purchases', ['purchases' => $purchases]);
        if(request()->type == 'pdf')
        {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('pdf.payouts', ['payouts' => $payouts, 'date' => $date->format("M Y")]);
            return $pdf->download($title . '.pdf');
        }

        if(request()->type == 'excel')
        {   
            $collection = collect([["Name", "Bank name", "Bank SORT/SWIFT code", "Bank address", "Account No.", "Amount (RM)", "Amount (USD)"]]);

            foreach($payouts as $payout)
            {
                // $currency = $payout->is_std ? "USD" : "RM";
                // $amount =  $currency . number_format($payout->amount, 2, ".", ",");
                $amount = $payout->is_std ? "0.00" : $payout->amount;
                $amount_std =  $payout->is_std ? $payout->amount : "0.00";
                $collection->push([
                                    $payout->name,
                                    $payout->bank_name,
                                    $payout->bank_swift,
                                    $payout->bank_address,
                                    $payout->account_no,
                                    $amount,
                                    $amount_std
                                ]);
            }

            return new PayoutExports($collection, strtolower($date->format("M_Y")));
        }
    }

    public function receipt(Purchase $purchase)
    {
        
        $title = 'receipt';

        // return view('pdf.receipt', ['purchase' => $purchase, 'user' => $purchase->user]);

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.receipt', ['purchase' => $purchase, 'user' => $purchase->user]);
        return $pdf->download($title . '.pdf');
    }

    public function payouts_default()
    {
        $transactions = Transaction::latest()->get();

        $startDate = $transactions->min('date');
        $endDate = $transactions->max('date');

        $currentDate = $startDate;

        $result = collect();
        for($currentDate = $startDate->startOfMonth(); $currentDate->lte($endDate); $currentDate->addMonth())
        {
            $transactions = DB::table('users')
                                ->leftJoin('transactions', 'users.id', '=', 'transactions.user_id')
                                ->whereRaw('MONTH(date) = ? AND YEAR(date) = ?', [$currentDate->month, $currentDate->year])
                                ->select(DB::raw('sum(transactions.amount) as amount, user_id, name, bank_name, account_no, bank_address, bank_swift, payout_status, is_std'))
                                ->groupBy('users.id', 'transactions.payout_status', 'is_std')
                                ->orderByDesc('amount')
                                ->get();

            $result->push([$currentDate->format("F Y") => $transactions]);
        }

        $pdf = App::make('dompdf.wrapper');
        // dd($result);
        $pdf->loadView('pdf.grouped_payouts', ['results' => $result, 'count' => 0]);
        return $pdf->download('newleaf_payouts.pdf');
    }

    public function transactions_default()
    {
        $users = User::with('transactions')->orderBy('name', 'asc')->get();


        $pdf = App::make('dompdf.wrapper');
        // dd($result);
        $pdf->loadView('pdf.grouped_transactions', ['users' => $users, 'showHeader' => false]);
        return $pdf->download('newleaf_commissions.pdf');
    }
}
