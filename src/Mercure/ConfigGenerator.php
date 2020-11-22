<?php

namespace App\Mercure;

use App\Entity\Chat\Message;
use App\Entity\Chat\Channel;

class ConfigGenerator
{
    public function __invoke($object)
    {
        if ($object instanceof Message) {
            return $this->getForMessage($object);
        }
    }

    public function getForMessage(Message $object)
    {
        return [
            'private' => true,
            'topics' => $this->getTopicMessages($object->getChannel()),
        ];
    }

    public function getTopicMessages(Channel $channel)
    {
        return \sprintf(
            '/api/channels/%s/messages',
            $channel->getId(),
        );
    }
}