<?php

    namespace Modules\User\Listeners;

    use Illuminate\Support\Facades\Mail;
    use Modules\User\Emails\VendorApprovedEmail;
    use Modules\User\Events\VendorApproved;
    use Modules\User\Models\User;
    use Modules\Vendor\Models\VendorRequest;

    class SendVendorApprovedMail
    {
        const CODE = [
            'first_name' => '[first_name]',
            'last_name'  => '[last_name]',
            'name'       => '[name]',
            'email'      => '[email]',
        ];
        /**
         * Create the event listener.
         *
         * @return void
         */
        public $user;
        public $vendorRequest;

        public function __construct(User $user, VendorRequest $vendorRequest)
        {
            $this->user = $user;
            $this->vendorRequest = $vendorRequest;
            //
        }

        /**
         * Handle the event.
         *
         * @param  Event  $event
         * @return void
         */
        public function handle(VendorApproved $event)
        {
            if ($event->user->locale) {
                $old = app()->getLocale();
                app()->setLocale($event->user->locale);
            }

            Mail::to($event->user->email)->send(new VendorApprovedEmail($event->user));

            if (!empty($old)) {
                app()->setLocale($old);
            }
        }

    }