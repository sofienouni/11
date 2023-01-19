<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\Images;
use App\Entity\Villes;
use App\Form\BiensType;
use App\Form\ProgramType;
use App\Form\VilleType;
use App\Repository\BiensRepository;
use App\Repository\ImagesRepository;
use App\Repository\MessagesRepository;
use App\Repository\VentesRepository;
use App\Repository\VillesRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/biens')]
class BiensController extends AbstractController
{
    #[Route('/', name: 'app_biens_index', methods: ['GET'])]
    public function index(BiensRepository $biensRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $queryBuilder = $biensRepository->findAllFieldPaginated();
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/index.html.twig', [
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/program', name: 'app_program_index', methods: ['GET'])]
    public function program(BiensRepository $biensRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $queryBuilder = $biensRepository->findAllFieldPaginated(null,true);
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/index.html.twig', [
            'pager' => $pagerfanta,
            'program' => true
        ]);
    }

    #[Route('/vente', name: 'app_ventes_index', methods: ['GET'])]
    public function ventes_index(VentesRepository $ventesRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $queryBuilder = $ventesRepository->findForPager();
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/ventes_index.html.twig', [
            'ventes' => $pagerfanta,
        ]);
    }

    #[Route('/message', name: 'app_messages_index', methods: ['GET'])]
    public function messages_index(MessagesRepository $messagesRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $queryBuilder = $messagesRepository->findForPager();
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/messages_index.html.twig', [
            'messages' => $pagerfanta,
        ]);
    }

    #[Route('/new', name: 'app_biens_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BiensRepository $biensRepository, SluggerInterface $slugger, ImagesRepository $imagesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $bien = new Biens();
        $form = $this->createForm(BiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $biensRepository->save($bien, true);
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
                    $image = new Images();
                    $image->setUrl($newFilename);
                    $image->setBien($bien);
                    $imagesRepository->save($image, true);
                }
            }


            return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/newProgramme', name: 'app_programme_new', methods: ['GET', 'POST'])]
    public function newProgramme(Request $request, BiensRepository $biensRepository, SluggerInterface $slugger, ImagesRepository $imagesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $bien = new Biens();
        $form = $this->createForm(ProgramType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $biensRepository->save($bien, true);
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
                    $image = new Images();
                    $image->setUrl($newFilename);
                    $image->setBien($bien);
                    $imagesRepository->save($image, true);
                }
            }


            return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new.html.twig', [
            'programme' => true,
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/newVille', name: 'app_ville_new', methods: ['GET', 'POST'])]
    public function new_ville(Request $request, VillesRepository $villesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $ville = new Villes();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $villesRepository->save($ville, true);


            return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new_ville.html.twig', [
            'bien' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_biens_show', methods: ['GET'])]
    public function show(Biens $bien): Response
    {
        return $this->render('biens/show.html.twig', [
            'bien' => $bien,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_biens_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Biens $bien, BiensRepository $biensRepository, SluggerInterface $slugger, ImagesRepository $imagesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(BiensType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $liste_images = $form->get('photo')->getData();
            if (!empty($liste_images)){
                $previews_images = $imagesRepository->findBy(
                    ['bien' => $bien]
                );
                foreach ($previews_images as $previews_image)
                    $imagesRepository->remove($previews_image);
            }
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
                    $image = new Images();
                    $image->setUrl($newFilename);
                    $image->setBien($bien);
                    $imagesRepository->save($image, true);
                }
            }
            $biensRepository->save($bien, true);

            return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/edit.html.twig', [
            'bien' => $bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_biens_delete', methods: ['POST'])]
    public function delete(Request $request, Biens $bien, BiensRepository $biensRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            $biensRepository->remove($bien, true);
        }

        return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
    }
}
