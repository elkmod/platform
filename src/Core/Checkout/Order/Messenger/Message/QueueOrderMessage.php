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
     * @var SalesChannelContext
     */
    private $context;

    public function __construct(Cart $cart, SalesChannelContext $context)
    {
        $this->cart = $cart;
        $this->context = $context;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart)
    {
        $this->cart = $cart;
    }

    public function getContext(): SalesChannelContext
    {
        return $this->context;
    }

    public function setContext(SalesChannelContext $context)
    {
        $this->context = $context;
    }
}
