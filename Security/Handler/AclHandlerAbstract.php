<?php

/**
 * This file is part of the FightmasterSecurityExtensionBundle package.
 *
 * (c) Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fightmaster\SecurityExtensionBundle\Security\Handler;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Fightmaster\SecurityExtensionBundle\Security\Handler\Exception\InvalidArgumentException;
use Fightmaster\SecurityExtensionBundle\Security\Handler\Exception\ObjectClassNameNotFoundException;
use Fightmaster\SecurityExtensionBundle\Security\RoleInformationManagerInterface;

/**
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
abstract class AclHandlerAbstract implements HandlerInterface
{
    /**
     * The FQCN of the object.
     *
     * @var string
     */
    protected $objectClassName;

    /**
     * @var RoleInformationManagerInterface
     */
    protected $roleInformationManager;

    /**
     * @param RoleInformationManagerInterface $roleInformationManagerInterface
     * @param string $objectClassName
     */
    public function __construct(RoleInformationManagerInterface $roleInformationManagerInterface, $objectClassName)
    {
        $this->roleInformationManager = $roleInformationManagerInterface;
        $this->objectClassName = $objectClassName;
    }

    /**
     * Checks object
     *
     * @param $object
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     * @return bool
     */
    protected function isExpectedObject($object)
    {
        if ($this->objectClassName == null) {
            throw new ObjectClassNameNotFoundException();
        }
        $className = $this->objectClassName;
        if (!$object instanceof $className) {
            throw new InvalidArgumentException();
        }

        return true;
    }
}
