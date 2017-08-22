<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Controllers;


use Bolt\Controller\Base;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Base {

    protected function addRoutes(ControllerCollection $c)
    {
        $c->match('/', [$this, 'show'])
            ->method('GET');
        $c->match('/add', [$this, 'add'])
            ->method('POST');
        $c->match('/remove', [$this, 'remove'])
            ->method('POST');
        $c->match('/update', [$this, 'update'])
            ->method('POST');
        $c->match('/reset', [$this, 'reset'])
            ->method('POST');
    }

    public function show()
    {
        return new Response(dump($this->app['cart']->fetch('articles')));
    }

    public function add(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity', 1);

        $this->app['cart']->add($contenttype, $id, $quantity);

        $this->setFlash('cart_added');

        return $this->redirect($this->app['cart.config']->getRedirectUrl('cart_added'));
    }

    public function remove(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');

        $this->app['cart']->remove($contenttype, $id);

        $this->setFlash('cart_removed');

        return $this->redirect($this->app['cart.config']->getRedirectUrl('cart_removed'));
    }

    public function update(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity');

        $this->app['cart']->update($contenttype, $id, $quantity);

        $this->setFlash('cart_updated');

        return $this->redirect($this->app['cart.config']->getRedirectUrl('cart_updated'));
    }

    public function reset()
    {
        $this->app['cart']->reset();

        $this->setFlash('cart_reset');

        return $this->redirect($this->app['cart.config']->getRedirectUrl('cart_reset'));
    }

    private function setFlash($key, $value = true) {
        $flashbag = $this->session()->getFlashBag();

        $flashbag->set($key, $value);
    }
}