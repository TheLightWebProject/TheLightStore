<?php

namespace App\Controller;

use App\Repository\OrderDetailsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderDetailController extends AbstractController
{
    /**
     * @Route("/orderdetail/{id}", name="order_detail")
     */
    public function showAllOrderDetail(OrderDetailsRepository $repo, int $id): Response
    {
        $orderDetail = $repo->showAllOrderDetail($id);

        return $this->render('order_detail/orderdetail.html.twig', [
            'orderDetails' => $orderDetail
        ]);
    }

    /**
     * @Route("/orderdetail/delete/{id}", name="delele_oreder_detail")
     */
    public function deleteOrderDetailAction(OrderDetailsRepository $repo, ManagerRegistry $res, int $id): Response
    {
        $orderDetail = $repo->find($id);

        // if (!$orders) {
        //     throw
        //     $this->createNotFoundException('Invalid ID' . $id);
        // }

        $entity = $res->getManager();

        $entity->remove($orderDetail);
        $entity->flush();

        return $this->redirectToRoute("show_all_order");
    }
}
