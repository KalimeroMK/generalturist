<?php

namespace Modules\User\Listeners;

use App\Notifications\AdminChannelServices;
use App\Notifications\PrivateChannelServices;
use Modules\User\Events\UpdatePlanRequest;

class SendNotifyUpdatePlanRequest
{
    public function handle(UpdatePlanRequest $event)
    {
        $user = $event->user;
        $data = [
            'id' => $user->id,
            'event' => 'UpdatePlanRequest',
            'to' => 'customer',
            'name' => $user->display_name,
            'avatar' => $user->avatar_url,
            'link' => route('user.plan'),
            'type' => 'plan',
            'message' => __('Your plan request has been approved')
        ];

        $user->notify(new PrivateChannelServices($data));

        $data = [
            'id' => $user->id,
            'event' => 'UpdatePlanRequest',
            'name' => $user->display_name,
            'avatar' => $user->avatar_url,
            'to' => 'admin',
            'link' => route('user.admin.plan.index', ['s' => $user->id]),
            'type' => 'plan',
            'message' => $user->display_name.__(' plan request has been approved')
        ];
        $user->notify(new AdminChannelServices($data));
    }
}
