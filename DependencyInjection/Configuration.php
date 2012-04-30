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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 *
 * @author Dmitry Petrov aka fightmaster <old.fightmaster@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->root('fightmaster_security_extension', 'array')
            ->children()
                ->scalarNode('role_information_manager')->cannotBeEmpty()->defaultValue('fightmaster_security_extension.manager.role_information.default')->end()
                ->arrayNode('by_privileges')->addDefaultsIfNotSet()->ignoreExtraKeys()->end()
                ->arrayNode('by_roles')->addDefaultsIfNotSet()->ignoreExtraKeys()->end()
            ->end();

        return $treeBuilder;
    }
}
