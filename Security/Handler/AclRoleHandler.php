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
class AclRoleHandler extends AclHandlerAbstract
{
    /**
     * The current Security Context.
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param string $objectClassName
     * @param RoleInformationManagerInterface $roleInformationManager
     */
    public function __construct(SecurityContextInterface $securityContext, $objectClassName, RoleInformationManagerInterface $roleInformationManager)
    {
        parent::__construct($objectClassName, $roleInformationManager);
        $this->securityContext = $securityContext;
    }

    /**
     * Checks if the user is allowed to create new instances of the domain object / fields
     *
     * @return boolean
     */
    public function canCreate()
    {
        return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'create'));
    }

    /**
     * Checks if the user should be able to view the domain object / field
     *
     * @param $object
     * @return boolean
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     */
    public function canView($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'view'));
        }
    }

    /**
     * Checks if the user should be able to edit existing instances of the domain object / field
     *
     * @param $object
     * @return boolean
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     */
    public function canEdit($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'edit'));
        }
    }

    /**
     * Checks if the user should be able to delete domain objects
     *
     * @param $object
     * @return boolean
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     */
    public function canDelete($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'delete'));
        }
    }

    /**
     * Checks if the user should be able to recover domain objects from trash
     *
     * @param $object
     * @return boolean
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     */
    public function canUndelete($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'undelete'));
        }
    }

    /**
     * Checks if the user should be able to perform any action on the domain object except for granting others permissions
     *
     * @param $object
     * @return boolean
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     */
    public function isOperator($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'operator'));
        }
    }

    /**
     * Checks if the user should be able to perform any action on the domain object,
     * and is allowed to grant other SIDs any permission except for
     *
     * @param $object
     * @return boolean
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     */
    public function isMaster($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'master'));
        }
    }

    /**
     * Checks if the user is owning the domain object in question and can perform any
     * action on the domain object as well as grant any permission
     *
     * @param $object
     * @return boolean
     * @throws ObjectClassNameNotFoundException
     * @throws InvalidArgumentException
     */
    public function isOwner($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformationManager->getRoleByPrivilege($this->objectClassName, 'owner'));
        }
    }

    /**
     * Role based Acl does not require setup.
     *
     * @param $object
     */
    public function setDefaultAcl($object)
    {

    }

    /**
     * Role based Acl does not require setup.
     *
     * @return void
     */
    public function installFallbackAcl()
    {

    }

    /**
     * Role based Acl does not require setup.
     *
     * @return void
     */
    public function uninstallFallbackAcl()
    {

    }
}
