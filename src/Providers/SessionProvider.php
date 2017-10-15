<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Providers;


use Bolt\Extension\PandaMadness\ShoppingCart\Config\Config;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionProvider implements CartProviderInterface {

    protected $session;
    protected $key;

    /**
     * SessionProvider constructor.
     * @param \Symfony\Component\HttpFoundation\Session\Session $session

     */
    public function __construct(Session $session, string $key)
    {
        $this->session = $session;
        $this->key = $key;

        if(!$this->session->has($this->key)) {
            $this->session->set($this->key, []);
        }
    }

    public function get($contenttype = null)
    {
        $contents = $this->session->get($this->key);

        if(empty($contenttype)) {
            return $contents;
        }

        return isset($contents[$contenttype]) ? [$contents[$contenttype]] : [];
    }

    public function add($contenttype, $id, $quantity = 1)
    {
        $contents = $this->session->get($this->key);

        if(isset($contents[$contenttype][$id])) {
            $contents[$contenttype][$id] += $quantity;
        } else {
            $contents[$contenttype][$id] = $quantity;
        }

        return $this->session->set($this->key, $contents);
    }

    public function remove($contenttype, $id)
    {
        $contents = $this->session->get($this->key);

        if(isset($contents[$contenttype][$id])) {
            unset($contents[$contenttype][$id]);
        }

        if(empty($contents[$contenttype])) {
            unset($contents[$contenttype]);
        }

        return $this->session->set($this->key, $contents);
    }

    public function update($contenttype, $id, $quantity)
    {
        $contents = $this->session->get($this->key);

        if(isset($contents[$contenttype][$id])) {
            $contents[$contenttype][$id] = $quantity;
        } else {
            $contents[$contenttype][$id] = 1;
        }

        return $this->session->set($this->key, $contents);
    }

    public function reset()
    {
        $this->session->set($this->key, []);
    }
}