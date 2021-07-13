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

namespace Plugin\CustomerGroupProduct;


use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Plugin;
use Eccube\Plugin\AbstractPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PluginManager extends AbstractPluginManager
{
    public function enable(array $meta, ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $Plugin = $entityManager
            ->getRepository(Plugin::class)
            ->findOneBy(['code' => 'CustomerGroup', 'enabled' => true]);

        if (is_null($Plugin)) {
            log_error('会員グループ管理プラグイン for EC-CUBE4が有効化されていないので有効化できません');
            throw new HttpException(400, '会員グループ管理プラグイン for EC-CUBE4が有効化されていないので有効化できません');
        }
    }
}
