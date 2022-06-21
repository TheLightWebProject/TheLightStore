<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Form\Type\CustomerFormType;
use App\Repository\CustomersRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CustomerController extends AbstractController
{
    /**
     * @Route("/profile/update", name="update_profile")
     */
    public function updateProfileAction(Request $req, ManagerRegistry $re, UserRepository $repoUser, CustomersRepository $repo, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $user = $repoUser->findBy(['email' => $lastUsername]);

        $customer = $repo->findOneBy(['user' => $user]);

        $formCus = $this->createForm(CustomerFormType::class, $customer);

        $formCus->handleRequest($req);

        if ($formCus->isSubmitted() && $formCus->isValid()) {
            $data = $formCus->getData($req);

            $customer->setFullname($data->getFullname());
            $customer->setSex($data->isSex());
            $customer->setTelephone($data->getTelephone());
            $customer->setAddress($data->getAddress());
            $customer->setBirthday($data->getBirthday());

            $em = $re->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('customer/update.html.twig', [
            'update_profile' => $formCus->createView(),
        ]);
    }

}
