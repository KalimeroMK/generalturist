<?php

namespace Modules\Sms\Core\Drivers;

use Modules\Sms\Core\Contracts\SMS;
use Modules\Sms\Core\Exceptions\SmsException;

abstract class Driver implements SMS
{
    /**
     * The recipient of the message.
     *
     * @var string
     */
    protected $recipient;

    /**
     * The message to send.
     *
     * @var string
     */
    protected $message;

    /**
     * {@inheritdoc}
     */
    abstract public function send();

    /**
     * Set the recipient of the message.
     *
     * @param  string  $recipient
     *
     * @return $this
     * @throws SmsException
     *
     */
    public function to(string $recipient)
    {
        throw_if(is_null($recipient), SmsException::class, 'Recipients cannot be empty');

        $this->recipient = $recipient;

        return $this;
    }

    public function content(string $message)
    {
        throw_if(empty($message), SmsException::class, 'Message text is required');

        $this->message = $message;

        return $this;
    }
}