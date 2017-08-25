<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Storage\EntityManager;

class Cart {

    protected $provider;

    protected $storage;

    /**
     * Cart constructor.
     * @param \Bolt\Extension\PandaMadness\ShoppingCart\Cart\CartProviderInterface $provider
     * @param \Bolt\Storage\EntityManager $storage
     */
    public function __construct(CartProviderInterface $provider, EntityManager $storage)
    {
        $this->provider = $provider;
        $this->storage = $storage;
    }

    public function fetch($contenttype = null)
    {
        $contents = $this->get($contenttype);

        $items = [];
        $data = [];

        foreach ($contents as $contenttype => $value) {
            $ids = array_keys($value);
            $fetched = $this->storage->getContent($contenttype, ['id' => implode(' || ', $ids)]);
        }

        if(empty($contenttype)) {
            foreach ($contents as $contenttype => $value) {
                $ids = array_keys($value);
                $fetched = $this->storage->getContent($contenttype, ['id' => implode(' || ', $ids)]);
                dump(implode(' || ', $ids));
                die();
                $fetched = is_array($fetched) ? $fetched : [$fetched];
                foreach ($fetched as $item) {
                    $data['content'] = $item;

                    $data['quantity'] = $value[$item['id']];

                    $items[] = $data;
                }
            }

            return $items;
        }

        $ids = array_keys($contents);
        $items['content'] = $this->storage->getContent($contenttype, ['id' => implode(' || ', $ids)]);
        $items['quantity'] = $contents[$items['content']['id']];

        return $items;
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