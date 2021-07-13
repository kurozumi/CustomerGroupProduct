<?php
/**
 * This file is part of CustomerGroupProduct
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\CustomerGroupProduct\EventListener;


use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Eccube\Entity\Order;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ShoppingCompleteListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ShoppingCompleteListener constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            EccubeEvents::FRONT_SHOPPING_COMPLETE_INITIALIZE => 'onFrontShoppingCompleteInitialize'
        ];
    }

    /**
     * @param EventArgs $event
     */
    public function onFrontShoppingCompleteInitialize(EventArgs $event): void
    {
        /** @var Order $order */
        $order = $event->getArgument('Order');
        $customer = $order->getCustomer();

        if ($customer instanceof Customer) {
            foreach ($order->getOrderItems() as $orderItem) {
                if ($orderItem->isProduct()) {
                    $product = $orderItem->getProduct();
                    if ($product->hasRegisterGroup()) {
                        $customer->getGroups()->clear();
                        $customer->addGroup($product->getRegisterGroup());
                    }
                }
            }
            $this->entityManager->persist($customer);
            $this->entityManager->flush();
        }
    }
}
