<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Storage\EntityManager;
use Bolt\Storage\Query\Query;

class CartService {

    protected $provider;

    protected $storage;

    protected $cart;

    /**
     * Cart constructor.
     * @param \Bolt\Extension\PandaMadness\ShoppingCart\Cart\CartProviderInterface $provider
     * @param \Bolt\Storage\EntityManager $storage
     */
    public function __construct(CartProviderInterface $provider, Query $query)
    {
        $this->provider = $provider;
        $this->query = $query;
        $this->cart = new Cart($this->get(), $query);
    }

    public function fetch($contenttype = null)
    {
        return $this->cart->fetch($contenttype);
    }

    public function get($contenttype = null)
    {
        return $this->provider->get($contenttype);
    }

    public function add($contenttype, $id, $quantity = 1)
    {
        $this->provider->add($contenttype, $id, $quantity);
    }

    public function remove($contenttype, $id)
    {
        $this->provider->remove($contenttype, $id);
    }

    public function update($contenttype, $id, $quantity = 1)
    {
        $this->provider->update($contenttype, $id, $quantity);
    }

    public function reset()
    {
        $this->provider->reset();
    }
}