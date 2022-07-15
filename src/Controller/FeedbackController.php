<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\Type\FeedbackFormType;
use App\Repository\CustomersRepository;
use App\Repository\FeedbackRepository;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class FeedbackController extends AbstractController
{
    /**
     * @Route("/givefeedback/{id}", name="give_feedback")
     */
    public function giveFeedbackAction(Request $req, ManagerRegistry $res, ProductsRepository $repoPro, CustomersRepository $repoCus, $id): Response
    {
        $feedback = new Feedback();
        $formFeedback = $this->createForm(FeedbackFormType::class, $feedback);

        $formFeedback->handleRequest($req);
        $entity =  $res->getManager();

        //Get Product entity
        $product = $repoPro->find($id);
        //Get user Customer
        $user = $this->getUser();
        //Get customer entity
        $customer = $repoCus->findOneBy(['user' => $user]);

        if ($formFeedback->isSubmitted() && $formFeedback->isValid()) {
            $data = $formFeedback->getData($req);

            $feedback->setCustomer($customer);
            $feedback->setProduct($product);
            $feedback->setContent($data->getContent());
            $feedback->setSendDate(new \DateTime());
            $feedback->setAllow(false);

            $entity->persist($feedback);
            $entity->flush();

            return $this->redirectToRoute("shop");
        }

        return $this->render('feedback/givefeedback.html.twig', [
            'form_Feedback' => $formFeedback->createView(),
            'product' => $product,
            'customer' => $customer,
        ]);
    }

    /**
     * @Route("/management/feedback", name="show_all_feedback")
     */
    public function indexFeedback(ManagerRegistry $res, Request $req, FeedbackRepository $repo, PaginatorInterface $paginator): Response
    {
        $feedbacks = $repo->showFeedback();
        $paginator = $paginator->paginate($feedbacks, $req->query->getInt('page', 1), 15); //create paginator

        if (isset($_POST['btnUpdateFeedback']) && $_POST['txtupdateFeed'] == 1) {
            $id = $req->request->get('txtFeedID');
            $feedback = $repo->find($id);

            $feedback->setAllow(false);

            $entity = $res->getManager();
            $entity->persist($feedback);
            $entity->flush();

            return $this->redirectToRoute("show_all_feedback");
        }
        if (isset($_POST['btnUpdateFeedback']) && $_POST['txtupdateFeed'] == 0) {
            $id = $req->request->get('txtFeedID');
            $feedback = $repo->find($id);

            $feedback->setAllow(true);

            $entity = $res->getManager();
            $entity->persist($feedback);
            $entity->flush();

            return $this->redirectToRoute("show_all_feedback");
        }

        return $this->render('feedback/feedback.html.twig', [
            'feedbacks' => $paginator
        ]);
    }

    /**
     * @Route("/management/feedback/delete/{id}", name="delete_feedback")
     */
    public function allowFeedbackAction(ManagerRegistry $res, FeedbackRepository $repo, int $id): Response
    {
        $feedback = $repo->find($id);

        $entity = $res->getManager();

        $entity->remove($feedback);
        $entity->flush();

        return $this->redirectToRoute("show_all_feedback");
    }
}
