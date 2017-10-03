<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


interface CartProviderInterface
{
    public function get($contenttype = null);
    public function add($contenttype, $id, $quantity = 1);
    public function remove($contenttype, $id);
    public function update($contenttype, $id, $quantity);
    public function reset();
}