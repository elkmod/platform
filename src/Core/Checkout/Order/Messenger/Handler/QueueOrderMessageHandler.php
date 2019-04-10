<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Order\Messenger\Handler;

use Shopware\Core\Checkout\Order\Messenger\Message\QueueOrderMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class QueueOrderMessageHandler implements MessageHandlerInterface
{
    public function __invoke(QueueOrderMessage $message)
    {
        // platform/src/Core/Content/Media/Message/GenerateThumbnailsMessage.php -> readContext / withContext method call php serializer
        echo $message->getCart()->getPrice()->getTotalPrice();
    }
}
