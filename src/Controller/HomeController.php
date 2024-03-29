<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\BienSearch;
use App\Entity\BiensearchParams;
use App\Entity\ClientImages;
use App\Entity\Images;
use App\Entity\Messages;
use App\Entity\Ventes;
use App\Entity\Villes;
use App\Form\BiensearchParamsType;
use App\Form\BienSearchType;
use App\Form\MessagesType;
use App\Form\VentesType;
use App\Repository\BiensRepository;
use App\Repository\ClientImagesRepository;
use App\Repository\MessagesRepository;
use App\Repository\PrixRepository;
use App\Repository\TextesRepository;
use App\Repository\TypeBienRepository;
use App\Repository\VentesRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{

    private $apiService;
    private $manager;
    private $requestStack;

    public function __construct(EntityManagerInterface $manager,RequestStack $requestStack)
    {
        $this->manager = $manager;
        $this->requestStack = $requestStack;
    }

    #[Route('/', name: 'app_home')]
    public function index(BiensRepository $biensRepository, TypeBienRepository $typeBienRepository, PrixRepository $prixRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $liste_location = $prixRepository->findBy(['type' => 1],['min'=>'ASC']);
        foreach ($liste_location as $liste){
            $listes_prix_location[$liste->getId()] = $liste->getMin(). ' - ' . $liste->getMax();
        }
        $liste_vente = $prixRepository->findBy(['type' => 0],['min'=>'ASC']);
        foreach ($liste_vente as $liste){
            $listes_prix_vente[] = $liste->getMin(). ' - ' . $liste->getMax();
        }
        $biensearch = new BienSearch();
        $form = $this->createForm(BienSearchType::class, $biensearch);
        $form->handleRequest($request);
        $session = $this->requestStack->getSession();
        $ids = $session->get('selection',[]);
        $SearchParams = null;
        $type_biens = $typeBienRepository->findAll();
        if (isset($type_biens[0]))
            $appartement = $type_biens[0];
        if (isset($type_biens[1]))
            $villa = $type_biens[1];
        if (isset($type_biens[2]))
            $terrain = $type_biens[2];
        if (isset($type_biens[3]))
            $commerce = $type_biens[3];
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
            $queryBuilder = $biensRepository->findAllFieldPaginated($SearchParams);
        }else{
            $vente = [];
            $location = [];
            $value['type'] = 0;
            $value['type_bien'] = $appartement;
            $vente['Appartement'] = $biensRepository->findByExampleField($value);
            $value['type'] = 1;
            $location['Appartement'] = $biensRepository->findByExampleField($value);
            $value['type'] = 0;
            $value['type_bien'] = $villa;
            $vente['Villa'] = $biensRepository->findByExampleField($value);
            $value['type'] = 1;
            $location['Villa'] = $biensRepository->findByExampleField($value);
            $value['type'] = 0;
            $value['type_bien'] = $terrain;
            $vente['Terrain'] = $biensRepository->findByExampleField($value);
            $value['type'] = 1;
            $location['Terrain'] = $biensRepository->findByExampleField($value);
            $value['type'] = 0;
            $value['type_bien'] = $commerce;
            $vente['Commerce'] = $biensRepository->findByExampleField($value);
            $value['type'] = 1;
            $location['Commerce'] = $biensRepository->findByExampleField($value);
            return $this->render('home/selection.html.twig', [
                'ventes' => $vente,
                'ids' => $ids,
                'location' => $location,
                'listes_prix_location' => $listes_prix_location,
                'listes_prix_vente' => $listes_prix_vente,
                'form' => $form->createView(),
            ]);
        }

        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );



        return $this->render('home/index.html.twig', [
            'villes' => $villes,
            'ids' => $ids,
            'listes_prix_location' => $listes_prix_location,
            'listes_prix_vente' => $listes_prix_vente,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('save_selection/{id}', name: 'app_selection_save', methods: ['GET'])]
    public function selection_save(Biens $bien): JsonResponse
    {
        $session = $this->requestStack->getSession();
        $ids = $session->get('selection',[]);
        $ids[$bien->getId()] = $bien->getId();
        $session->set('selection', $ids);
        return $this->json($ids);
    }

    #[Route('remove_selection/{id}', name: 'app_selection_remove', methods: ['GET'])]
    public function remove_selection(Biens $bien): JsonResponse
    {
        $session = $this->requestStack->getSession();
        $selection = $session->get('selection',[]);
        unset($selection[$bien->getId()]);
        $session->set('selection', $selection);
        $selection = $session->get('selection',[]);
        return $this->json($selection);
    }

    #[Route('/ma_selection', name: 'app_selection')]
    public function ma_selection(BiensRepository $biensRepository, Request $request): Response
    {

        $session = $this->requestStack->getSession();
        $selection = $session->get('selection',[]);
        $ma_selection = $biensRepository->findBy(array('id' => $selection));

        return $this->render('home/maselection.html.twig', [
            'biens' => $ma_selection,
        ]);
    }

    #[Route('/comparateur', name: 'app_comparateur')]
    public function comparateur(BiensRepository $biensRepository, Request $request): Response
    {

        $session = $this->requestStack->getSession();
        $selection = $session->get('selection',[]);
        $ma_selection = $biensRepository->findBy(array('id' => $selection));

        return $this->render('home/comparateur.html.twig', [
            'biens' => $ma_selection,
        ]);
    }


    #[Route('/acheter', name: 'app_acheter')]
    public function acheter(BiensRepository $biensRepository, PrixRepository $prixRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $liste_location = $prixRepository->findBy(['type' => 1],['min'=>'ASC']);
        foreach ($liste_location as $liste){
            $listes_prix_location[$liste->getId()] = $liste->getMin(). ' - ' . $liste->getMax();
        }
        $liste_vente = $prixRepository->findBy(['type' => 0],['min'=>'ASC']);
        foreach ($liste_vente as $liste){
            $listes_prix_vente[] = $liste->getMin(). ' - ' . $liste->getMax();
        }
        $vente = null;
        $biensearch = new BiensearchParams();
        $form = $this->createForm(BiensearchParamsType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        if (!isset($SearchParams) || $SearchParams->getType() === null)
            $vente = 0;

        $queryBuilder = $biensRepository->findAllFieldPaginatedwithparams($SearchParams,$vente);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        $session = $this->requestStack->getSession();
        $ids = $session->get('selection',[]);
        return $this->render('home/acheter.html.twig', [
            'villes' => $villes,
            'ids' => $ids,
            'listes_prix_location' => $listes_prix_location,
            'listes_prix_vente' => $listes_prix_vente,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/louer', name: 'app_louer')]
    public function louer(BiensRepository $biensRepository, PrixRepository $prixRepository, Request $request): Response
    {

        $villes = $this->manager->getRepository(Villes::class)->findAll();
        $liste_location = $prixRepository->findBy(['type' => 1],['min'=>'ASC']);
        foreach ($liste_location as $liste){
            $listes_prix_location[$liste->getId()] = $liste->getMin(). ' - ' . $liste->getMax();
        }
        $liste_vente = $prixRepository->findBy(['type' => 0],['min'=>'ASC']);
        foreach ($liste_vente as $liste){
            $listes_prix_vente[] = $liste->getMin(). ' - ' . $liste->getMax();
        }
        $vente = null;
        $biensearch = new BiensearchParams();
        $form = $this->createForm(BiensearchParamsType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $SearchParams = $form->getData();
        }
        if (!isset($SearchParams) || $SearchParams->getType() === null)
            $vente = 1;


        $queryBuilder = $biensRepository->findAllFieldPaginatedwithparams($SearchParams,$vente);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );
        $session = $this->requestStack->getSession();
        $ids = $session->get('selection',[]);
        return $this->render('home/acheter.html.twig', [
            'villes' => $villes,
            'ids' => $ids,
            'listes_prix_location' => $listes_prix_location,
            'listes_prix_vente' => $listes_prix_vente,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MessagesRepository $messagesRepository): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $messagesRepository->save($message, true);
            $this->addFlash('success', 'Votre message a été envoyer avec succes. Nous vous contacterons dans les plus brefs délais.');
            return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/agence', name: 'app_agence')]
    public function agence(BiensRepository $biensRepository,TextesRepository $textesRepository, Request $request): Response
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
        $textes = $textesRepository->findBy(['page' => 'Notre Agence']);
        return $this->render('home/agence.html.twig', [
            'villes' => $villes,
            'textes' => $textes,
            'form' => $form->createView(),
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/vendre', name: 'app_vendre')]
    public function vendre(VentesRepository $ventesRepository, ClientImagesRepository $clientImagesRepository ,TextesRepository $textesRepository, SluggerInterface $slugger, Request $request): Response
    {

        $vente = new Ventes();
        $form = $this->createForm(VentesType::class, $vente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DateTime();
            $vente->setDate($now);
            $ventesRepository->save($vente, true);
            $liste_images = $form->get('photo')->getData();
            foreach ($liste_images as $images){
                if ($images) {
                    $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $images->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $image = new ClientImages();
                    $image->setUrl($newFilename);
                    $image->setVente($vente);
                    $clientImagesRepository->save($image, true);
                }
            }
            $this->addFlash('success', 'Votre annonce a été déposer avec succes. Nous vous contacterons dans les plus brefs délais.');
            return $this->redirectToRoute('app_vendre', [], Response::HTTP_SEE_OTHER);
        }
        $textes = $textesRepository->findBy(['page' => 'vendre']);

        return $this->render('home/vendre.html.twig', [
            'form' => $form->createView(),
            'textes' => $textes
        ]);
    }

    #[Route('/annonce', name: 'app_annonce')]
    public function annonce(VentesRepository $ventesRepository, ClientImagesRepository $clientImagesRepository, TextesRepository $textesRepository, SluggerInterface $slugger, Request $request): Response
    {

        $vente = new Ventes();
        $form = $this->createForm(VentesType::class, $vente);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $now = new \DateTime();
            $vente->setDate($now);
            $ventesRepository->save($vente, true);
            $liste_images = $form->get('photo')->getData();
            foreach ($liste_images as $images){
                if ($images) {
                    $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $images->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $image = new ClientImages();
                    $image->setUrl($newFilename);
                    $image->setVente($vente);
                    $clientImagesRepository->save($image, true);
                }
            }
            $this->addFlash('success', 'Votre annonce a été déposer avec succes. Nous vous contacterons dans les plus brefs délais.');
            return $this->redirectToRoute('app_annonce', [], Response::HTTP_SEE_OTHER);
        }
        $textes = $textesRepository->findBy(['page' => ['Déposer Une Annonce','vendre']]);

        return $this->render('home/vendre.html.twig', [
            'form' => $form->createView(),
            'location' => true,
            'textes' => $textes
        ]);
    }
}
