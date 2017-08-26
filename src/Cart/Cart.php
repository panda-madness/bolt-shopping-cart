<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Storage\EntityManager;

class Cart {

    /**
     * @var array $contents
     */
    protected $contents;

    /**
     * @var \Bolt\Storage\EntityManager
     */
    protected $storage;

    /**
     * Cart constructor.
     * @param array $contents
     */
    public function __construct(array $contents, EntityManager $storage)
    {
        $this->contents = $contents;
        $this->storage = $storage;
    }

    public function getContenttypeIds($contenttype)
    {
        if(isset($this->contents[$contenttype])) {
            return array_keys($this->contents[$contenttype]);
        }

        return [];
    }

    public function fetchContenttype($contenttype)
    {
        $ids = $this->getContenttypeIds($contenttype);

        $result = $this->storage->getContent($contenttype, ['id' => implode(' || ', $ids)]);

        $result = is_array($result) ? $result : [$result['id'] => $result];

        foreach ($this->contents[$contenttype] as $id => $quantity) {
            $arr['quantity'] = $quantity;
            $arr['content'] = $result[$id];
            $result[$id] = $arr;
        }

        return $result;
    }

    public function fetchAllContenttypes()
    {
        $items = [];

        foreach ($this->contents as $contenttype => $value) {

            $items[$contenttype] = $this->fetchContenttype($contenttype);
        }

        return $items;
    }

    public function fetch($contenttype)
    {
        if(empty($contenttype)) {
            return $this->fetchAllContenttypes();
        }

        return $this->fetchContenttype($contenttype);
    }
}