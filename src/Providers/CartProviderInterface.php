<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Providers;


interface CartProviderInterface
{
    public function get(string $contenttype = null);
    public function add(string $contenttype, int $id, int $quantity = 1);
    public function remove(string $contenttype, int $id);
    public function update(string $contenttype, int $id, int $quantity);
    public function reset();
}