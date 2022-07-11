<?php

namespace App\Controller;

use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Form\Type\OrderFormType;
use App\Repository\CustomersRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class OrderController extends AbstractController
{
    /**
     * @Route("/order/buynow", name="buynow")
     */
    public function orderAction(Request $req, ManagerRegistry $res, CustomersRepository $repoCus, OrdersRepository $repoOrder, ProductsRepository $repoPro): Response
    {
        //Get user Customer
        $user = $this->getUser();
        //Get customer entity
        $customer = $repoCus->findOneBy(['user' => $user]);

        $id = $req->request->get('proid');
        $name = $req->request->get('proname');
        $short = $req->request->get('shortdesc');
        $image = $req->request->get('image');
        $quantity = $req->request->get('quantity');
        $price = $req->request->get('price');

        if (isset($_POST["btnPaymentnow"])) {
            $custName = $req->request->get('txtFullname');
            $custAddress = $req->request->get('txtAddress');
            $custPhone = $req->request->get('txtPhonenumber');
            $totalprice = $req->request->get('totalprice');
            //add order
            $customerID = $repoCus->find($customer->getId()); //get entity customer

            $order = new Orders();
            $order->setOrderDate(new \DateTime());
            $order->setDeliveryDate(new \DateTime());
            $order->setChecked(false);
            $order->setUsername($customerID);
            $order->setDeliveryLocal($custAddress);
            $order->setCustName($custName);
            $order->setCustPhone($custPhone);
            $order->setTotalPrice($totalprice);

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
    public function paymentCartAction(ManagerRegistry $res, Request $req, CustomersRepository $repoCus, OrdersRepository $repoOrder, ProductsRepository $repoPro): Response
    {
        //Get user Customer
        $user = $this->getUser();
        //Get customer entity
        $customer = $repoCus->findOneBy(['user' => $user]);

        if (isset($_POST["btnPayment"])) {
            $custName = $req->request->get('txtFullname');
            $custAddress = $req->request->get('txtAddress');
            $custPhone = $req->request->get('txtPhonenumber');
            $totalprice = $req->request->get('totalprice');
            //add order
            $customerID = $repoCus->find($customer->getId()); //get entity customer

            $order = new Orders();
            $order->setOrderDate(new \DateTime());
            $order->setDeliveryDate(new \DateTime());
            $order->setChecked(false);
            $order->setUsername($customerID);
            $order->setDeliveryLocal($custAddress);
            $order->setCustName($custName);
            $order->setCustPhone($custPhone);
            $order->setTotalPrice($totalprice);

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

    /**
     * @Route("/management/order", name="show_all_order")
     */
    public function indexOrder(OrdersRepository $repo, Request $req, ManagerRegistry $res, PaginatorInterface $paginator): Response
    {
        if (isset($_POST['btnchecked']) && $_POST['check'] == 1) {
            $id = $req->request->get('txtido');
            $order = $repo->find($id);

            $order->setChecked(false);

            $entity = $res->getManager();
            $entity->persist($order);
            $entity->flush();

            return $this->redirectToRoute("show_all_order");
        }

        if (isset($_POST['btnchecked']) && $_POST['check'] == 0) {
            $id = $req->request->get('txtido');
            $order = $repo->find($id);

            $order->setDeliveryDate(new \DateTime());
            $order->setChecked(true);

            $entity = $res->getManager();
            $entity->persist($order);
            $entity->flush();

            return $this->redirectToRoute("show_all_order");
        }

        if (isset($_POST['btnSearchOrder'])) {
            $year = $req->request->get('slYear');
            $month = $req->request->get('slMonth');
            $day = $req->request->get('slDay');

            $orders = $repo->findDateOfOrder($year, $month, $day);
        } else {
            $orders = $repo->showAllOrder();
        }

        $paginator = $paginator->paginate($orders, $req->query->getInt('page', 1), 15); //create paginator

        return $this->render('order/order.html.twig', [
            'orders' => $paginator
        ]);
    }

    /**
     * @Route("/management/order/export", name="export")
     */
    public function exportFileAction(OrdersRepository $repo, Request $request): Response
    {
        $orders = $repo->showAllOrder();
        // $orders = json_decode($request->getContent());
        // $orders = json_encode($request->request->all());
        return $this->json(['property'=> $orders]);

        // $spreadsheet = new Spreadsheet();

        // /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        // $sheet = $spreadsheet->getActiveSheet();
        // $sheet->setCellValue('A1', 'Order Date');
        // $sheet->setCellValue('B1', 'Delivery date');
        // $sheet->setCellValue('C1', 'Customer Name');
        // $sheet->setCellValue('D1', 'Telephone');
        // $sheet->setCellValue('E1', 'Address');
        // $sheet->setCellValue('F1', 'Total Price');

        // $count = 2;

        // foreach ((array) $orders as $row) {
        //     $sheet->setCellValue('A' . $count, $row["orderDate"]);
        //     $sheet->setCellValue('B' . $count, $row["deliveryDate"]);
        //     $sheet->setCellValue('C' . $count, $row["custName"]);
        //     $sheet->setCellValue('D' . $count, $row["custPhone"]);
        //     $sheet->setCellValue('E' . $count, $row["deliveryLocal"]);
        //     $sheet->setCellValue('F' . $count, $row["totalPrice"]);

        //     $count = $count + 1;
        // }

        // $sheet->setTitle("Statistical");

        // // Create your Office 2007 Excel (XLSX Format)
        // $writer = new Xlsx($spreadsheet);

        // // Create a Temporary file in the system
        // $fileName = 'export_to_excel.xlsx';
        // $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // // Create the excel file in the tmp directory of the system
        // $writer->save($temp_file);

        // // Return the excel file as an attachment
        // return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    /**
     * @Route("/management/order/delete/{id}", name="delete_order")
     */
    public function deleteOrderAction(OrdersRepository $repo, ManagerRegistry $res, int $id): Response
    {
        $orders = $repo->find($id);

        $entity = $res->getManager();

        $entity->remove($orders);
        $entity->flush();

        return $this->redirectToRoute("show_all_order");
    }
}
