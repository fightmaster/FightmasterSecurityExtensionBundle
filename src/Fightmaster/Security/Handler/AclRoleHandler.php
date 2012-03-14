<?php

/**
 * This file is part of the Fightmaster/security-extension library.
 *
 * (c) Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fightmaster\Security\Handler;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Fightmaster\Security\Handler\Exception\InvalidArgumentException;
use Fightmaster\Security\Handler\Exception\ObjectClassNameNotFoundException;

/**
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class AclRoleHandler implements HandlerInterface
{
    /**
     * The current Security Context.
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var array
     */
    private $roleInformation = array('create' => null, 'view' => null, 'edit' => null, 'delete' => null,
                                     'undelete' => null, 'operator' => null, 'master' => null, 'owner' => null);

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext
     * @param $roleInformation
     */
    public function __construct(SecurityContextInterface $securityContext, $roleInformation = array())
    {
        $this->securityContext   = $securityContext;
        $this->setRoleInformation($roleInformation);
    }

    /**
     * Sets he FQCN of the object
     *
     * @param string $objectClassName
     */
    public function setObjectClassName($objectClassName)
    {
        $this->objectClassName = $objectClassName;
    }

    /**
     * @param array $roleInformation
     */
    public function setRoleInformation(array $roleInformation)
    {
        $this->roleInformation = array_merge($this->roleInformation, $roleInformation);
    }

    /**
     * Checks if the user is allowed to create new instances of the domain object / fields
     *
     * @return boolean
     */
    public function canCreate()
    {
        return $this->securityContext->isGranted($this->roleInformation['create']);
    }

    /**
     * Checks if the user should be able to view the domain object / field
     *
     * @param $object
     * @return boolean
     */
    public function canView($object)
    {
        return $this->securityContext->isGranted($this->roleInformation['view']);
    }

    /**
     * Checks if the user should be able to edit existing instances of the domain object / field
     *
     * @param $object
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function canEdit($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformation['edit']);
        }
    }

    /**
     * Checks if the user should be able to delete domain objects
     *
     * @param $object
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function canDelete($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformation['delete']);
        }
    }

    /**
     * Checks if the user should be able to recover domain objects from trash
     *
     * @param $object
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function canUndelete($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformation['undelete']);
        }
    }

    /**
     * Checks if the user should be able to perform any action on the domain object except for granting others permissions
     *
     * @param $object
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function isOperator($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformation['operator']);
        }
    }

    /**
     * Checks if the user should be able to perform any action on the domain object,
     * and is allowed to grant other SIDs any permission except for
     *
     * @param $object
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function isMaster($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformation['master']);
        }
    }

    /**
     * Checks if the user is owning the domain object in question and can perform any
     * action on the domain object as well as grant any permission
     *
     * @param $object
     * @return boolean
     * @throws Exception\InvalidArgumentException
     */
    public function isOwner($object)
    {
        if ($this->isExpectedObject($object)) {
            return $this->securityContext->isGranted($this->roleInformation['owner']);
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
    public function installFallbackAcl($roleInformation = array())
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

    /**
     * Checks object
     *
     * @param $object
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
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
