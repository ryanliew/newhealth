<?php

namespace App\Http\Controllers;

use App\Address;
use App\Notifications\IdentityVerificationDocumentsRejectedNotification;
use App\Notifications\KYCUpdatedNotification;
use App\Notifications\RemindUploadDocumentNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function setLanguage()
    {
        App::setLocale(request()->lang);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user->append(['address', 'company_address', 'default_locale', 'is_std', 'group_sale', 'has_verified_sale', 'commission_received', 'group_sale_needed', 'direct_descendants_count', 'direct_referrer_needed', 'descendants_count', 'commission_received_std', 'unpaid_commission', 'unpaid_commission_std', 'transaction_start', 'transaction_end']); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       $validated = request()->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'country_id' => 'required|exists:countries,id',
            'company_country_id' => 'sometimes|required|exists:countries,id',
            'address_line_1' => 'required',
            'postcode' => 'required',
            'nationality' => 'required',
            'phone' => 'required',
            'password' => 'sometimes|required|confirmed',
            'identification' => 'required',
            'company_name' => 'sometimes|required',
            'company_business_registration' => 'sometimes|required',
            'company_type' => 'sometimes|required',
            'company_incorporation_place' => 'sometimes|required',
            'company_incorporation_date' => 'sometimes|required',
            'company_address_line_1' => 'sometimes|required',
            'company_postcode' => 'sometimes|required',
            'company_phone' => 'sometimes|required',
            'company_email' => 'sometimes|required',
            'bank_name' => 'required',
            'bank_address' => 'required',
            'account_type' => 'required',
            'account_no' => 'required',
            'beneficiary_name' => 'required',
            'beneficiary_contact' => 'required',
            'beneficiary_address' => 'required|max:255',
            'beneficiary_identification' => 'required'
        ]);

        $status = $user->id_status !== "pending" ? "pending_verification" : $user->id_status;
        $user->update([
            'email' => request()->email,
            'name' => request()->name,
            'country_id' => request()->country_id,
            'id_type' => request()->id_type,
            'identification' => request()->identification,
            'nationality' => request()->nationality,
            'gender' => request()->gender,
            'company_name' => request()->company_name,
            'company_business_registration' => request()->company_business_registration,
            'company_incorporation_place' => request()->company_incorporation_place,
            'company_incorporation_date' => request()->company_incorporation_date,
            'company_regulatory_name' => request()->company_regulatory_name,
            'company_type' => request()->company_type,
            'company_email' => request()->company_email,
            'bank_name' => request()->bank_name,
            'bank_swift' => request()->bank_swift,
            'bank_address' => request()->bank_address,
            'account_type' => request()->account_type,
            'account_no' => request()->account_no,
            'beneficiary_name' => request()->beneficiary_name,
            'beneficiary_identification' => request()->beneficiary_identification,
            'beneficiary_address' => request()->beneficiary_address,
            'beneficiary_contact' => request()->beneficiary_contact,
            'id_status' => $status
        ]);

        if(request()->has('isChangePassword'))
        {
            $user->update([ 'password' => bcrypt(request()->password) ]);
        }

        $user->addresses()->updateOrCreate(['type' => Address::PERSONAL()],
            [
            'line_1' => request()->address_line_1,
            'line_2' => request()->address_line_2,
            'country_id' => request()->country_id,
            'postcode' => request()->postcode,
            'phone' => request()->phone,
            "type" => Address::PERSONAL()
            ]
        );

        if(isset($validated['company_address_line_1']))
        {
            $user->addresses()->updateOrCreate(["type" => Address::COMPANY()],
                [
                'line_1' => request()->company_address_line_1,
                'line_2' => request()->company_address_line_2,
                'country_id' => request()->company_country_id,
                'postcode' => request()->company_postcode,
                'phone' => request()->company_phone,
                "type" => Address::COMPANY()
            ]);
        }

        $user->contacts()->delete();
        if(isset(request()->personnels))
        {
            foreach(json_decode(request()->personnels) as $personnel)
            {
                $user->contacts()->create([
                    'name' => $personnel->name,
                    'designation' => $personnel->designation,
                    'email' => $personnel->email,
                    'phone' => $personnel->phone
                ]);
            }
        }

        return json_encode(['message' => 'user.profile_update_success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function showCompanyContacts(User $user)
    {
        return $user->contacts;
    }

    public function getTree(User $user)
    {   
        $users = User::descendantsAndSelf($user->id);

        return $users->toTree();
    }

    public function update_documents(User $user)
    {
        $message = [
            "*.required" => "validation.required",
            "*.file" => "validation.file",
            "*.max" => "user.file_exceed_size"
        ];

        $this->validate(request(), [
            'identity' => 'required|max:8000',
            'residence_proof' => 'required|max:8000',
            'nominee_identity' => 'required|max:8000',
            'bank_statement' => 'required|max:8000'
        ], $message);

        $identity = $user->kyc_identity; 
        $residence_proof = $user->kyc_residence_proof; 
        $nominee_identity = $user->kyc_nominee_identity; 
        $bank_statement = $user->kyc_bank_statement;

        if(request()->hasFile('identity'))
        {
            Storage::disk('public')->delete($user->kyc_identity);
            $identity = request()->file('identity')->store('documents', 'public');
        }

        if(request()->hasFile('residence_proof'))
        {
            Storage::disk('public')->delete($user->kyc_residence_proof);
            $residence_proof = request()->file('residence_proof')->store('documents', 'public');
        }

        if(request()->hasFile('nominee_identity'))
        {
            Storage::disk('public')->delete($user->kyc_nominee_identity);
            $nominee_identity = request()->file('nominee_identity')->store('documents', 'public');
        }

        if(request()->hasFile('bank_statement'))
        {
            Storage::disk('public')->delete($user->kyc_bank_statement);
            $bank_statement = request()->file('bank_statement')->store('documents', 'public');
        }

        $user->update([
            "kyc_identity" => $identity,
            "kyc_residence_proof" => $residence_proof,
            "kyc_nominee_identity" => $nominee_identity,
            "kyc_bank_statement" => $bank_statement,
            "id_status" => "pending_verification"
        ]);

        Notification::send($user, new KYCUpdatedNotification($user));
        
        return json_encode(['message' => 'user.documents_success']);
    }

    public function reject_documents(User $user)
    {
        $user->update([
            "reject_note" => request()->reject_note,
            "id_status" => "rejected"
        ]);

        $locale = $user->is_std ? 'zh' : 'en';
        $user->notify(new IdentityVerificationDocumentsRejectedNotification($user, $locale));
        return json_encode(['message' => 'user.documents_rejected']);
    }

    public function verify_documents(User $user)
    {
        $user->update([
            "reject_note" => '',
            "id_status" => "verified"
        ]);

        return json_encode(['message' => 'user.documents_verified']);
    }

    public function remind_documents(User $user)
    {
        $locale = $user->is_std ? 'zh' : 'en';
        $user->notify(new RemindUploadDocumentNotification($user, $locale));

        return json_encode(['message' => 'user.reminder_sent']);
    }

    public function update_legal(User $user)
    {

        $user->update(['id_status' => request()->status]);

        return json_encode(['message' => 'user.legal_updated']);
    }

    public function rewind_legal(User $user)
    {
        $new_status = 'verified';
        switch($user->id_status) {
            case 'complete':
                $new_status = 'execution_ready';
                break;
            case 'execution_ready': 
                $new_status = "instruction_issued";
                break;
            case 'instruction_issued':
                $new_status = 'verified';
                break;
        }

        $user->update(['id_status' => $new_status]);

        return json_encode(['message' => 'user.legal_updated']);
    }

    public function update_lock(User $user)
    {
        $user->is_locked = !$user->is_locked;

        $user->save();

        $message = $user->is_locked ? "user.locked" : "user.unlocked";

        return json_encode(['message' => $message]);
    }

    public function delete(User $user)
    {
        Log::info("Deleted user " . $user->name . " by " . auth()->user()->name);

        // Removed this as we will be using soft delete
        // foreach($user->purchases as $purchase) {
        //     $purchase->packages()->detach();
        // }

        $user->transactions()->delete();
        $user->purchases()->delete();

        foreach($user->children as $children) {
            $children->appendToNode($user->parent)->save();
        }
        $user->delete();

        return json_encode(['message' => "user.deleted"]);
    }
}
