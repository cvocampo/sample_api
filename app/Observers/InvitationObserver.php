<?php

namespace App\Observers;
use App\Models\Invitation;
use App\Notifications\InvitationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class InvitationObserver
{



    public function creating(Invitation $invitation)
    {
        $invitation->id = (string) Str::uuid();
        $invitation->six_digit_code = $this->create_six_digit_code();

    }

    public function created(Invitation $invitation)
    {

        Notification::route('mail',$invitation->email)->notify(new InvitationNotification("/api/invitation/" . $invitation->email));
    }

    protected function create_six_digit_code()
    {
        $number = mt_rand(10000,999999);

        if ($this->numberExists($number)){
            return $this->create_six_digit_code();
        }

        return $number;
    }

    protected function numberExists($number)
    {
        return Invitation::where('six_digit_code',$number)->exists();
    }
}
