<?php

namespace App\MessageHandler;

use App\Message\MessageNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Chat\Message;
use Symfony\Component\Serializer\SerializerInterface;

final class MessageNotificationHandler implements MessageHandlerInterface
{
    private $em;
    private $client;
    private $serializer;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->client = $client;
        $this->serializer = $serializer;
    }

    public function __invoke(MessageNotification $message)
    {
        $message = $this->em->getRepository(Message::class)->find($message->getMessageId());
        $bots = $message->getChannel()->getBots();
        $data = $this->serializer->serialize($message, 'json');
        foreach($bots as $item) {
            if ($item->getUser()->getId() !== $message->getOwner()->getId()) {
                $response = $this->client->request('POST', $item->getWebhook(), [
                    'body' => $data,
                    ]
                );
            }
        }
    }
}
