<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Order\Messenger\Handler;

use Shopware\Core\Checkout\Cart\Order\OrderConversionContext;
use Shopware\Core\Checkout\Cart\Order\OrderConverter;
use Shopware\Core\Checkout\Order\Exception\DeliveryWithoutAddressException;
use Shopware\Core\Checkout\Order\Messenger\Message\QueueOrderMessage;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class QueueOrderMessageHandler implements MessageHandlerInterface
{
    private $converter;

    private $repository;

    public function __construct(EntityRepositoryInterface $repository, OrderConverter $converter)
    {
        $this->repository = $repository;
        $this->converter = $converter;
    }

    public function __invoke(QueueOrderMessage $message)
    {
        $context = $message->readContext();

        try {
            $order = $this->converter->convertToOrder($message->getCart(), $context, new OrderConversionContext());
        } catch (DeliveryWithoutAddressException $e) {
            return $e->getMessage();
        }

        return $this->repository->create([$order], $context->getContext());
    }
}
