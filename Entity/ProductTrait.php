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

namespace Plugin\CustomerGroupProduct\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Plugin\CustomerGroup\Entity\Group;

/**
 * Class ProductTrait
 * @package Plugin\CustomerGroupProduct\Entity
 *
 * @EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Plugin\CustomerGroup\Entity\Group")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $registerGroup;

    /**
     * @return Group|null
     */
    public function getRegisterGroup(): ?Group
    {
        return $this->registerGroup;
    }

    /**
     * @return bool
     */
    public function hasRegisterGroup(): bool
    {
        return $this->registerGroup instanceof Group;
    }

    /**
     * @param Group $registerGroup
     * @return $this
     */
    public function setRegisterGroup(Group $registerGroup): self
    {
        $this->registerGroup = $registerGroup;

        return $this;
    }
}
