<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Cart;


use Bolt\Collection\Bag;
use Bolt\Storage\Query\Query;

class Cart {

    /**
     * @var array $contents
     */
    public $contents;

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
        $this->contents = Bag::from($contents);
        $this->query = $query;
    }

    public function get()
    {
        $sorted = [];

        foreach ($this->contents as $item) {
            $arr['id'] = $item['id'];
            $arr['quantity'] = $item['quantity'];
            $sorted[$item['contenttype']][] = $arr;
        }

        $final = [];

        foreach ($sorted as $ct => $items) {
            $ids = [];

            foreach ($items as $item) {
                $ids[] = $item['id'];
            }

            $result = $this->query->getContent($ct, ['id' => implode(' || ', $ids)])->get();

            foreach ($items as $item) {
                foreach ($result as $content) {
                    if($content->id === $item['id']) {
                        $final[$ct][$content->id]['item'] = $content;
                        $final[$ct][$content->id]['quantity'] = $item['quantity'];
                    }
                }
            }
        }

        return $final;
    }
}