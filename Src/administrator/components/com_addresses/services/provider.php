<?php
/**
 * @package     Com_addresses
 * @version     1.3.3
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\Component\Addresses\Administrator\Extension\AddressesComponent;

/**
 * The service provider.
 *
 * @since  4.0.0
 */
return new class implements ServiceProviderInterface {
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	public function register(Container $container)
	{
		$container->registerServiceProvider(new MVCFactory('\\Joomla\\Component\\Addresses'));
		$container->registerServiceProvider(new ComponentDispatcherFactory('\\Joomla\\Component\\Addresses'));
		$container->registerServiceProvider(new RouterFactory('\\Joomla\\Component\\Addresses'));

		$container->set(
			ComponentInterface::class,
			function (Container $container) {
				$component = new AddressesComponent($container->get(ComponentDispatcherFactoryInterface::class));
				$component->setMVCFactory($container->get(MVCFactoryInterface::class));
				$component->setRouterFactory($container->get(RouterFactoryInterface::class));

				return $component;
			}
		);
	}
};
