<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Storage\EntityManager;
use Bolt\Storage\Query\Query;

class Cart {

    /**
     * @var array $contents
     */
    protected $contents;

    /**
     * @var \Bolt\Storage\EntityManager
     */
    protected $query;

    /**
     * Cart constructor.
     * @param array $contents
     * @param \Bolt\Storage\Query\Query $query
     */
    public function __construct(array $contents, Query $query)
    {
        $this->contents = $contents;
        $this->query = $query;
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
        $ids = array_keys($this->contents[$contenttype]);

        $result = $this->query->getContent($contenttype, ['id' => implode(' || ', $ids)])->get();

        foreach ($result as $key => $item) {
            $result[$key]->quantity = $this->contents[$contenttype][$item->id];
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