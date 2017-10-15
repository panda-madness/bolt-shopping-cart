<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Providers;


use Bolt\Collection\Bag;
use Bolt\Collection\MutableBag;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionProvider implements CartProviderInterface {

    protected $session;
    protected $key;
    protected $contents;

    /**
     * SessionProvider constructor.
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param string $key
     */
    public function __construct(Session $session, string $key)
    {
        $this->session = $session;
        $this->key = $key;

        if(!$this->session->has($this->key)) {
            $this->session->set($this->key, []);
        }

        $this->contents = MutableBag::from($this->session->get($this->key));
    }

    public function get(string $contenttype = null)
    {
        if(empty($contenttype)) {
            return $this->contents->toArray();
        }

        return $this->contents->get($contenttype, []);
    }

    public function add(string $contenttype, int $id, int $quantity = 1)
    {
        foreach ($this->contents as $key => $item) {
            if($item['contenttype'] === $contenttype && $item['id'] === $id) {
                $item['quantity'] += $quantity;
                $this->contents->set($key, $item);
                return $this->save();
            }
        }

        $this->contents->add([
            'contenttype' => $contenttype,
            'id' => $id,
            'quantity' => $quantity,
        ]);

        return $this->save();
    }

    public function remove(string $contenttype, int $id)
    {
        foreach ($this->contents as $key => $item) {
            if($item['contenttype'] === $contenttype && $item['id'] === $id) {
                $this->contents->remove($key);
                return $this->save();
            }
        }

        return $this->save();
    }

    public function update(string $contenttype, int $id, int $quantity)
    {
        foreach ($this->contents as $key => $item) {
            if($item['contenttype'] === $contenttype && $item['id'] === $id) {
                $item['contenttype'] = $contenttype;
                $item['id'] = $id;
                $item['quantity'] = $quantity;
                $this->contents->set($key, $item);
                return $this->save();
            }
        }

        return $this->save();
    }

    public function reset()
    {
        $this->contents = [];

        return $this->save();
    }

    private function save() {
        return $this->session->set($this->key, $this->contents->toArray());
    }
}