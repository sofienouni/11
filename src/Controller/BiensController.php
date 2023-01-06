<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\Images;
use App\Form\BiensType;
use App\Repository\BiensRepository;
use App\Repository\ImagesRepository;
use App\Repository\VentesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/biens')]
class BiensController extends AbstractController
{
    #[Route('/', name: 'app_biens_index', methods: ['GET'])]
    public function index(BiensRepository $biensRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        return $this->render('biens/index.html.twig', [
            'biens' => $biensRepository->findBy(array(),null,10),
        ]);
    }

    #[Route('/vente', name: 'app_ventes_index', methods: ['GET'])]
    public function ventes_index(VentesRepository $ventesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        return $this->render('biens/ventes_index.html.twig', [
            'ventes' => $ventesRepository->findBy(array(),null,10),
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
