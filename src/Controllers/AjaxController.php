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
            ->method('POST');
        $c->match('/remove', [$this, 'remove'])
            ->method('POST');
        $c->match('/update', [$this, 'update'])
            ->method('POST');
        $c->match('/reset', [$this, 'reset'])
            ->method('POST');
        $c->match('/fetch', [$this, 'fetch'])
            ->method('get');
    }

    public function add(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity', 1);

        $this->app['cart']->add($contenttype, $id, $quantity);

        return new JsonResponse(['response' => 200]);
    }

    public function remove(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');

        $this->app['cart']->remove($contenttype, $id);

        return new JsonResponse(['response' => 200]);
    }

    public function update(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity');

        $this->app['cart']->update($contenttype, $id, $quantity);

        return new JsonResponse(['response' => 200]);
    }

    public function reset()
    {
        $this->app['cart']->reset();

        return new JsonResponse(['response' => 200]);
    }

    public function fetch()
    {
        $cart = $this->app['cart']->fetch();

        return new JsonResponse(['response' => 200, 'contents' => $cart]);
    }
}