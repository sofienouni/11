<?php

namespace App\Controller;

use App\Entity\Biens;
use App\Entity\BienSearch;
use App\Entity\Images;
use App\Entity\Messages;
use App\Entity\Prix;
use App\Entity\Textes;
use App\Entity\TypeBien;
use App\Entity\VentesSearch;
use App\Entity\Villes;
use App\Form\BienSearchType;
use App\Form\BiensType;
use App\Form\MessagesType;
use App\Form\PrixType;
use App\Form\TextesType;
use App\Form\TypeBienType;
use App\Form\VentesSearchType;
use App\Form\VilleType;
use App\Repository\BiensRepository;
use App\Repository\ImagesRepository;
use App\Repository\MessagesRepository;
use App\Repository\PrixRepository;
use App\Repository\TextesRepository;
use App\Repository\TypeBienRepository;
use App\Repository\VentesRepository;
use App\Repository\VillesRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/biens')]
class BiensController extends AbstractController
{
    #[Route('/', name: 'app_biens_index', methods: ['GET','POST'])]
    public function index(BiensRepository $biensRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $biensearch = new BienSearch();
        $form = $this->createForm(BienSearchType::class, $biensearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted()) {
            $SearchParams = $form->getData();
            $queryBuilder = $biensRepository->findAllFieldPaginated($SearchParams);
        }else{
            $queryBuilder = $biensRepository->findAllFieldPaginated();
        }

        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/index.html.twig', [
            'pager' => $pagerfanta,
            'form' => $form->createView(),
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

    #[Route('/type_bien', name: 'app_type_index', methods: ['GET'])]
    public function type_bien(TypeBienRepository $typeBienRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $type_bien = $typeBienRepository->findAll();
        return $this->render('biens/type_index.html.twig', [
            'type_bien' => $type_bien,
        ]);
    }

    #[Route('/texte', name: 'app_texte_index', methods: ['GET'])]
    public function texte(TextesRepository $textesRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $queryBuilder = $textesRepository->findAllFieldPaginated();
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            15
        );
        return $this->render('biens/texte_index.html.twig', [
            'pager' => $pagerfanta,
        ]);
    }

    #[Route('/vente', name: 'app_ventes_index', methods: ['GET','POST'])]
    public function ventes_index(VentesRepository $ventesRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $ventesearch = new VentesSearch();
        $form = $this->createForm(VentesSearchType::class, $ventesearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted()) {
            $SearchParams = $form->getData();
            $queryBuilder = $ventesRepository->findAllFieldPaginated($SearchParams);
        }else{
            $queryBuilder = $ventesRepository->findForPager();
        }
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/ventes_index.html.twig', [
            'ventes' => $pagerfanta,
            'form' => $form->createView()
        ]);
    }

    #[Route('/prix', name: 'app_prix_index', methods: ['GET','POST'])]
    public function prix_index(PrixRepository $prixRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $listes_prix_location = $prixRepository->findBy(['type' => 1],['min'=>'ASC']);
        $listes_prix_vente = $prixRepository->findBy(['type' => 0],['min'=>'ASC']);
        $prix = new Prix();
        $form = $this->createForm(PrixType::class, $prix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prixRepository->save($prix, true);
            return $this->redirectToRoute('app_prix_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('biens/prix_index.html.twig', [
            'listes_prix_location' => $listes_prix_location,
            'listes_prix_vente' => $listes_prix_vente,
            'form' => $form->createView()
        ]);
    }

    #[Route('/message', name: 'app_messages_index', methods: ['GET','POST'])]
    public function messages_index(MessagesRepository $messagesRepository, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $ventesearch = new VentesSearch();
        $form = $this->createForm(VentesSearchType::class, $ventesearch);
        $form->handleRequest($request);
        $SearchParams = null;
        if ($form->isSubmitted()) {
            $SearchParams = $form->getData();
            $queryBuilder = $messagesRepository->findAllFieldPaginated($SearchParams);
        }else{
            $queryBuilder = $messagesRepository->findForPager();
        }
        $adapter = new QueryAdapter($queryBuilder);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            10
        );
        return $this->render('biens/messages_index.html.twig', [
            'messages' => $pagerfanta,
            'form' => $form->createView()
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
            $exist = $villesRepository->verif_exist(['nom' => $ville->getNom()]);
            if (isset($exist) && !empty($exist)){
                $nom = $exist->getNom();
                $this->addFlash('Failed', "Une autre ville existe au nom de : $nom");
            }else{
                $villesRepository->save($ville, true);
            }
            return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new_ville.html.twig', [
            'bien' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/newPrix', name: 'app_prix_new', methods: ['GET', 'POST'])]
    public function new_prix(Request $request, PrixRepository $prixRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $prix = new Prix();
        $form = $this->createForm(PrixType::class, $prix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prixRepository->save($prix, true);
            return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new_prix.html.twig', [
            'bien' => $prix,
            'form' => $form,
        ]);
    }

    #[Route('/newType', name: 'app_type_new', methods: ['GET', 'POST'])]
    public function new_type(Request $request, TypeBienRepository $typeBienRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $type_bien = new TypeBien();
        $form = $this->createForm(TypeBienType::class, $type_bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeBienRepository->save($type_bien, true);


            return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/new_type.html.twig', [
            'type_bien' => $type_bien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_biens_show', methods: ['GET'])]
    public function show(Biens $bien, BiensRepository $biensRepository, MessagesRepository $messagesRepository, Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $messagesRepository->save($message, true);
            $this->addFlash('success', 'Votre message a été envoyer avec succes. Nous vous contacterons dans les plusbrefs délais.');
        }
        $biensearch = new BienSearch();
        $form_search = $this->createForm(BienSearchType::class, $biensearch);
        $form_search->handleRequest($request);
        $value['type'] = $bien->isType();
        $value['type_bien'] = $bien->getTypeBien();
        $value['current'] = $bien->getId();
        $biens = $biensRepository->findByExampleField($value);
        return $this->render('biens/show.html.twig', [
            'bien' => $bien,
            'biens' => $biens,
            'form' => $form->createView(),
            'form_search' => $form_search->createView()
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

    #[Route('/{id}/edit_type', name: 'app_type_edit', methods: ['GET', 'POST'])]
    public function edit_type(Request $request, TypeBien $typeBien, TypeBienRepository $typeBienRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(TypeBienType::class, $typeBien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $typeBienRepository->save($typeBien, true);

            return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/edit_type.html.twig', [
            'type_bien' => $typeBien,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit_texte', name: 'app_texte_edit', methods: ['GET', 'POST'])]
    public function edit_texte(Request $request, Textes $texte, TextesRepository $textesRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        $form = $this->createForm(TextesType::class, $texte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $textesRepository->save($texte, true);

            return $this->redirectToRoute('app_texte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('biens/edit_texte.html.twig', [
            'text' => $texte,
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
            if (isset($biens) and !empty($biens)){
                $referer = $request->headers->get('referer');
                $request->getSession()->getFlashBag()->add('notice', 'Cette Ville contient des Biens! Vous ne pouvez donc pas la supprimmer');
                return new RedirectResponse($referer);
            }else{
                $villesRepository->remove($ville, true);
            }
        }

        return $this->redirectToRoute('app_ville_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete_type/{id}', name: 'app_type_delete', methods: ['POST'])]
    public function delete_type(Request $request, TypeBien $typeBien, BiensRepository $biensRepository, TypeBienRepository $typeBienRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->isCsrfTokenValid('delete'.$typeBien->getId(), $request->request->get('_token'))) {
            $biens = $biensRepository->findBy(['ville'=>$typeBien]);
            if (isset($biens) and !empty($biens)){
                $referer = $request->headers->get('referer');
                $request->getSession()->getFlashBag()->add('notice', 'Ce Type contient des Biens! Vous ne pouvez donc pas le supprimmer');
                return new RedirectResponse($referer);
            }else{
                $typeBienRepository->remove($typeBien, true);
            }
        }

        return $this->redirectToRoute('app_type_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete_prix/{id}', name: 'app_prix_delete', methods: ['POST'])]
    public function delete_prix(Request $request, Prix $prix, PrixRepository $prixRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');
        if ($this->isCsrfTokenValid('delete'.$prix->getId(), $request->request->get('_token'))) {
            $prixRepository->remove($prix, true);
        }

        return $this->redirectToRoute('app_prix_index', [], Response::HTTP_SEE_OTHER);
    }
}
