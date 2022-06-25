<?php

namespace App\Controller;

use App\Entity\Brands;
use App\Form\Type\BrandFormType;
use App\Repository\BrandsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

date_default_timezone_set('Asia/Ho_Chi_Minh');
class BrandController extends AbstractController
{
    /**
     * @Route("/management/brand", name="show_all_brands", methods={"GET"})
     */
    public function indexBrand(BrandsRepository $repo): Response
    {
        $brands = $repo->findAll();

        return $this->render('brand/index.html.twig', [
            'brands' => $brands
        ]);
    }

    /**
     * @Route("/management/brand/new", name="add_brand")
     */
    public function addBrandAction(ManagerRegistry $res, Request $req, SluggerInterface $slugger, ValidatorInterface $valid): Response
    {   
        $brand = new Brands();
        $formBrand = $this->createForm(BrandFormType::class, $brand);

        $formBrand->handleRequest($req);
        $entity = $res->getManager();

        if ($formBrand->isSubmitted() && $formBrand->isValid()) {
            $data = $formBrand->getData($req);

            $brand->setName($data->getName());
            $brand->setDecrip($data->getDecrip());

            $imgFile = $formBrand->get('image')->getData();

            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile->guessExtension();

                try {
                    $imgFile->move(
                        $this->getParameter('image_brand'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo $e;
                }
                $brand->setImage($newFilename);
            }

            // $err = $valid->validate($brand);
            // if (count($err) > 0) {
            //     $string_err = (string)$err;
            //     return new Response($string_err, 400);
            // }

            $entity->persist($brand);
            $entity->flush();

            $this->addFlash(
                'success',
                'New brand was added'
            );

            return $this->redirectToRoute("show_all_brands");
        }

        return $this->render('brand/add.html.twig', [
            'form_Brand' => $formBrand->createView()
        ]);
    }

    /**
     * @Route("/management/brand/getphoto/{filename}", name="get_brand_photo")
     */
    public function getBrandPhoto($filename): Response
    {
        $file = $this->getParameter('image_brand') . '/' . $filename;
        $response = new Response();
        $response->headers->set('Content-Type', 'image/jpg');
        $response->setContent(file_get_contents($file));
        return $response;
    }

    // public function tranform(Request $re)
    // {
    //     $data = json_decode($re->getContent(), true);
    //     if ($data === null) {
    //         return $re;
    //     }

    //     $re->request->replace($data);
    //     return $re;
    // }

    /**
     * @Route("/management/brand/edit/{id}", name="edit_brand")
     */
    public function editBrandAction(BrandsRepository $repo, ManagerRegistry $res, Request $req, int $id, SluggerInterface $slugger): Response
    {
        $brand = $repo->find($id);
        $formBrand = $this->createForm(BrandFormType::class, $brand);

        $formBrand->handleRequest($req);
        $entity = $res->getManager();

        if ($formBrand->isSubmitted() && $formBrand->isValid()) {
            $data = $formBrand->getData($req);

            $brand->setName($data->getName());
            $brand->setDecrip($data->getDecrip());

            $imgFile = $formBrand->get('image')->getData();

            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile->guessExtension();

                try {
                    $imgFile->move(
                        $this->getParameter('image_brand'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo $e;
                }
                $brand->setImage($newFilename);
            }

            $entity->persist($brand);
            $entity->flush();

            $this->addFlash(
                'success',
                'Brand was edited'
            );

            return $this->redirectToRoute("show_all_brands");
        }

        return $this->render('brand/edit.html.twig', [
            'form_Brand' => $formBrand->createView()
        ]);
    }

    /**
     * @Route("/management/brand/delete/{id}", name="delete_brand")
     */
    public function deleteBrandAction(BrandsRepository $repo, ManagerRegistry $res, int $id): Response
    {
        $brand = $repo->find($id);

        if (!$brand) {
            throw
            $this->createNotFoundException('Invalid ID' . $id);
        }

        $entity = $res->getManager();

        $entity->remove($brand);
        $entity->flush();

        $filePath = $brand->getImage();
        $file = $this->getParameter('image_brand') . '/' . $filePath;
        unlink($file);

        $this->addFlash(
            'success',
            'Brand was deleted'
        );

        return $this->redirectToRoute("show_all_brands");
    }
}
