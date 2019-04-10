<?php declare(strict_types=1);

namespace Shopware\Core\Checkout;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Checkout extends Bundle
{
    protected $name = 'Checkout';

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/DependencyInjection/'));
        $loader->load('customer.xml');
        $loader->load('order.xml');
        $loader->load('payment.xml');
        $loader->load('shipping.xml');
        $loader->load('cart.xml');
        $loader->load('discount_surcharge.xml');
        $loader->load('rule.xml');
        $loader->load('promotion.xml');
        $loader->load('messenger.xml');
    }
}
