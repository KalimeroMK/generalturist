<?php

namespace Modules\User\Listeners;

use App\Notifications\AdminChannelServices;
use Modules\User\Events\SendMailUserRegistered;

class SendNotifyRegisteredListen
{

    public function handle(SendMailUserRegistered $event)
    {
        $user = $event->user;
        $data = [
            'id' => $user->id,
            'event' => 'SendMailUserRegistered',
            'to' => 'admin',
            'name' => $user->display_name,
            'avatar' => $user->avatar_url,
            'link' => route('user.admin.index', ['s' => $user->id]),
            'type' => 'user',
            'message' => $user->display_name.__(' has been registered')
        ];

        $user->notify(new AdminChannelServices($data));
    }

}
