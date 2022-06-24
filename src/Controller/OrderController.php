<?php

namespace App\Controller;

use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Repository\CustomersRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class OrderController extends AbstractController
{
    /**
     * @Route("/order/buynow", name="buynow")
     */
    public function orderAction(Request $req, ManagerRegistry $res, UserRepository $repoUser, CustomersRepository $repoCus, OrdersRepository $repoOrder, ProductsRepository $repoPro, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $repoUser->findBy(['email' => $lastUsername]);

        $customer = $repoCus->findOneBy(['user' => $user]);

        $product = [];

        $id = $req->request->get('proid');
        $name = $req->request->get('proname');
        $short = $req->request->get('shortdesc');
        $image = $req->request->get('image');
        $quantity = $req->request->get('quantity');
        $price = $req->request->get('price');

        if (isset($_POST["btnPaymentnow"])) {
            //add order
            $customerID = $repoCus->find($customer->getId()); //get entity customer

            $order = new Orders();
            $order->setOrderDate(new \DateTime());
            $order->setDeliveryDate(new \DateTime());
            $order->setChecked(false);
            $order->setUsername($customerID);

            $entity =  $res->getManager();
            $entity->persist($order);
            $entity->flush();

            //add order details
            $id = $req->request->get('proid');
            $quantity = $req->request->get('quantity');
            $price = $req->request->get('price');
            $product = $repoPro->find($id); //get entity product
            $orderID = $repoOrder->find($order->getId()); //get entity order

            $orderDetail = new OrderDetails();
            $orderDetail->setQuantity($quantity);
            $orderDetail->setTotalPrice($quantity * $price);
            $orderDetail->setOrders($orderID);
            $orderDetail->setProduct($product);

            $entity->persist($orderDetail);
            $entity->flush();

            //update product
            $product->setQuantity($product->getQuantity() - $quantity);
            $entity->persist($product);
            $entity->flush();

            $this->addFlash(
                'success',
                'Payment successfully'
            );

            return $this->redirectToRoute('cart');
        }

        $product = [$id, $name, $short, $image, $quantity, $price];

        return $this->render('order/buynow.html.twig', [
            'customer' => $customer,
            'product' => $product
        ]);
    }

    /**
     * @Route("/order/payment", name="payment")
     */
    public function paymentCartAction(Request $req, ManagerRegistry $res, UserRepository $repoUser, CustomersRepository $repoCus, OrdersRepository $repoOrder, ProductsRepository $repoPro, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $repoUser->findBy(['email' => $lastUsername]);

        $customer = $repoCus->findOneBy(['user' => $user]);

        if (isset($_POST["btnPayment"])) {
            //add order
            $customerID = $repoCus->find($customer->getId()); //get entity customer

            $order = new Orders();
            $order->setOrderDate(new \DateTime());
            $order->setDeliveryDate(new \DateTime());
            $order->setChecked(false);
            $order->setUsername($customerID);

            $entity =  $res->getManager();
            $entity->persist($order);
            $entity->flush();

            //add order details
            for ($item = 0; $item < sizeof($_SESSION['cart_item']); $item++) {
                $id = $_SESSION['cart_item'][$item][0];
                $quantity = $_SESSION['cart_item'][$item][4];
                $price = $_SESSION['cart_item'][$item][5];
                $allprice = $quantity * $price;

                $product = $repoPro->find($id); //get entity product
                $orderID = $repoOrder->find($order->getId()); //get entity order

                $orderDetail = new OrderDetails();
                $orderDetail->setQuantity($quantity);
                $orderDetail->setTotalPrice($allprice);
                $orderDetail->setOrders($orderID);
                $orderDetail->setProduct($product);

                $entity->persist($orderDetail);
                $entity->flush();

                //update product

                $product->setQuantity($product->getQuantity() - $quantity);
                $entity->persist($product);
                $entity->flush();
            }
            unset($_SESSION['cart_item']);

            $this->addFlash(
                'success',
                'Payment successfully'
            );

            return $this->redirectToRoute('cart');
        }

        return $this->render('order/payment.html.twig', [
            'customer' => $customer,
            'sessions' => $_SESSION['cart_item']
        ]);
    }
}
