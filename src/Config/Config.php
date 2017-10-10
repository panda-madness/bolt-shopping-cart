<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Config;

class Config {

    protected $config;

    /**
     * Config constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get()
    {
        return $this->config;
    }

    public function getProvider()
    {
        return $this->config['provider'];
    }

    public function getRoot()
    {
        return $this->config['root'];
    }

    /**
     * @param $key
     * @return string
     */
    public function getRedirectUrl($key)
    {
        return $this->config['redirects'][$key];
    }

    public function getSessionKey()
    {
        return (string)$this->config['session']['key'];
    }
}