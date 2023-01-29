<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\Images;
use App\Entity\Villes;
use App\Form\BiensType;
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

    #[Route('/ville', name: 'app_ville_index', methods: ['GET'])]
    public function ville(VillesRepository $villesRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $queryBuilder = $villesRepository->findAllFieldPaginated();
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/ville_index.html.twig', [
            'pager' => $pagerfanta,
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


    #[Route('/newVille', name: 'app_ville_new', methods: ['GET', 'POST'])]
    public function new_ville(Request $request, VillesRepository $villesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $ville = new Villes();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $villesRepository->save($ville, true);


            return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new_ville.html.twig', [
            'bien' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_biens_show', methods: ['GET'])]
    public function show(Biens $bien, BiensRepository $biensRepository): Response
    {
        $value['type'] = $bien->isType();
        $value['type_bien'] = $bien->getTypeBien();
        $biens = $biensRepository->findByExampleField($value);
        return $this->render('biens/show.html.twig', [
            'bien' => $bien,
            'biens' => $biens
        ]);
    }


    #[Route('/{id}/edit_ville', name: 'app_ville_edit', methods: ['GET', 'POST'])]
    public function edit_ville(Request $request, Villes $ville, VillesRepository $villesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $villesRepository->save($ville, true);

            return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/edit_ville.html.twig', [
            'ville' => $ville,
            'form' => $form,
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
                ini_set('upload_max_filesize', '4M');

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
            foreach ($bien->getImages() as $image){
                if (file_exists($this->getParameter('images_directory').'/'.$image->getUrl())){
                    unlink($this->getParameter('images_directory').'/'.$image->getUrl());
                }
            }
            $biensRepository->remove($bien, true);
        }

        return $this->redirectToRoute('app_biens_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/delete/{id}', name: 'app_ville_delete', methods: ['POST'])]
    public function delete_ville(Request $request, Villes $ville, BiensRepository $biensRepository, VillesRepository $villesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $biens = $biensRepository->findBy(['ville'=>$ville]);
            foreach ($biens as $bien){
                $biensRepository->remove($bien);
            }
            $villesRepository->remove($ville, true);
        }

        return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
    }
}
