<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Cart\Order;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\Exception\CustomerNotLoggedInException;
use Shopware\Core\Checkout\Cart\Exception\InvalidCartException;
use Shopware\Core\Checkout\Order\Exception\DeliveryWithoutAddressException;
use Shopware\Core\Checkout\Order\Exception\EmptyCartException;
use Shopware\Core\Checkout\Order\Messenger\Message\QueueOrderMessage;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\Event\NestedEventCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderPersister implements OrderPersisterInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $repository;

    /**
     * @var OrderConverter
     */
    private $converter;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(EntityRepositoryInterface $repository, OrderConverter $converter, MessageBusInterface $messageBus)
    {
        $this->repository = $repository;
        $this->converter = $converter;
        $this->messageBus = $messageBus;
    }

    /**
     * @throws CustomerNotLoggedInException
     * @throws DeliveryWithoutAddressException
     * @throws EmptyCartException
     * @throws InvalidCartException
     */
    public function persist(Cart $cart, SalesChannelContext $context): EntityWrittenContainerEvent
    {
        if ($cart->getErrors()->blockOrder()) {
            throw new InvalidCartException($cart->getErrors());
        }

        if (!$context->getCustomer()) {
            throw new CustomerNotLoggedInException();
        }
        if ($cart->getLineItems()->count() <= 0) {
            throw new EmptyCartException();
        }

        $message = new QueueOrderMessage($cart);
        $message->withContext($context);

        $this->messageBus->dispatch($message);

        //$order = $this->converter->convertToOrder($cart, $context, new OrderConversionContext());

        // return $this->repository->create([$order], $context->getContext());

        return new EntityWrittenContainerEvent($context->getContext(), new NestedEventCollection(), []);
    }
}
