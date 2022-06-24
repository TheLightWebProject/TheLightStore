<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\Type\FeedbackFormType;
use App\Repository\CustomersRepository;
use App\Repository\FeedbackRepository;
use App\Repository\ProductsRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/givefeedback/{id}", name="give_feedback")
     */
    public function giveFeedbackAction(Request $req, ManagerRegistry $res, ProductsRepository $repoPro, UserRepository $repoUser, CustomersRepository $repoCus, AuthenticationUtils $authenticationUtils, $id): Response
    {
        $feedback = new Feedback();
        $formFeedback = $this->createForm(FeedbackFormType::class, $feedback);

        $formFeedback->handleRequest($req);
        $entity =  $res->getManager();

        //Get Product entity
        $product = $repoPro->find($id);

        //Get user Customer
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $repoUser->findBy(['email' => $lastUsername]);
        $customer = $repoCus->findOneBy(['user' => $user]);

        if ($formFeedback->isSubmitted() && $formFeedback->isValid()) {
            $data = $formFeedback->getData($req);

            $feedback->setCustomer($customer);
            $feedback->setProduct($product);
            $feedback->setContent($data->getContent());
            $feedback->setSendDate(new \DateTime());
            $feedback->setAllow(false);

            // $err = $valid->validate($suppliers);
            // if (count($err) > 0) {
            //     $string_err = (string)$err;
            //     return new Response($string_err, 400);
            // }

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
}
