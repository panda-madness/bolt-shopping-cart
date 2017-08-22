<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Extension\PandaMadness\ShoppingCart\Config\Config;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionProvider implements CartProviderInterface {

    protected $session;
    protected $config;

    /**
     * SessionProvider constructor.
     * @param \Symfony\Component\HttpFoundation\Session\Session $session

     */
    public function __construct(Session $session)
    {
        $this->session = $session;

        if(!$this->session->has('shopping_cart')) {
            $this->session->set('shopping_cart', []);
        }
    }

    public function get($contenttype = null)
    {
        $contents = $this->session->get('shopping_cart');

        if(empty($contenttype)) {
            return $contents;
        } else {
            return isset($contents[$contenttype]) ? $contents[$contenttype] : [];
        }
    }

    public function add($contenttype, $id, $quantity = 1)
    {
        $contents = $this->session->get('shopping_cart');

        if(isset($contents[$contenttype][$id])) {
            $contents[$contenttype][$id] += $quantity;
        } else {
            $contents[$contenttype][$id] = $quantity;
        }

        return $this->session->set('shopping_cart', $contents);
    }

    public function remove($contenttype, $id)
    {
        $contents = $this->session->get('shopping_cart');

        if(isset($contents[$contenttype][$id])) {
            unset($contents[$contenttype][$id]);
        }

        return $this->session->set('shopping_cart', $contents);
    }

    public function update($contenttype, $id, $quantity)
    {
        $contents = $this->session->get('shopping_cart');

        if(isset($contents[$contenttype][$id])) {
            $contents[$contenttype][$id] = $quantity;
        } else {
            $contents[$contenttype][$id] = 1;
        }

        return $this->session->set('shopping_cart', $contents);
    }

    public function reset()
    {
        $this->session->set('shopping_cart', []);
    }
}