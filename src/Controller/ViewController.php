<?php

namespace App\Controller;

use App\Repository\BrandsRepository;
use App\Repository\FeedbackRepository;
use App\Repository\ProductsRepository;
use App\Repository\SuppliersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
    public function shopAction(Request $req, ProductsRepository $repo, BrandsRepository $repoBrand, SuppliersRepository $repoSup, PaginatorInterface $paginator): Response
    {
        $suppliers = $repoSup->findAll();
        $brands = $repoBrand->findAll();

        if (isset($_GET['bid'])) {
            $id = $req->query->get('bid');
            $products = $repo->findBy(['brand' => $id]);
        } elseif (isset($_GET['sid'])) {
            $id = $req->query->get('sid');
            $products = $repo->findBy(['supplier' => $id]);
        } elseif (isset($_POST['btnSearch'])) {
            $value = $req->request->get('txtSearch');

            $keywords = explode(' ', $value);
            $searchTermKeywords = array();

            foreach ($keywords as $word) {
                $searchTermKeywords[] = "name LIKE '%$word%'";
            }

            $products = $repo->findBySearch($searchTermKeywords);
        } else {
            $products = $repo->showShop();
        }

        $paginator = $paginator->paginate($products, $req->query->getInt('page', 1), 18);

        return $this->render('view/shop.html.twig', [
            'products' => $paginator,
            'brands' => $brands,
            'suppliers' => $suppliers,
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
    public function viewDetail(ProductsRepository $repo, FeedbackRepository $repoFeed, int $id): Response
    {
        if (!$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY')) {
            $product = $repo->viewDetail($id);

            $showFeedback = $repoFeed->allowDisplayFeedback($id);

            // return $this->json($product);
            return $this->render('view/viewdetail.html.twig', [
                'product' => $product,
                'show_Feeds' => $showFeedback
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
     * @Route("/management", name="index_management")
     */
    public function indexManagementAction(): Response
    {
        return $this->render('view/management.html.twig');
    }

    // public function adminDashboard(): Response
    // {
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    //     return $this->render('security/login.html.twig');
    // }
}
