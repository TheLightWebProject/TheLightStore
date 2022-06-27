<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Form\Type\ChangePasswordFormType;
use App\Form\Type\CustomerFormType;
use App\Repository\CustomersRepository;
use App\Repository\OrdersRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class CustomerController extends AbstractController
{
    /**
     * @Route("/customer/update", name="update_profile")
     */
    public function updateProfileAction(Request $req, ManagerRegistry $re, UserRepository $repoUser, CustomersRepository $repo, AuthenticationUtils $authenticationUtils): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $lastUsername = $authenticationUtils->getLastUsername();
            $user = $repoUser->findOneBy(['email' => $lastUsername]);

            $customer = $repo->findOneBy(['user' => $user]);

            $formCus = $this->createForm(CustomerFormType::class, $customer);

            $formCus->handleRequest($req);

            if ($formCus->isSubmitted() && $formCus->isValid()) {
                if ($user != null && $customer != null) {
                    $data = $formCus->getData($req);

                    $customer->setFullname($data->getFullname());
                    $customer->setSex($data->isSex());
                    $customer->setTelephone($data->getTelephone());
                    $customer->setAddress($data->getAddress());
                    $customer->setBirthday($data->getBirthday());

                    $em = $re->getManager();
                    $em->persist($customer);
                    $em->flush();

                    $this->addFlash(
                        'success',
                        'Update profile successfully'
                    );

                    return $this->redirectToRoute('update_profile');
                } else {
                    $addCustomer = new Customers();
                    $data = $formCus->getData($req);

                    $addCustomer->setFullname($data->getFullname());
                    $addCustomer->setSex($data->isSex());
                    $addCustomer->setTelephone($data->getTelephone());
                    $addCustomer->setAddress($data->getAddress());
                    $addCustomer->setBirthday($data->getBirthday());
                    $addCustomer->setUser($user);

                    $em = $re->getManager();
                    $em->persist($addCustomer);
                    $em->flush();

                    $this->addFlash(
                        'success',
                        'Add information successfully'
                    );

                    return $this->redirectToRoute('update_profile');
                }
            }

            return $this->render('customer/update.html.twig', [
                'update_profile' => $formCus->createView(),
            ]);
        } else {
            // $this->addFlash(
            //     'danger',
            //     'You must be login to access this page'
            // );
            // return $this->redirectToRoute("app_login");
            $error = "You must be login to access this page";
            return $this->render('security/login.html.twig', [
                'error' => $error
            ]);
        }
    }

    /**
     * @Route("/customer/changepassword", name="change_password")
     */
    public function changePasswordAction(ManagerRegistry $re, Request $req, UserRepository $repo, UserPasswordHasherInterface $userPasswordHasher, AuthenticationUtils $authenticationUtils): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $lastUsername = $authenticationUtils->getLastUsername();

            $user = $repo->findOneBy(['email' => $lastUsername]);

            $formChange = $this->createForm(ChangePasswordFormType::class, $user);
            $formChange->handleRequest($req);
            if ($formChange->isSubmitted() && $formChange->isValid()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $formChange->get('password')->getData()
                    )
                );

                $em = $re->getManager();
                $em->persist($user);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Change password successfully'
                );

                return $this->redirectToRoute('change_password');
            }

            return $this->render('customer/changepassword.html.twig', [
                'change_password_form' => $formChange->createView(),
            ]);
        } else {
            // $this->addFlash(
            //     'danger',
            //     'You must be login to access this page'
            // );
            // return $this->redirectToRoute("app_login");
            $error = "You must be login to access this page";
            return $this->render('security/login.html.twig', [
                'error' => $error
            ]);
        }
    }

    /**
     * @Route("/customer/ordered", name="product_ordered")
     */
    public function productOrderedAction(CustomersRepository $repo, OrdersRepository $repoOrder, AuthenticationUtils $authenticationUtils): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $lastUsername = $authenticationUtils->getLastUsername();

            $customerOrder = $repo->findProductOrdered($lastUsername);

            return $this->render('customer/productordered.html.twig', [
                'product_ordered' => $customerOrder
            ]);
        } else {
            // $this->addFlash(
            //     'danger',
            //     'You must be login to access this page'
            // );
            // return $this->redirectToRoute("app_login");
            $error = "You must be login to access this page";
            return $this->render('security/login.html.twig', [
                'error' => $error
            ]);
        }
    }
}
