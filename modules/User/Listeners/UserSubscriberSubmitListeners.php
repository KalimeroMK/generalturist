<?php

namespace Modules\User\Listeners;

use App\Notifications\AdminChannelServices;
use App\User;
use Modules\User\Events\UserSubscriberSubmit;

class UserSubscriberSubmitListeners
{

    public function handle(UserSubscriberSubmit $event)
    {
        $subscriber = $event->subscriber;
        $data = [
            'id' => $subscriber->id,
            'event' => 'UserSubscriberSubmit',
            'to' => 'admin',
            'name' => __('Someone'),
            'avatar' => '',
            'link' => route('user.admin.subscriber.index'),
            'type' => 'subscriber',
            'message' => __('You have just gotten a new Subscriber')
        ];

        $user = User::query()->select("users.*")->hasPermission("dashboard_access")->first();

        if ($user) {
            $user->notify(new AdminChannelServices($data));
        }
    }
}
