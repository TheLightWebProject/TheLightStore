<?php

namespace App\Controller;

use App\Repository\CustomersRepository;
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
    public function orderAction(Request $req, ManagerRegistry $re, UserRepository $repoUser, CustomersRepository $repoCus, AuthenticationUtils $authenticationUtils): Response
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

        $product = [$id, $name, $short, $image, $quantity, $price];

        return $this->render('order/index.html.twig', [
            'customer' => $customer,
            'product' => $product
        ]);
    }
}
