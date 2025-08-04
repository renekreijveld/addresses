<?php

/**
 * @package     com_addresses
 * @version     1.0.0
 * @copyright   Copyright (C) 2025. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 * @author      René Kreijveld <email@renekreijveld.nl> - https://renekreijveld.nl
 */

namespace Joomla\Plugin\WebServices\Addresses\Extension;

use Joomla\CMS\Event\Application\BeforeApiRouteEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;
use Joomla\Router\Route;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Web Services adapter for com_addresses.
 *
 * @since  4.0.0
 */
final class Addresses extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   5.1.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeApiRoute' => 'onBeforeApiRoute',
        ];
    }

    /**
     * Registers com_addresses's API's routes in the application
     *
     * @param   BeforeApiRouteEvent  $event  The event object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function onBeforeApiRoute(BeforeApiRouteEvent $event): void
    {
        $router = $event->getRouter();

        $defaults = [
            'component' => 'com_addresses',
            'public' => false
        ];

        $routes = [
            new Route(['GET'], 'v1/addresses', 'addresses.displayList', [], $defaults),
            new Route(['GET'], 'v1/addresses/:id', 'addresses.displayItem', ['id' => '(\d+)'], $defaults),
            new Route(['GET'], 'v1/addresses/search/:search', 'addresses.search', ['search' => '[a-zA-Z0-9 ]*'], $defaults),
            new Route(['GET'], 'v1/addresses/postcode/:postcode', 'addresses.postcode', ['postcode' => '[0-9]{4}[a-zA-Z]{2}'], $defaults),
            new Route(['POST'], 'v1/addresses', 'addresses.add', [], $defaults),
            new Route(['DELETE'], 'v1/addresses', 'addresses.delete', [], $defaults)
        ];

        $router->addRoutes($routes);
    }
}
