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

namespace Plugin\CustomerGroupProduct\Tests\Web;


use Eccube\Entity\Customer;
use Eccube\Entity\ProductClass;
use Eccube\Tests\Web\AbstractShoppingControllerTestCase;
use Plugin\CustomerGroup\Tests\TestCaseTrait;

class ShoppingControllerTest extends AbstractShoppingControllerTestCase
{
    use TestCaseTrait;

    /**
     * @var \Plugin\CustomerGroup\Entity\Group
     */
    protected $group;

    /**
     * @var \Eccube\Entity\Customer
     */
    protected $customer;

    /**
     * @var \Eccube\Entity\Product
     */
    protected $product;

    public function setUp()
    {
        parent::setUp();

        $this->group = $this->createGroup();
        $this->customer = $this->createCustomer();
        $this->product = $this->createProduct();
    }

    public function test自動登録する会員グループが設定されている商品を購入したら会員に会員グループが登録されるか()
    {
        $this->product->setRegisterGroup($this->group);

        /** @var ProductClass $productClass */
        $productClass = $this->product->getProductClasses()->first();

        // カートに追加
        $this->scenarioCartIn($this->customer, $productClass->getId());

        $this->scenarioConfirm($this->customer);

        // チェックアウト
        $this->scenarioCheckout($this->customer);

        // 購入完了
        $this->client->request('GET', $this->generateUrl('shopping_complete'));

        /** @var Customer $customer */
        $customer = $this->entityManager->find(Customer::class, $this->customer->getId());
        $groups = $customer->getGroups();

        self::assertCount(1, $groups);
        self::assertEquals($this->group->getId(), $groups->first()->getId());
    }
}
