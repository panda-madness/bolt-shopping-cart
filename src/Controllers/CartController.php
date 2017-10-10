<?php

namespace Bolt\Extension\PandaMadness\ShoppingCart\Controllers;


use Bolt\Controller\Base;
use Silex\ControllerCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Base {

    protected function addRoutes(ControllerCollection $c)
    {
        $c->match('/', [$this, 'show'])
            ->method('GET')
            ->bind('cart.show');
        $c->match('/add', [$this, 'add'])
            ->method('POST')
            ->bind('cart.add');
        $c->match('/remove', [$this, 'remove'])
            ->method('POST')
            ->bind('cart.remove');
        $c->match('/update', [$this, 'update'])
            ->method('POST')
            ->bind('cart.update');
        $c->match('/reset', [$this, 'reset'])
            ->method('POST')
            ->bind('cart.reset');
    }

    public function show(Request $request)
    {
        return new JsonResponse($this->app['cart.manager']->get()->contents());
    }

    public function add(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity', 1);

        $this->app['cart.manager']->add($contenttype, $id, $quantity);

        if($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'status' => 'success'
            ]);
        }

        $this->setFlash('cart_added');
        return $this->redirect($this->app['cart.config']->getRedirectUrl('add'));
    }

    public function remove(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');

        $this->app['cart.manager']->remove($contenttype, $id);

        if($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'status' => 'success'
            ]);
        }

        $this->setFlash('cart_removed');
        return $this->redirect($this->app['cart.config']->getRedirectUrl('remove'));
    }

    public function update(Request $request)
    {
        $contenttype = $request->request->get('contenttype');
        $id = $request->request->get('id');
        $quantity = $request->request->get('quantity');

        $this->app['cart.manager']->update($contenttype, $id, $quantity);

        if($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'status' => 'success'
            ]);
        }

        $this->setFlash('cart_updated');
        return $this->redirect($this->app['cart.config']->getRedirectUrl('update'));
    }

    public function reset(Request $request)
    {
        $this->app['cart.manager']->reset();

        $this->setFlash('cart_reset');

        if($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'status' => 'success'
            ]);
        }

        return $this->redirect($this->app['cart.config']->getRedirectUrl('reset'));
    }

    private function setFlash($key, $value = true) {
        $this->session()->getFlashBag()->set($key, $value);
    }
}