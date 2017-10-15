<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart;

use Bolt\Extension\PandaMadness\ShoppingCart\Cart\Manager;
use Bolt\Extension\PandaMadness\ShoppingCart\Providers\DatabaseProvider;
use Bolt\Extension\PandaMadness\ShoppingCart\Providers\SessionProvider;
use Bolt\Extension\PandaMadness\ShoppingCart\Config\Config;
use Bolt\Extension\SimpleExtension;
use Silex\Application;

/**
 * Simple shopping cart helper extension
 *
 * @author Margulan Baimbet <thecigaroman@gmail.com>
 */
class ShoppingCartExtension extends SimpleExtension
{
    protected function registerServices(Application $app)
    {
        $app['cart.config'] = $app->share(
            function ($app) {
                return new Config($this->getConfig());
            }
        );

        $app['cart.manager'] = $app->share(
            function ($app) {
                switch ($this->getConfig()['provider']) {
                    case 'session':
                        $provider = new SessionProvider($app['session'], $this->getConfig()['session']['key']);
                        break;
                    case 'database':
                        $provider = new DatabaseProvider($app['storage']);
                        break;
                    default:
                        throw new \UnexpectedValueException("Provider not found");
                }

                return new Manager($provider, $app['query']);
            }
        );

        $app['cart.providers.session'] = $app->share(
            function ($app) {
                return new SessionProvider($app['session'], $this->getConfig()['session']['key']);
            }
        );

        $app['cart.providers.database'] = $app->share(
            function ($app) {
                return new DatabaseProvider($app['storage']);
            }
        );
    }

    protected function registerFrontendControllers()
    {
        $app = $this->getContainer();
        $root = $app['cart.config']->getRoot();

        return [
            $root => new Controllers\CartController()
        ];
    }

    protected function registerTwigFunctions()
    {
        return [
            'fetch_cart' => 'fetchCart'
        ];
    }

    public function fetchCart()
    {
        $app = $this->getContainer();

        return $app['cart.manager']->get();
    }
}
