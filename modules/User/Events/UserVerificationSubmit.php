<?php

namespace Modules\User\Events;

use Illuminate\Queue\SerializesModels;

class  UserVerificationSubmit
{
    use SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }
}
