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

    public function getPathRoot()
    {
        return $this->config['url'];
    }

    public function getContenttypes()
    {
        return $this->config['contenttypes'];
    }

    /**
     * @param $key
     * @return string
     */
    public function getRedirectUrl($key)
    {
        return $this->config['redirects'][$key];
    }
}