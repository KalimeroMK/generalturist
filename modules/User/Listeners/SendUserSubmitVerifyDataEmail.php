<?php

namespace Modules\User\Listeners;

use Illuminate\Support\Facades\Mail;
use Modules\User\Emails\UserVerificationSubmitToAdmin;
use Modules\User\Events\UserVerificationSubmit;

class SendUserSubmitVerifyDataEmail
{

    /**
     * Handle the event.
     *
     * @param $event UserVerificationSubmit
     * @return void
     */
    public function handle(UserVerificationSubmit $event)
    {
        if (!empty(setting_item('admin_email'))) {
            Mail::to(setting_item('admin_email'))->send(new UserVerificationSubmitToAdmin($event->user));
        }
    }

}
