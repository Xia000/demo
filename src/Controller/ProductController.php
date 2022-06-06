<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $category = new Category();
        $category->setName('Category 1');


        $product = new Product();
        $product->setName('Product 1');
        $product->setPrice(10.00);
        $product->setDescription('Description 1');

        $product->setCategory($category);


        $entityManager = $doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->persist($product);
        $entityManager->flush();




        return new Response(
            'Saved new product with id: '.$product->getId()
            .' and new category with id: '.$category->getId()
        );
    }

    /**
     * @Route("/product/create", name="product_create")
     */
    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();
        $category->setName('Category 1');
        

        $product =  new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($product);
            $entityManager->flush();

            return new Response( 'Saved new product with id: '.$product->getId() );
        }

        return new Response(
            $twig->render('product/show.html.twig', [
                'product_form' => $form->createView(),
            ])
        );

    }
    
}
