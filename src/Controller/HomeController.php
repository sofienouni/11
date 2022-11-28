<?php

namespace App\Controller;

use App\Entity\BienSearch;
use App\Entity\BiensearchParams;
use App\Entity\Villes;
use App\Form\BiensearchParamsType;
use App\Form\BienSearchType;
use App\Repository\BiensRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $apiService;
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/', name: 'app_home')]
    public function index(BiensRepository $biensRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $biensearch = new BienSearch();
        $form = $this->createForm(BienSearchType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        $queryBuilder = $biensRepository->findAllFieldPaginated($SearchParams);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );

        return $this->render('home/index.html.twig', [
            'villes' => $villes,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/acheter', name: 'app_acheter')]
    public function acheter(BiensRepository $biensRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $biensearch = new BiensearchParams();
        $form = $this->createForm(BiensearchParamsType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        $vente = 0;

        $queryBuilder = $biensRepository->findAllFieldPaginatedwithparams($SearchParams,$vente);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        return $this->render('home/acheter.html.twig', [
            'villes' => $villes,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/louer', name: 'app_louer')]
    public function louer(BiensRepository $biensRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $biensearch = new BiensearchParams();
        $form = $this->createForm(BiensearchParamsType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        $vente = 1;

        $queryBuilder = $biensRepository->findAllFieldPaginatedwithparams($SearchParams,$vente);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        return $this->render('home/acheter.html.twig', [
            'villes' => $villes,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(BiensRepository $biensRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $biensearch = new BiensearchParams();
        $form = $this->createForm(BiensearchParamsType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        $vente = 1;

        $queryBuilder = $biensRepository->findAllFieldPaginatedwithparams($SearchParams,$vente);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        return $this->render('home/contact.html.twig', [
            'villes' => $villes,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }


    #[Route('/agence', name: 'app_agence')]
    public function agence(BiensRepository $biensRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $biensearch = new BiensearchParams();
        $form = $this->createForm(BiensearchParamsType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        $vente = 1;

        $queryBuilder = $biensRepository->findAllFieldPaginatedwithparams($SearchParams,$vente);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        return $this->render('home/agence.html.twig', [
            'villes' => $villes,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/vendre', name: 'app_vendre')]
    public function vendre(BiensRepository $biensRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $biensearch = new BiensearchParams();
        $form = $this->createForm(BiensearchParamsType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        $vente = 1;

        $queryBuilder = $biensRepository->findAllFieldPaginatedwithparams($SearchParams,$vente);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        return $this->render('home/vendre.html.twig', [
            'villes' => $villes,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }
}
