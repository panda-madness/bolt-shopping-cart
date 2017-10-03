<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Controllers;


use Bolt\Controller\Base;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Base {

    protected function addRoutes(ControllerCollection $c)
    {
        $c->match('/add', [$this, 'add'])
            ->method('POST')
            ->bind('cart.api.add');
        $c->match('/remove', [$this, 'remove'])
            ->method('POST')
            ->bind('cart.api.remove');
        $c->match('/update', [$this, 'update'])
            ->method('POST')
            ->bind('cart.api.update');
        $c->match('/reset', [$this, 'reset'])
            ->method('POST')
            ->bind('cart.api.reset');
        $c->match('/fetch', [$this, 'fetch'])
            ->method('GET')
            ->bind('cart.api.fetch');
    }

    public function add(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity', 1);

        $this->app['cart.manager']->add($contenttype, $id, $quantity);

        return new JsonResponse(['response' => 200]);
    }

    public function remove(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');

        $this->app['cart.manager']->remove($contenttype, $id);

        return new JsonResponse(['response' => 200]);
    }

    public function update(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity');

        $this->app['cart.manager']->update($contenttype, $id, $quantity);

        return new JsonResponse(['response' => 200]);
    }

    public function reset()
    {
        $this->app['cart.manager']->reset();

        return new JsonResponse(['response' => 200]);
    }

    public function fetch()
    {
        $cart = $this->app['cart.manager']->get()->contents();

        return new JsonResponse(['response' => 200, 'contents' => $cart]);
    }
}