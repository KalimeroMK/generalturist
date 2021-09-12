<?php

    namespace Modules\User\Listeners;

    use App\Notifications\AdminChannelServices;
    use Modules\User\Events\UserVerificationSubmit;

    class SendNotifyVerificationData
    {

        /**
         * Handle the event.
         *
         * @param $event UserVerificationSubmit
         * @return void
         */
        public function handle(UserVerificationSubmit $event)
        {
            $user = $event->user;
            $data = [
                'id'      => $user->id,
                'event'   => 'UserVerificationSubmit',
                'to'      => 'admin',
                'name'    => $user->display_name,
                'avatar'  => $user->avatar_url,
                'link'    => route('user.admin.verification.index'),
                'type'    => 'user_verification_request',
                'message' => __(':name has asked for verification', ['name' => $user->display_name]),
            ];

            $user->notify(new AdminChannelServices($data));
        }

    }
