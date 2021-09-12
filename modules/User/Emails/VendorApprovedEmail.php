<?php

    namespace Modules\User\Emails;

    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class VendorApprovedEmail extends Mailable
    {
        use Queueable, SerializesModels;

        const CODE = [
            'buttonReset' => '[button_reset_password]',
        ];
        public $token;
        public $user;

        public function __construct($user)
        {
            $this->user = $user;
        }

        public function build()
        {
            $subject = __('Vendor Registration Approved');

            return $this->subject($subject)->view('User::emails.vendor-approved', ['user' => $this->user]);
        }


    }
