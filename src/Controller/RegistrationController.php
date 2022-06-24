<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Entity\User;
use App\Form\Type\CustomerFormType;
use App\Form\Type\UserFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MigratingSessionHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $req, ManagerRegistry $re, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();

        $formUser = $this->createForm(UserFormType::class, $user);

        $formUser->handleRequest($req);

        if ($formUser->isSubmitted() && $formUser->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formUser->get('password')->getData()
                )
            );
            $user->setRoles(['ROLE_USER']);

            $em = $re->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('more_information_register', [
                'id' => $user->getId()
            ]);
        }

        return $this->render('registration/index.html.twig', [
            'form_User' => $formUser->createView(),
        ]);
    }

    /**
     * @Route("/registration/moreinformation", name="more_information_register")
     */
    public function moreInformationAction(Request $req, ManagerRegistry $re, UserRepository $repo): Response
    {
        $customer = new Customers();
        $uid = $req->query->get('id');
        $user = $repo->find($uid);

        $formCus = $this->createForm(CustomerFormType::class, $customer);

        $formCus->handleRequest($req);

        if ($formCus->isSubmitted() && $formCus->isValid()) {
            $data = $formCus->getData($req);

            $customer->setFullname($data->getFullname());
            $customer->setSex($data->isSex());
            $customer->setTelephone($data->getTelephone());
            $customer->setAddress($data->getAddress());
            $customer->setBirthday($data->getBirthday());
            $customer->setUser($user);

            $em = $re->getManager();
            $em->persist($customer);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/moreinfo.html.twig', [
            'form_Cus' => $formCus->createView(),
        ]);
    }
}
