<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\Type\ProductFormType;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class ProductController extends AbstractController
{
    /**
     * @Route("/management/product", name="show_all_product")
     */
    public function indexProduct(Request $req, ProductsRepository $repo, PaginatorInterface $paginator): Response
    {
        if (isset($_POST['btnSearchProduct'])) {
            $value = $req->request->get('txtSearchProduct');

            $keywords = explode(' ', $value);
            $searchTermKeywords = array();

            foreach ($keywords as $word) {
                $searchTermKeywords[] = "p.name LIKE '%$word%'";
            }

            $products = $repo->findBySearchProduct($searchTermKeywords);
        } else {
            $products = $repo->showAllProduct();
        }

        $paginator = $paginator->paginate($products, $req->query->getInt('page', 1), 15); //create paginator

        return $this->render('product/index.html.twig', [
            'products' => $paginator
        ]);
    }

    /**
     * @Route("/management/product/new", name="add_product")
     */
    public function addProductAction(ManagerRegistry $res, Request $req, SluggerInterface $slugger): Response
    {
        $products = new Products();
        $formProduct = $this->createForm(ProductFormType::class, $products);

        $formProduct->handleRequest($req);
        $entity = $res->getManager();

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $data = $formProduct->getData($req);

            $products->setName($data->getName());
            $products->setPrice($data->getPrice());
            $products->setSmallDesc($data->getSmallDesc());
            $products->setDetailDesc($data->getDetailDesc());
            $products->setCreatedDate(new \DateTime());
            $products->setQuantity($data->getQuantity());
            $products->setBrand($data->getBrand());
            $products->setSupplier($data->getSupplier());

            $imgFile = $formProduct->get('image')->getData();

            if ($imgFile && $imgFile != "") {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile->guessExtension();

                try {
                    $imgFile->move(
                        $this->getParameter('image_product'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo $e;
                }
                $products->setImage($newFilename);
                $entity->persist($products);
                $entity->flush();

                $this->addFlash(
                    'success',
                    'New product was added'
                );

                return $this->redirectToRoute("show_all_product");
            } else {
                $this->addFlash(
                    'danger',
                    'Please choose image'
                );

                return $this->redirectToRoute("add_product");
            }
        }

        return $this->render('product/add.html.twig', [
            'form_Product' => $formProduct->createView()
        ]);
    }

    /**
     * @Route("/product/getphoto/{filename}", name="get_product_photo")
     */
    public function getProductPhoto($filename): Response
    {
        $file = $this->getParameter('image_product') . '/' . $filename;
        $response = new Response();
        $response->headers->set('Content-Type', 'image/jpg');
        $response->setContent(file_get_contents($file));
        return $response;
    }

    /**
     * @Route("/management/product/edit/{id}", name="edit_product")
     */
    public function editProductAction(ProductsRepository $repo, ManagerRegistry $res, Request $req, SluggerInterface $slugger, int $id): Response
    {
        $products = $repo->find($id);
        $formProduct = $this->createForm(ProductFormType::class, $products);

        $formProduct->handleRequest($req);
        $entity = $res->getManager();

        if ($formProduct->isSubmitted() && $formProduct->isValid()) {
            $data = $formProduct->getData($req);

            $products->setName($data->getName());
            $products->setPrice($data->getPrice());
            $products->setSmallDesc($data->getSmallDesc());
            $products->setDetailDesc($data->getDetailDesc());
            $products->setCreatedDate(new \DateTime());
            $products->setQuantity($data->getQuantity());
            $products->setBrand($data->getBrand());
            $products->setSupplier($data->getSupplier());

            $imgFile = $formProduct->get('image')->getData();

            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile->guessExtension();

                try {
                    $imgFile->move(
                        $this->getParameter('image_product'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo $e;
                }
                $products->setImage($newFilename);
            }

            $entity->persist($products);
            $entity->flush();

            $this->addFlash(
                'success',
                'Product was edited'
            );


            return $this->redirectToRoute("show_all_product");
        }

        return $this->render('product/edit.html.twig', [
            'form_Product' => $formProduct->createView()
        ]);
    }

    /**
     * @Route("/management/product/delete/{id}", name="delete_product", methods={"DELETE"})
     */
    public function deleteProductAction(ProductsRepository $repo, ManagerRegistry $res, int $id): Response
    {
        $product = $repo->find($id);

        if (!$product) {
            throw
            $this->createNotFoundException('Invalid ID ' . $id);
        }

        $entity = $res->getManager();

        $entity->remove($product);
        $entity->flush();

        $filePath = $product->getImage();
        $file = $this->getParameter('image_product') . '/' . $filePath;
        unlink($file);

        return new JsonResponse();
        // return $this->redirectToRoute("show_all_product");
    }
}
