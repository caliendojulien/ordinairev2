<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Utilisateur;
use App\Repository\FormationRepository;
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

    #[Route('/liste', '_listeUtilisateursFormations')]
    public function listeUtilisateurFormation(
        UtilisateurRepository  $utilisateurRepository,
        FormationRepository    $formationRepository
    ): Response
    {
        $listeUtilisateurs = $utilisateurRepository->findAll();
        $listeFormations = $formationRepository->findAll();
        return $this->render('affichage_suppression_utilisateur_formation/affichageSuppresion.html.twig',
            compact('listeUtilisateurs', 'listeFormations'));
    }

    #[Route('/supprimer/u/{utilisateur}', '_supprimerU')]
    public function supprimerUtilisateur(
        EntityManagerInterface $em,
        Utilisateur            $utilisateur,
    ): Response
    {
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirectToRoute('accueil_listeUtilisateursFormations');
    }

    #[Route('/supprimer/f/{formation}', '_supprimerF')]
    public function supprimerFormation(
        EntityManagerInterface $em,
        Formation            $formation,
    ): Response
    {
        $em->remove($formation);
        $em->flush();
        return $this->redirectToRoute('accueil_listeUtilisateursFormations');
    }


}
