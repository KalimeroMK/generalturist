<?php

namespace Modules\Booking\Gateways;

use Validator;

class StripeGateway extends StripeCheckoutGateway
{
    public $name = 'Stripe Checkout';
    protected $id = 'stripe';
    protected $gateway;


}
