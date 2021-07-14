<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\UsedProduct;
use App\Form\UsedProductType;
use App\Repository\UsedProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/group")
 */
class UsedProductController extends AbstractController
{
    /**
     * @Route("/{slug}/products/", name="used_product_index", methods={"GET","POST"})
     */
    public function index(Group $group): Response
    {

        return $this->render('used_product/index.html.twig', [
            'used_products' => $group->getUsedProducts(),
            'group' => $group,
        ]);
    }

    /**
     * @Route("/{slug}/products/new", name="used_product_new", methods={"GET","POST"})
     */
    public function new(Request $request, Group $group): Response
    {
        $usedProduct = new UsedProduct();
        $form = $this->createForm(UsedProductType::class, $usedProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->get('product')->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usedProduct);
            $usedProduct->setOwner($group);
            $usedProduct->setWeight($product->getWeight());
            $usedProduct->setCapacity($product->getCapacity());
            $entityManager->flush();

            return $this->redirectToRoute('used_product_index', ['slug'=>$group->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('used_product/new.html.twig', [
            'used_product' => $usedProduct,
            'form' => $form,
        ]);
    }

//    /**
//     * @Route("/{id}", name="used_product_show", methods={"GET"})
//     */
//    public function show(UsedProduct $usedProduct): Response
//    {
//        return $this->render('used_product/show.html.twig', [
//            'used_product' => $usedProduct,
//        ]);
//    }

    /**
     * @Route("/{slug}/product/{id}/edit", name="used_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UsedProduct $usedProduct): Response
    {
        $form = $this->createForm(UsedProductType::class, $usedProduct);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('used_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('used_product/edit.html.twig', [
            'used_product' => $usedProduct,
            'form' => $form,

        ]);
    }

    /**
     * @Route("/{slug}/product/{id}/", name="used_product_delete", methods={"POST"})
     */
    public function delete(Request $request, UsedProduct $usedProduct): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usedProduct->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usedProduct);
            $entityManager->flush();
        }

        return $this->redirectToRoute('used_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
