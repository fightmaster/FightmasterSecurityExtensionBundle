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
interface RoleInformationManagerInterface
{
    /**
     * Returns array of the roles
     *
     * <code>
     * array('edit' => 'ROLE_USER', 'create' => 'ROLE_USER', 'delete' => 'ROLE_ADMIN')
     * </code>
     *
     * @abstract
     * @param string $objectClassName
     * @return array
     */
    public function getRolesBasedOnPrivileges($objectClassName);

    /**
     * Returns array of the privileges which groupped by roles
     *
     * <code>
     * array('ROLE_USER' => array('edit', 'view', 'create'), 'ROLE_ADMIN' => array('delete', 'create', 'edit'))
     * </code>
     *
     * @abstract
     * @param string $objectClassName
     * @return array
     */
    public function getPrivilegesBasedOnRoles($objectClassName);

    /**
     * Returns role
     *
     * @abstract
     * @param string $objectClassName
     * @param string $privilege
     * @return string
     */
    public function getRoleByPrivilege($objectClassName, $privilege);

    /**
     * Returns array of the privileges which corresponds the role
     *
     * @abstract
     * @param string $objectClassName
     * @param string $role
     * @return array
     */
    public function getPrivilegesByRole($objectClassName, $role);
}
