<?php

namespace App\Controller\Admin;

use App\Entity\Option;
use App\Form\PropertyType;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(PropertyRepository $repository, ManagerRegistry $doctrine)
    {
        $this->repository = $repository;
        $this->doctrine = $doctrine;
    }


    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }


    public function edit($id, Request $request, CacheManager $cacheManager, UploaderHelper $helper)
    {
        $property = $this->repository->find($id);



        if (!$property) {
            throw $this->createNotFoundException('Property not found');
        }
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($property->getImage() instanceof UploadedFile) {
                $cacheManager->remove($helper->asset($property, 'image'));
            }
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'Modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->doctrine->getManager()->persist($property);
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'Créé avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }


    public function delete($id, Request $request)
    {
        $property = $this->repository->find($id);
        if (!$property) {
            throw $this->createNotFoundException('Property not found');
        }
        if ($this->isCsrfTokenValid('delete'.$property->getId(), $request->get('_token'))){
            $this->doctrine->getManager()->remove($property);
            $this->doctrine->getManager()->flush();
            $this->addFlash('success', 'Supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}


?>
