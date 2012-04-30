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

use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclAlreadyExistsException;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;
use Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Acl\Permission\BasicPermissionMap;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Fightmaster\SecurityExtensionBundle\Security\Handler\Exception\InvalidArgumentException;
use Fightmaster\SecurityExtensionBundle\Security\Handler\Exception\ObjectClassNameNotFoundException;
use Fightmaster\SecurityExtensionBundle\Security\RoleInformationManagerInterface;

/**
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class AclSecurityHandler extends AclHandlerAbstract
{
    /**
     * Used to retrieve ObjectIdentity instances for objects.
     *
     * @var ObjectIdentityRetrievalStrategyInterface
     */
    private $objectRetrieval;

    /**
     * The AclProvider.
     *
     * @var MutableAclProviderInterface
     */
    private $aclProvider;

    /**
     * The current Security Context.
     *
     * @var SecurityContextInterface
     */
    private $securityContext;

    /**
     * The Class OID for the object.
     *
     * @var ObjectIdentity
     */
    private $oid;

    /**
     * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
     * @param \Symfony\Component\Security\Acl\Model\ObjectIdentityRetrievalStrategyInterface $objectRetrieval
     * @param \Symfony\Component\Security\Acl\Model\MutableAclProviderInterface $aclProvider
     * @param string $objectClassName
     */
    public function __construct(SecurityContextInterface $securityContext,
                                ObjectIdentityRetrievalStrategyInterface $objectRetrieval,
                                MutableAclProviderInterface $aclProvider, $objectClassName,
                                RoleInformationManagerInterface $roleInformationManager)
    {
        parent::__construct($objectClassName, $roleInformationManager);
        $this->securityContext   = $securityContext;
        $this->objectRetrieval   = $objectRetrieval;
        $this->aclProvider       = $aclProvider;
    }

    /**
     * Sets he FQCN of the object and value of the ObjectIdentity
     *
     * @param string $objectClassName
     */
    public function setObjectClassName($objectClassName)
    {
        $this->objectClassName   = $objectClassName;
        $this->oid               = new ObjectIdentity('class', $this->objectClassName);;
    }

    /**
     * Checks if the user is allowed to create new instances of the domain object / fields
     *
     * @return boolean
     */
    public function canCreate()
    {
        if ($this->oid == null) {
            throw new ObjectIdentityNotFoundException();
        }

        return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_CREATE, $this->oid);
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
            return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_VIEW, $object);
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
            return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_EDIT, $object);
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
            return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_DELETE, $object);
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
            return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_UNDELETE, $object);
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
            return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_OPERATOR, $object);
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
            return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_MASTER, $object);
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
            return $this->securityContext->isGranted(BasicPermissionMap::PERMISSION_OWNER, $object);
        }
    }

    /**
     * Sets the default Acl permissions on a comment.
     *
     * Note: this does not remove any existing Acl and should only
     * be called on new instances.
     *
     * @param $object
     * @return void
     * @throws InvalidArgumentException
     */
    public function setDefaultAcl($object)
    {
        if ($this->isExpectedObject($object)) {
            $objectIdentity = $this->objectRetrieval->getObjectIdentity($object);
            $acl = $this->aclProvider->createAcl($objectIdentity);

            if ($this->isAvailableUserInterface($object)) {
                $securityIdentity = UserSecurityIdentity::fromAccount($object->getAuthor());
                $acl->insertObjectAce($securityIdentity, $this->getDefaultAclMask());
            }
        }

        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Installs the Default 'fallback' Acl entries for generic access.
     * This needs to be re-run whenever the object changes or is subclassed.
     *
     * @throws ObjectClassNameNotFoundException
     * @return void
     */
    public function installFallbackAcl()
    {
        if ($this->objectClassName == null) {
            throw new ObjectClassNameNotFoundException();
        }
        $oid = new ObjectIdentity('class', $this->objectClassName);

        try {
            $acl = $this->aclProvider->createAcl($oid);
        } catch (AclAlreadyExistsException $exists) {
            return;
        }

        $this->doInstallFallbackAcl($acl, new MaskBuilder());
        $this->aclProvider->updateAcl($acl);
    }

    /**
     * Removes default Acl entries
     *
     * This should be run when uninstalling the object in your project, or when
     * the Class Acl entry end up corrupted.
     *
     * @throws ObjectClassNameNotFoundException
     * @return void
     */
    public function uninstallFallbackAcl()
    {
        if ($this->objectClassName == null) {
            throw new ObjectClassNameNotFoundException();
        }
        $oid = new ObjectIdentity('class', $this->objectClassName);
        $this->aclProvider->deleteAcl($oid);
    }

    /**
     *
     * @param $object
     * @return bool
     */
    protected function isAvailableUserInterface($object)
    {
        return false;
    }

    /**
     * Installs the default Class Ace entries into the provided $acl object.
     *
     * Override this method in a subclass to change what permissions are defined.
     * Once this method has been overridden you need to run the
     * `fightmaster_security:installAces --flush` command
     *
     * @param AclInterface $acl
     * @param MaskBuilder $builder
     * @return void
     */
    protected function doInstallFallbackAcl(AclInterface $acl, MaskBuilder $builder)
    {
        $roleInformation = $this->roleInformationManager->getPrivilegesBasedOnRoles($this->objectClassName);
        if (count($roleInformation)) {
            foreach ($roleInformation as $role => $permissions) {
                foreach ($permissions as $permission) {
                    $builder->add($permission);
                }
                $acl->insertClassAce(new RoleSecurityIdentity($role), $builder->get());
                $builder->reset();
            }
        }
    }

    /**
     * @return int
     */
    protected function getDefaultAclMask()
    {
        return MaskBuilder::MASK_OWNER;
    }
}
