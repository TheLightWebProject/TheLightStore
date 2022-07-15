<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Form\Type\CustomerFormType;
use App\Repository\CustomersRepository;
use App\Repository\OrderDetailsRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class CustomerController extends AbstractController
{
    /**
     * @Route("/customer/update", name="update_profile")
     */
    public function updateProfileAction(Request $req, ManagerRegistry $re, UserRepository $repoUser, CustomersRepository $repo): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $userEntity = $repoUser->find($user);

            if ($userEntity->isVerified() == 1) {
                $customer = $repo->findOneBy(['user' => $userEntity]);

                $formCus = $this->createForm(CustomerFormType::class, $customer);

                $formCus->handleRequest($req);

                if ($formCus->isSubmitted() && $formCus->isValid()) {
                    if ($userEntity != null && $customer != null) {
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
                        $addCustomer->setUser($userEntity);

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
                $this->addFlash(
                    'danger',
                    'You must be verify the account to see more'
                );
                return $this->redirectToRoute("shop");
            }
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
    public function changePasswordAction(UserRepository $repo, ManagerRegistry $re, Request $req, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $userEntity = $repo->find($user);
            if ($userEntity->isVerified() == 1) {
                if (isset($_POST['btnConfirm'])) {
                    $oldPass = $req->request->get('txtOldPass');
                    $newPass = $req->request->get('txtNewPass');
                    $confirmPass = $req->request->get('txtConfirmPass');
                    
                    if ($oldPass != "" || $newPass != "" || $confirmPass != "") {
                        if ($userPasswordHasher->isPasswordValid($user, $oldPass)) {
                            if ($newPass == $confirmPass) {
                                $userEntity->setPassword(
                                    $userPasswordHasher->hashPassword(
                                        $userEntity,
                                        $newPass
                                    )
                                );

                                $em = $re->getManager();
                                $em->persist($userEntity);
                                $em->flush();

                                $this->addFlash(
                                    'success',
                                    'Change password successfully'
                                );
                                return $this->redirectToRoute('change_password');
                            } else {
                                $this->addFlash(
                                    'danger',
                                    'Password and confirm password does not match'
                                );
                                return $this->redirectToRoute('change_password');
                            }
                        } else {
                            $this->addFlash(
                                'danger',
                                'Old password does not match'
                            );
                            return $this->redirectToRoute('change_password');
                        }
                    } else {
                        $this->addFlash(
                            'danger',
                            'Please do not leave fields blank'
                        );
                        return $this->redirectToRoute('change_password');
                    }
                }
                return $this->render('customer/changepassword.html.twig');
            } else {
                $this->addFlash(
                    'danger',
                    'You must be verify the account to see more'
                );
                return $this->redirectToRoute("shop");
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
     * @Route("/customer/ordered", name="product_ordered")
     */
    public function productOrderedAction(UserRepository $repoUser, CustomersRepository $repo): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $userEntity = $repoUser->find($user);

            if ($userEntity->isVerified() == 1) {
                $customerOrder = $repo->showProductOrdered($userEntity->getEmail());

                return $this->render('customer/productordered.html.twig', [
                    'product_ordered' => $customerOrder
                ]);
            } else {
                $this->addFlash(
                    'danger',
                    'You must be verify the account to see more'
                );
                return $this->redirectToRoute("shop");
            }
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
     * @Route("/customer/ordered/{id}", name="product_ordered_detail")
     */
    public function productOrderedDetailAction(UserRepository $repoUser, OrderDetailsRepository $repo, int $id): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->getUser();
            $userEntity = $repoUser->find($user);

            if ($userEntity->isVerified() == 1) {
                $customerOrderDetail = $repo->showProductOrderedDetail($id);

                return $this->render('customer/productodetail.html.twig', [
                    'product_ordered_detail' => $customerOrderDetail
                ]);
            } else {
                $this->addFlash(
                    'danger',
                    'You must be verify the account to see more'
                );
                return $this->redirectToRoute("shop");
            }
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
