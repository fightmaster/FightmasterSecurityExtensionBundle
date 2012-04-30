<?php

/**
 * This file is part of the FightmasterSecurityExtensionBundle package.
 *
 * (c) Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Fightmaster\SecurityExtensionBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Fightmaster\SecurityExtensionBundle\Security\Exception\InvalidArgumentException;

/**
 * Configures the DI container for FightmasterSecurityExtensionBundle.
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class FightmasterSecurityExtensionExtension extends Extension
{
    /**
     * Loads and processes configuration to configure the Container.
     *
     * @throws InvalidArgumentException
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        /** @todo implementation checking following parameters*/
        $config['by_privileges'] = $configs[0]['by_privileges'];
        $config['by_roles'] = $configs[0]['by_roles'];
        $container->setAlias('fightmaster_security_extension.manager.role_information', $config['role_information_manager']);
        $container->setParameter('fightmaster_security_extension.by_privileges', $config['by_privileges']);
        $container->setParameter('fightmaster_security_extension.by_roles', $config['by_roles']);
        $loader->load('services.xml');
        $this->loadAclRoleHandlerInformation($container, $config);
        $this->loadAclSecurityHandlerInformation($container, $config);
    }

    protected function loadAclRoleHandlerInformation(ContainerBuilder $container, array $config)
    {
        if (!empty($config['by_privileges'])) {
            $securityContextDefinition = new Reference('security.context');
            $roleInformationDefinition = new Reference('fightmaster_security_extension.manager.role_information');
            foreach ($config['by_privileges'] as $className => $roleInformation) {
                $shortClassName = strtolower($this->getShortNameOfClass($className));
                $definition = new Definition('%fightmaster_security_extension.manager.handler.role.class%',
                    array($securityContextDefinition, $roleInformationDefinition, $className));
                $container->setDefinition(sprintf('fightmaster_security_extension.manager.handler.role.%s', $shortClassName), $definition);
            }
        }
    }

    protected function loadAclSecurityHandlerInformation(ContainerBuilder $container, array $config)
    {
        if (!empty($config['by_roles'])) {
            $securityContextDefinition = new Reference('security.context');
            $objectIdentityDefinition = new Reference('security.acl.object_identity_retrieval_strategy');
            $aclProviderDefinition = new Reference('security.acl.provider');
            $roleInformationDefinition = new Reference('fightmaster_security_extension.manager.role_information');
            foreach ($config['by_roles'] as $className => $roleInformation) {
                $shortClassName = strtolower($this->getShortNameOfClass($className));
                $definition = new Definition('%fightmaster_security_extension.manager.handler.security.class%',
                    array($securityContextDefinition, $objectIdentityDefinition, $aclProviderDefinition, $roleInformationDefinition, $className));
                $container->setDefinition(sprintf('fightmaster_security_extension.manager.handler.security.%s', $shortClassName), $definition);
            }
        }
    }

    /**
     * @param $className
     * @return string|null mixed
     * @throws InvalidArgumentException
     */
    private function getShortNameOfClass($className)
    {
        if ($className === '') {
            throw new InvalidArgumentException('Name of the class is empty');
        }

        $shortName = str_replace('\\', '_', $className);
        if ($shortName === null) {
            throw new InvalidArgumentException(sprintf('Impossible to get the short name of the class ("%s").', $className));
        }

        return $shortName;
    }
}
