<?php

namespace Modules\Vendor\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PayoutRequestDeletedEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;
    public $payout_request;
    public $email_to;

    public function __construct($user, $payout_request, $to = 'vendor')
    {
        $this->user = $user;
        $this->payout_request = $payout_request;
        $this->email_to = $to;
    }

    public function build()
    {
        $subject = '';
        switch ($this->email_to) {
            case "admin":
                $subject = __('A payout request has been deleted');
                break;
            case "vendor":
                $subject = __('Your payout request has been deleted');
                break;
        }

        return $this->subject($subject)->view('Vendor::emails.payout-request-email', [
            'user' => $this->user, 'payout_request' => $this->payout_request, 'to' => $this->email_to,
            'action' => 'delete'
        ]);
    }


}
