<?php

namespace App\Http\Controllers\Admin;

use App\Models\Claim;
use App\Notifications\ClaimApproved;
use App\Notifications\ClaimDenied;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClaimStatusController extends Controller
{
    public function mark_status(Claim $claim, $status, $notification = false)
    {

        $company = $claim->company;

        if($claim->status == 0 && $claim->approved == 1)
        {
            $company->users()->detach($claim->user->id);
        }elseif($status == 0 && $claim->approved == 0)
        {
            if($notification)
            {
                $claim->user->notify(new ClaimDenied($claim));
                \Log::notice('Email sent to user : ' . $claim->user->full_name .' Email : ' .$claim->user->email .' Claim '. $claim->id . ' denied for company ID ' .$claim->company->id );
    
            }
           
        }

        $claim->status = $status;
        $claim->approved = 0;
        $claim->save();

        return redirect()->route('admin.claims.index')->with(['status' => 'success', 'message' => 'Claim '.$claim->id .' updated.']);
    }


    public function mark_approved(Claim $claim, $status, $notification = false)
    {

        $company = $claim->company;
        if($status == 1)
        {
            if($notification)
            {
                // Fire confirmation email that it was accepted
                $claim->user->notify(new ClaimApproved($claim));
                \Log::notice('Email sent to user : ' . $claim->user->full_name .' Email : ' .$claim->user->email .' Claim '. $claim->id . ' approved for company ID ' .$claim->company->id );
            }
            $company->users()->attach($claim->user->id, ['master' => 1]);
         

        } elseif($status == 0)
        {
            if($notification)
            {
                $claim->user->notify(new ClaimDenied($claim));
                \Log::notice('Email sent to user : ' . $claim->user->full_name .' Email : ' .$claim->user->email .' Claim '. $claim->id . ' denied for company ID ' .$claim->company->id );
            }
            $company->users()->detach($claim->user->id);
        }
        
        $claim->approved = $status;
        $claim->status = 0;
        $claim->save();
        $company->save();
        
        return redirect()->route('admin.claims.index')->with(['status' => 'success', 'message' => 'Claim '.$claim->id .' updated.']);
    }
}
