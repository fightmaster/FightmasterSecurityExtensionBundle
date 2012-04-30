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

/**
 * Used for checking if the ACL system will allow specific actions  to occur.
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
interface HandlerInterface
{
    /**
     * Checks if the user is allowed to create new instances of the domain object / fields
     *
     * @return boolean
     */
    public function canCreate();

    /**
     * Checks if the user should be able to view the domain object / field
     *
     * @param $object
     * @return boolean
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function canView($object);

    /**
     * Checks if the user should be able to edit existing instances of the domain object / field
     *
     * @param $object
     * @return boolean
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function canEdit($object);

    /**
     * Checks if the user should be able to delete domain objects
     *
     * @param $object
     * @return boolean
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function canDelete($object);

    /**
     * Checks if the user should be able to recover domain objects from trash
     *
     * @param $object
     * @return boolean
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function canUndelete($object);

    /**
     * Checks if the user should be able to perform any action on the domain object except for granting others permissions
     *
     * @param $object
     * @return boolean
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function isOperator($object);

    /**
     * Checks if the user should be able to perform any action on the domain object,
     * and is allowed to grant other SIDs any permission except for
     *
     * @param $object
     * @return boolean
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function isMaster($object);

    /**
     * Checks if the user is owning the domain object in question and can perform any
     * action on the domain object as well as grant any permission
     *
     * @param $object
     * @return boolean
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function isOwner($object);

    /**
     * Sets the default Acl permissions on a comment.
     *
     * Note: this does not remove any existing Acl and should only
     * be called on new instances.
     *
     * @param $object
     * @return void
     * @throws Exception\ObjectClassNameNotFoundException
     * @throws Exception\InvalidArgumentException
     */
    public function setDefaultAcl($object);

    /**
     * Installs the Default 'fallback' Acl entries for generic access.
     *
     * @return void
     */
    public function installFallbackAcl();

    /**
     * Removes default Acl entries
     *
     * @return void
     */
    public function uninstallFallbackAcl();
}
