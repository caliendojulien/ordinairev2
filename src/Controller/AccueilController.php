<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'accueil')]
class AccueilController extends AbstractController
{
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/liste', '_listeUtilisateurs')]
    public function listeUtilisateurFormation(
        EntityManagerInterface $em,
        UtilisateurRepository  $utilisateurRepository
    ): Response
    {
        $listeUtilisateurs = $utilisateurRepository->findAll();
        return $this->render('',
            compact('listeUtilisateurs'));
    }



    #[Route('/supprimer/{utilisateur}', '_supprimer')]
    public function supprimerUtilisateurFormation(
        int                    $id,
        EntityManagerInterface $em,
        Utilisateur            $utilisateur,
        UtilisateurRepository  $utilisateurRepository
    ): Response
    {
        $utilisateurID = $utilisateurRepository->find($id);
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute('');
    }
}
