<?php

namespace App\Controller;

use App\Entity\Suppliers;
use App\Form\Type\SupplierFormType;
use App\Repository\SuppliersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierController extends AbstractController
{
    /**
     * @Route("/supplier", name="show_all_supplier")
     */
    public function index(SuppliersRepository $repo): Response
    {
        $suppliers = $repo->findAll();

        return $this->render('supplier/index.html.twig', [
            'suppliers' => $suppliers
        ]);
    }

    /**
     * @Route("/supplier/new", name="add_supplier")
     */
    public function addAction(ManagerRegistry $res, Request $req, ValidatorInterface $valid): Response
    {
        $suppliers = new Suppliers();
        $formSupplier = $this->createForm(SupplierFormType::class, $suppliers);

        $formSupplier->handleRequest($req);
        $entity =  $res->getManager();

        if ($formSupplier->isSubmitted() && $formSupplier->isValid()) {
            $data = $formSupplier->getData($req);

            $suppliers->setName($data->getName());
            $suppliers->setTelephone($data->getTelephone());
            $suppliers->setEmail($data->getEmail());

            $err = $valid->validate($suppliers);
            if (count($err) > 0) {
                $string_err = (string)$err;
                return new Response($string_err, 400);
            }

            $entity->persist($suppliers);
            $entity->flush();

            return $this->redirectToRoute("show_all_supplier");
        }

        return $this->render('supplier/add.html.twig', [
            'form_Supplier' => $formSupplier->createView()
        ]);
    }

    /**
     * @Route("/supplier/edit/{id}", name="edit_supplier")
     */
    public function editAction(ManagerRegistry $res, Request $req, ValidatorInterface $valid, SuppliersRepository $repo, $id): Response
    {
        $suppliers = $repo->find($id);
        $formSupplier = $this->createForm(SupplierFormType::class, $suppliers);

        $formSupplier->handleRequest($req);
        $entity =  $res->getManager();

        if ($formSupplier->isSubmitted() && $formSupplier->isValid()) {
            $data = $formSupplier->getData($req);

            $suppliers->setName($data->getName());
            $suppliers->setTelephone($data->getTelephone());
            $suppliers->setEmail($data->getEmail());

            $err = $valid->validate($suppliers);
            if (count($err) > 0) {
                $string_err = (string)$err;
                return new Response($string_err, 400);
            }

            $entity->persist($suppliers);
            $entity->flush();

            return $this->redirectToRoute("show_all_supplier");
        }

        return $this->render('supplier/add.html.twig', [
            'form_Supplier' => $formSupplier->createView()
        ]);
    }

    /**
     * @Route("/supplier/delete/{id}", name="delete_supplier")
     */
    public function deleteSupplierAction(SuppliersRepository $repo, ManagerRegistry $res, int $id): Response
    {
        $suppliers = $repo->find($id);

        if (!$suppliers) {
            throw
            $this->createNotFoundException('Invalid ID' . $id);
        }

        $entity = $res->getManager();

        $entity->remove($suppliers);
        $entity->flush();

        return $this->redirectToRoute("show_all_supplier");
    }
}
