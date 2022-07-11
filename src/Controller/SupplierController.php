<?php

namespace App\Controller;

use App\Entity\Suppliers;
use App\Form\Type\SupplierFormType;
use App\Repository\SuppliersRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierController extends AbstractController
{
    /**
     * @Route("/management/supplier", name="show_all_supplier")
     */
    public function indexSupplier(Request $req, SuppliersRepository $repo, PaginatorInterface $paginator): Response
    {
        if (isset($_POST['btnSearchSupplier'])) {
            $value = $req->request->get('txtSearchSupplier');
            $suppliers = $repo->findBySearchSupplier($value);
        } else {
            $suppliers = $repo->findAll();
        }
        $paginator = $paginator->paginate($suppliers, $req->query->getInt('page', 1), 15); //create paginator

        return $this->render('supplier/index.html.twig', [
            'suppliers' => $paginator
        ]);
    }

    /**
     * @Route("/management/supplier/new", name="add_supplier")
     */
    public function addSupplierAction(ManagerRegistry $res, Request $req): Response
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

            $entity->persist($suppliers);
            $entity->flush();

            $this->addFlash(
                'success',
                'New suppiler was added'
            );

            return $this->redirectToRoute("show_all_supplier");
        }

        return $this->render('supplier/add.html.twig', [
            'form_Supplier' => $formSupplier->createView()
        ]);
    }

    /**
     * @Route("/management/supplier/edit/{id}", name="edit_supplier")
     */
    public function editSupplierAction(ManagerRegistry $res, Request $req, SuppliersRepository $repo, $id): Response
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

            $entity->persist($suppliers);
            $entity->flush();

            $this->addFlash(
                'success',
                'Supplier was edited'
            );

            return $this->redirectToRoute("show_all_supplier");
        }

        return $this->render('supplier/edit.html.twig', [
            'form_Supplier' => $formSupplier->createView()
        ]);
    }

    /**
     * @Route("/management/supplier/delete/{id}", name="delete_supplier", methods={"DELETE"})
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

        return new JsonResponse();
        // return $this->redirectToRoute("show_all_supplier");
    }
}
