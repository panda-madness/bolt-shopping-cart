<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Extension\PandaMadness\ShoppingCart\Providers\CartProviderInterface;
use Bolt\Storage\EntityManager;
use Bolt\Storage\Query\Query;

class Manager {

    protected $provider;

    protected $cart;

    protected $query;

    /**
     * Cart constructor.
     * @param \Bolt\Extension\PandaMadness\ShoppingCart\Providers\CartProviderInterface $provider
     * @param \Bolt\Storage\Query\Query $query
     */
    public function __construct(CartProviderInterface $provider, Query $query)
    {
        $this->provider = $provider;
        $this->query = $query;
        $this->cart = new Cart($this->provider->get(), $query);
    }

    public function get()
    {
        return $this->cart;
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