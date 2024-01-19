<?php
namespace App\Controller;

use App\Entity\Filter;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\FilterType;

class PropertyController extends AbstractController
{

    private $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
     
        $filter = new Filter();
        $form = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($filter),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('property/index.html.twig', [
            'form' => $form->createview(),
            'current_menu' => 'properties',
            'properties' => $properties,
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
