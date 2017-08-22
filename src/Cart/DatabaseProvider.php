<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Storage\EntityManager;

class DatabaseProvider implements CartProviderInterface {

    protected $storage;

    /**
     * SessionProvider constructor.
     * @param \Bolt\Storage\EntityManager $storage
     */
    public function __construct(EntityManager $storage)
    {
        $this->storage = $storage;
    }

    public function add($contenttype, $id, $quantity = 1)
    {
        // TODO: Implement add() method.
    }

    public function remove($contenttype, $id)
    {
        // TODO: Implement remove() method.
    }

    public function update($contenttype, $id, $quantity)
    {
        // TODO: Implement update() method.
    }

    public function reset()
    {
        // TODO: Implement reset() method.
    }

    public function get($contenttype = null)
    {
        // TODO: Implement get() method.
    }
}