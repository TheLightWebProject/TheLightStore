<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartDetails;
use App\Repository\CartDetailsRepository;
use App\Repository\CartRepository;
use App\Repository\CustomersRepository;
use App\Repository\FeedbackRepository;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
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
    public function cartAction(Request $req, ManagerRegistry $res, CustomersRepository $repoCus, ProductsRepository $repoPro, CartRepository $repoCart, CartDetailsRepository $repoCartDetail, FeedbackRepository $repoFeed): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $customer = $repoCus->findOneBy(['user' => $user]);
            $cart = $repoCart->findOneBy(['customer' => $customer]);
            $cartDetails = $repoCartDetail->showCartDetails($customer->getId());

            //Get information from form
            if (isset($_POST['addcart']) && ($_POST['addcart'])) {
                $id = $req->request->get('proid');
                $product = $repoPro->find($id);
                $quantity = $req->request->get('quantity');

                if ($cart == null) {
                    $cartEntity = new Cart();
                    $cartEntity->setCustomer($customer);
                    $cartEntity->setTotalPrice($product->getPrice() * $quantity);

                    $entity = $res->getManager();
                    $entity->persist($cartEntity);
                    $entity->flush();

                    $cartDetailEntity = new CartDetails();
                    $cartDetailEntity->setCart($repoCart->find($cartEntity->getId()));
                    $cartDetailEntity->setProducts($product);
                    $cartDetailEntity->setQuantity($quantity);
                    $cartDetailEntity->setTotalPrice($product->getPrice() * $quantity);

                    $entity = $res->getManager();
                    $entity->persist($cartDetailEntity);
                    $entity->flush();
                } else {
                    $cartEntity = $repoCart->find($cart->getId());
                    $cartDetailTemp = $repoCartDetail->findBy(['cart' => $cartEntity]);

                    foreach ($cartDetailTemp as $cartDetail) {
                        if ($cartDetail->getProducts() == $product) {
                            $newqty = $cartDetail->getQuantity() + $quantity;
                            //check whether or not the purchase quantity is greater than the inventory quantity
                            if ($newqty > $product->getQuantity()) {
                                $this->addFlash(
                                    'danger',
                                    'The purchase quantity is greater than the inventory quantity'
                                );
                                return $this->redirectToRoute("cart");
                            } else {
                                //Update product existed
                                $cartDetail->setQuantity($newqty);
                                $cartDetail->setTotalPrice($newqty * $product->getPrice());

                                $entity = $res->getManager();
                                $entity->persist($cartDetail);

                                //Update total price of cart
                                $cartEntity->setTotalPrice($cartEntity->getTotalPrice() + ($quantity * $product->getPrice()));
                                
                                $entity->persist($cartEntity);
                                $entity->flush();

                                $this->addFlash(
                                    'success',
                                    'Add to cart successfully'
                                );
                                return $this->render('view/viewdetail.html.twig', [
                                    'product' => $repoPro->viewDetail($id),
                                    'show_Feeds' => $repoFeed->allowDisplayFeedback($id),
                                ]);
                            }
                        }
                    }
                    //Add new cart detail
                    $cartDetailEntity = new CartDetails();
                    $cartDetailEntity->setCart($cartEntity);
                    $cartDetailEntity->setProducts($product);
                    $cartDetailEntity->setQuantity($quantity);
                    $cartDetailEntity->setTotalPrice($product->getPrice() * $quantity);

                    $entity = $res->getManager();
                    $entity->persist($cartDetailEntity);
                    $entity->flush();

                    //Update total price of cart
                    $cartEntity->setTotalPrice($cartEntity->getTotalPrice() + $cartDetailEntity->getTotalPrice());
                    $entity = $res->getManager();
                    $entity->persist($cartEntity);
                    $entity->flush();
                }
                $this->addFlash(
                    'success',
                    'Add to cart successfully'
                );
                return $this->render('view/viewdetail.html.twig', [
                    'product' => $repoPro->viewDetail($id),
                    'show_Feeds' => $repoFeed->allowDisplayFeedback($id),
                ]);
            } else {
                return $this->render('cart/cart.html.twig', [
                    'cart' => $cart,
                    'cartDetails' => $cartDetails
                ]);
            }
        } else {
            $this->addFlash(
                'danger',
                'You must be login to access this page'
            );
            return $this->redirectToRoute("app_login");
        }
    }

    /**
     * @Route("/cart/remove", name="remove_cart")
     */
    public function removeCartAction(Request $req, ManagerRegistry $res, CartRepository $repoCart, CartDetailsRepository $repo): Response
    {
        //Delete a product in cart
        if (isset($_GET['id'])) {
            $id = $req->query->get('id');
            $cartDetail = $repo->find($id);

            $entity = $res->getManager();
            $entity->remove($cartDetail);
            $entity->flush();

            $cart = $repoCart->find($cartDetail->getCart());
            $cart->setTotalPrice($cart->getTotalPrice() - $cartDetail->getTotalPrice());

            $entity = $res->getManager();
            $entity->persist($cart);
            $entity->flush();
        }
        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/clear", name="clear_cart")
     */
    public function clearCartAction(Request $req, ManagerRegistry $res, CartRepository $repo): Response
    {
        //Clear all cart
        if (isset($_GET['id'])) {
            $id = $req->query->get('id');
            $cart = $repo->find($id);

            $entity = $res->getManager();
            $entity->remove($cart);
            $entity->flush();
            return $this->redirectToRoute("cart");
        }
    }
}
