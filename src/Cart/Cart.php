<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


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

    public function contents()
    {
        $content = [];
        foreach ($this->contents as $contenttype => $collection) {
            $ids = array_keys($collection);

            $content[$contenttype] = $this->query->getContent($contenttype, ['id' => implode(' || ', $ids)])->get();

            foreach ($content[$contenttype] as $key => $item) {
                $content[$contenttype][$key]->set('quantity', $collection[$item->id]);
            }
        }

        return $content;
    }

    public function totalPrice()
    {
        $price = 0;
        foreach ($this->contents() as $contenttype) {
            foreach ($contenttype as $product) {
                $price += $product->price * $product->quantity;
            }
        }

        return $price;
    }

    public function totalQuantity()
    {
        $quantity = 0;
        foreach ($this->contents as $contenttype) {
            foreach ($contenttype as $product) {
                $quantity++;
            }
        }

        return $quantity;
    }
}