<?php

namespace App\Controller;

use App\Repository\BrandsRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends AbstractController
{
    /**
     * @Route("/homepage", name="index")
     */
    public function index(ProductsRepository $repo): Response
    {
        $products = $repo->showTop4BestSelling();

        return $this->render('view/content.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/shop", name="shop")
     */
    public function shopAction(Request $req, ProductsRepository $repo, BrandsRepository $repoBrand, PaginatorInterface $paginator): Response
    {
        $products = $repo->showShop();

        $brands = $repoBrand->findAll();

        $paginator = $paginator->paginate($products, $req->query->getInt('page', 1), 12);

        return $this->render('view/shop.html.twig', [
            'products' => $paginator,
            'brands' => $brands
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(): Response
    {
        return $this->render('view/about.html.twig');
    }

    /**
     * @Route("/viewdetail/{id}", name="view_detail_product")
     */
    public function viewDetail(ProductsRepository $repo, int $id): Response
    {
        $product = $repo->viewDetail($id);

        // return $this->json($product);
        return $this->render('view/viewdetail.html.twig', [
            'product' => $product
        ]);
    }
}
