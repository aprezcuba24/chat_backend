<?php

namespace App\Message;

final class MessageNotification
{
    private $messageId;

    public function __construct($messageId)
    {
        $this->messageId = $messageId;
    }

    public function getMessageId()
    {
        return $this->messageId;
    }
}
