<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart;

use Bolt\Extension\PandaMadness\ShoppingCart\Cart\CartService;
use Bolt\Extension\PandaMadness\ShoppingCart\Cart\CartProviderInterface;
use Bolt\Extension\PandaMadness\ShoppingCart\Cart\DatabaseProvider;
use Bolt\Extension\PandaMadness\ShoppingCart\Cart\SessionProvider;
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
    protected function getDefaultConfig()
    {
        return [
            'provider' => 'session',
            'url' => '/cart',
            'redirects' => [
                'cart_added' => '/cart',
                'cart_removed' => '/cart',
                'cart_updated' => '/cart',
                'cart_reset' => '/cart',
            ]
        ];
    }

    protected function registerServices(Application $app)
    {
        $app['cart'] = $app->share(
            function ($app) {
                switch ($this->getConfig()['provider']) {
                    case 'session':
                        $provider = new SessionProvider($app['session']);
                        break;
                    case 'database':
                        $provider = new DatabaseProvider($app['storage']);
                        break;
                }

                return new CartService($provider, $app['storage']);
            }
        );

        $app['cart.providers.session'] = $app->share(
            function ($app) {
                return new SessionProvider($app['session']);
            }
        );

        $app['cart.providers.database'] = $app->share(
            function ($app) {
                return new DatabaseProvider($app['storage']);
            }
        );

        $app['cart.config'] = $app->share(
            function ($app) {
                return new Config($this->getConfig());
            }
        );
    }

    protected function registerFrontendControllers()
    {
        $app = $this->getContainer();
        $root = $app['cart.config']->getPathRoot();

        return [
            $root => new Controllers\CartController(),
            $root . '/api' => new Controllers\AjaxController(),
        ];
    }

    protected function registerTwigFunctions()
    {
        return [
            'fetch_cart' => 'fetchCart'
        ];
    }

    public function fetchCart($contenttype = null)
    {
        $app = $this->getContainer();

        return $app['cart']->fetch($contenttype);
    }
}
