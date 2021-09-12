<?php

    namespace Modules\User\Listeners;

    use Illuminate\Support\Facades\Mail;
    use Modules\User\Emails\AdminUpdateVerifyDataToUser;
    use Modules\User\Events\AdminUpdateVerificationData;

    class SendAdminUpdateVerifyDataEmail
    {

        public function __construct()
        {
        }

        /**
         * Handle the event.
         *
         * @param $event AdminUpdateVerificationData
         * @return void
         */
        public function handle(AdminUpdateVerificationData $event)
        {
            Mail::to($event->user->email)->send(new AdminUpdateVerifyDataToUser($event->user, $event->is_update_full));
        }

    }
