<?php
namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends AbstractController
{

    private $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('property/index.html.twig', [
        'current_menu' => 'properties'
        ]);
    }

    public function show($slug, $id): Response
    {
        $property = $this->repository->find($id);
        return $this->render('property/show.html.twig',[
            'property' => $property,
            'current_menu' => 'properties']);
    }


}
