<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Order\Messenger\Message;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class QueueOrderMessage
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var string
     */
    private $contextData;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return string
     */
    public function getContextData(): string
    {
        return $this->contextData;
    }

    /**
     * @param string $contextData
     */
    public function setContextData(string $contextData): void
    {
        $this->contextData = $contextData;
    }

    public function withContext(SalesChannelContext $context): QueueOrderMessage
    {
        $this->contextData = serialize($context);

        return $this;
    }

    public function readContext(): SalesChannelContext
    {
        return unserialize($this->contextData);
    }
}
