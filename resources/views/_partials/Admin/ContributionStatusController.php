<?php

    namespace App\Http\Controllers\Admin;

    use App\Models\Media;
    use App\Models\User;
    use App\Notifications\ContributionApproved;
    use App\Notifications\ContributionDenied;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;

    class ContributionStatusController extends Controller
    {
        public function mark_approved(Media $contribution, $status)
        {
            $custom_properties = $contribution->custom_properties;

            $user = User::find($contribution->custom_properties['user_id']);
            $custom_properties['approved'] = intval($status);

            if($status == 1)
            {
               $user->notify(new ContributionApproved($contribution));
                \Log::notice('Email sent to user : ' . $user->full_name .' Email : ' .$user->email .' Contribution '. $contribution->id . ' approved for company ID ' .$contribution->model_id );
                // Fire email notify
            }elseif($status == 0)
            {
                $user->notify(new ContributionDenied($contribution));
                \Log::notice('Email sent to user : ' . $user->full_name .' Email : ' .$user->email .' Contribution '. $contribution->id . ' denied for company ID ' .$contribution->model_id );

                //Fire email notify
            }

            $contribution->custom_properties = $custom_properties;

            $contribution->save();

            return redirect()->route('admin.contribution.index')->with(['status' => 'success', 'message' => 'Contribution updated.']);
        }
    }
