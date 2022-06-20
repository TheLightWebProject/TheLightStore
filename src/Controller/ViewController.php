<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends AbstractController
{
    /**
     * @Route("/homepage", name="index")
     */
    public function index(): Response
    {
        return $this->render('view/content.html.twig');
    }

    /**
     * @Route("/shop", name="shop")
     */
    public function shopAction(): Response
    {
        return $this->render('view/shop.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction(): Response
    {
        return $this->render('view/about.html.twig');
    }
}
