<?php

/**
 * This file is part of the FightmasterSecurityExtensionBundle package.
 *
 * (c) Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fightmaster\SecurityExtensionBundle\Security;

/**
 * Used for gets role information.
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class RoleInformationManager implements RoleInformationManagerInterface
{
    /**
     * @var array
     */
    private $roleInformationBasedOnPriveleges = array('create' => null, 'view' => null, 'edit' => null, 'delete' => null,
        'undelete' => null, 'operator' => null, 'master' => null, 'owner' => null);

    /**
     * {@inheritDoc}
     *
     * @param string $objectClassName
     * @return array
     */
    public function getRolesBasedOnPrivileges($objectClassName)
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * {@inheritDoc}
     *
     * @param string $objectClassName
     * @return array
     */
    public function getPrivilegesBasedOnRoles($objectClassName)
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * {@inheritDoc}
     *
     * @param string $objectClassName
     * @param string $privilege
     * @return string
     */
    public function getRoleByPrivilege($objectClassName, $privilege)
    {
        return $this->roleInformationBasedOnPriveleges[$privilege];
    }

    /**
     * {@inheritDoc}
     *
     * @param string $objectClassName
     * @param string $role
     * @return array
     */
    public function getPrivilegesByRole($objectClassName, $role)
    {
        throw new \Exception('Not yet implemented');
    }

    /**
     * @param array $roleInformation
     */
    private function setRoleInformation(array $roleInformation)
    {
        $this->roleInformation = array_merge($this->roleInformation, $roleInformation);
    }
}
