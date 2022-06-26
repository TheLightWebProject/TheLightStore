<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function cartAction(Request $req): Response
    {
        //Cart initialization
        if (!isset($_SESSION['cart_item'])) $_SESSION['cart_item'] = [];

        //Get information from form
        if (isset($_POST['addcart']) && ($_POST['addcart'])) {
            $id = $req->request->get('proid');
            $name = $req->request->get('proname');
            $short = $req->request->get('shortdesc');
            $image = $req->request->get('image');
            $quantity = $req->request->get('quantity');
            $price = $req->request->get('price');

            //Check whether or not the product has in the cart
            $fl = 0;
            //Check whether or not the product has been duplicated
            for ($i = 0; $i < sizeof($_SESSION['cart_item']); $i++) {
                if ($_SESSION['cart_item'][$i][1] == $name) {
                    $fl = 1;
                    $newqty = $quantity + $_SESSION['cart_item'][$i][4];
                    $_SESSION['cart_item'][$i][4] = $newqty;
                    break;
                }
            }

            if ($fl == 0) {
                $item = [$id, $name, $short, $image, $quantity, $price];
                $_SESSION['cart_item'][] = $item;
                //var_dump($_SESSION['cart_item']);
            }
        }
        return $this->render('cart/cart.html.twig', [
            'sessions' => $_SESSION['cart_item']
        ]);
    }

    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function removeCartAction(): Response
    {
        //Delete a product in cart
        if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart_item']) && isset($_SESSION['cart_item'][$_GET['remove']])) {
            // Remove the product from the shopping cart
            unset($_SESSION['cart_item'][$_GET['remove']]);
        }
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/clear", name="clear_cart")
     */
    public function clearCartAction(): Response
    {
        //Clear all cart
        if (isset($_GET['delcard']) && ($_GET['delcard'] == 1)) unset($_SESSION['cart_item']);

        return $this->redirectToRoute("cart");
    }
}
