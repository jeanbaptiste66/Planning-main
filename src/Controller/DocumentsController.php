<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/documents')]
class DocumentsController extends AbstractController
{
    #[Route('/', name: 'app_documents_index', methods: ['GET'])]
    public function index(DocumentsRepository $documentsRepository): Response
    {
        return $this->render('documents/index.html.twig', [
            'documents' => $documentsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_documents_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentsRepository $documentsRepository, SluggerInterface $slugger): Response
    {
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //upload du fichier
            $pdf = $form->get("fichier")->getData();
        
            if( $pdf ){
                $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($document->getCentre());
                $newFilename = $safeFilename.'-'.$originalFilename.uniqid().'.'.$pdf->guessExtension();

                try {
                    $pdf->move(
                        $this->getParameter('documents_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $document->setNom($newFilename);
                $document->setCreatedAt(new \DateTimeImmutable());
                $document->setUser($this->getUser());
            }
         
            $documentsRepository->save($document, true);

            return $this->redirectToRoute('app_documents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('documents/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_documents_show', methods: ['GET'])]
    public function show(Documents $document): Response
    {
        return $this->render('documents/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_documents_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Documents $document, DocumentsRepository $documentsRepository): Response
    {
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentsRepository->save($document, true);

            return $this->redirectToRoute('app_documents_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('documents/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_documents_delete', methods: ['POST'])]
    public function delete(Request $request, Documents $document, DocumentsRepository $documentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {  
            
          //  if ( file_exists($this -> getParameter ('documents_directory') .  "/" . $document -> getNom ()){
                unlink($this->getParameter('documents_directory') ."/". $document -> getNom() );
          //  }

            $documentsRepository->remove($document, true);
        }

        return $this->redirectToRoute('app_documents_index', [], Response::HTTP_SEE_OTHER);
    }
}
