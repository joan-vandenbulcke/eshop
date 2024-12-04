<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Form\CategoriesType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ProductController extends AbstractController
{

  #[Route('/product/add', name: 'app_product_add')]
  public function add(Request $request, EntityManagerInterface $entity): Response
  {

    $slugger = new AsciiSlugger();
    $product = new Products();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $product->setSlug($slugger->slug($form->get('name')->getData()));
      $entity->persist($product);
      $entity->flush();
      $this->addFlash('message', 'Produit ajouté avec succès');
      return $this->redirectToRoute('app_product');
    }

    // Formulaire pour catégories
    $category = new Categories();
    $formCategory = $this->createForm(CategoriesType::class, $category);
    $formCategory->handleRequest($request);

    if ($formCategory->isSubmitted() && $formCategory->isValid()) {
      $category->setSlug($slugger->slug($formCategory->get('name')->getData()));
      $entity->persist($category);
      $entity->flush();
      $this->addFlash('message', 'Catégorie ajouté avec succès');
      return $this->redirectToRoute('app_product_add');
    }

    return $this->render('product/add.html.twig', [
      'form' => $form->createView(),
      'formCategory' => $formCategory
    ]);
  }

  #[Route('/product/modif/{id}', name: 'app_product_modif')]
  public function modif($id, Request $request, EntityManagerInterface $entity): Response
  {

    $product = $entity->getRepository(Products::class)->find($id);
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $entity->persist($product);
      $entity->flush();
      $this->addFlash('message', 'Produit modifié avec succès');
      return $this->redirectToRoute('app_product');
    }

    // Formulaire pour catégories
    $category = $entity->getRepository(Categories::class)->find($id);
    $formCategory = $this->createForm(CategoriesType::class, $category);
    $formCategory->handleRequest($request);

    if ($formCategory->isSubmitted() && $formCategory->isValid()) {
      $entity->persist($category);
      $entity->flush();
      $this->addFlash('message', 'Catégorie modifiée avec succès');
      // return $this->redirectToRoute('app_product_add');
    }

    return $this->render('product/add.html.twig', [
      'form' => $form->createView(),
      'formCategory' => $formCategory
    ]);
  }

  #[Route('/product/delete/{id}', name: 'app_product_delete')]
  public function delete($id, Request $request, EntityManagerInterface $entity): Response
  {
    $product = $entity->getRepository(Products::class)->find($id);
    $entity->remove($product);
    $entity->flush();

    return $this->redirectToRoute('app_product');
  }

  #[Route('/product/deleteCategory/{id}', name: 'app_product_delete_category')]
  public function deleteCategory($id, Request $request, EntityManagerInterface $entity): Response
  {
    $category = $entity->getRepository(Categories::class)->find($id);
    $entity->remove($category);
    $entity->flush();

    return $this->redirectToRoute('app_main');
  }



  #[Route('/product/{id}', name: 'app_product')]
  public function index($id, Request $request, EntityManagerInterface $entity): Response
  {

    $product = $entity->getRepository(Products::class)->findBy(['category' => $id]);

    return $this->render('product/index.html.twig', [
      'products' => $product,
    ]);
  }
}
