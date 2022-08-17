<?php

namespace App\Controller;

use App\Form\AjouterFType;
use App\Form\AjouterType;
use App\Repository\ReservationRepository;
use App\Entity\Formation;
use App\Entity\Utilisateur;
use App\Repository\FormationRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/affichage', name: '_affichage')]
    public function affichage(Request $request, EntityManagerInterface $entityManager, ReservationRepository $repository): Response
    {
        $dateCalendrier = $request->get("calendrier");
        $cptMidi = $repository->count(['midi' => 1, 'date' => new \DateTime($dateCalendrier)]);
        $cptSoir = $repository->count(['soir' => 1, 'date' => new \DateTime($dateCalendrier)]);


        return $this->render('affichageReservation.html.twig', [
                'cptMidi' => $cptMidi,
                'dateCalendrier' => $dateCalendrier,
                'cptSoir' => $cptSoir
            ]
        );
    }

    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route('/liste', '_listeUtilisateursFormations')]
    public function listeUtilisateurFormation(
        UtilisateurRepository $utilisateurRepository,
        FormationRepository   $formationRepository
    ): Response
    {
        $listeUtilisateurs = $utilisateurRepository->findAll();
        $listeFormations = $formationRepository->findAll();
        return $this->render('affichage_suppression_utilisateur_formation/affichageSuppresion.html.twig',
            compact('listeUtilisateurs', 'listeFormations'));
    }

    #[IsGranted("ROLE_SUPER_ADMIN")]
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

    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route('/supprimer/f/{formation}', '_supprimerF')]
    public function supprimerFormation(
        EntityManagerInterface $em,
        Formation              $formation,
    ): Response
    {
        $em->remove($formation);
        $em->flush();
        return $this->redirectToRoute('accueil_listeUtilisateursFormations');
    }

    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route('/ajouter', '_ajouter')]
    public function ajouterUtilisateurFormation(
        Request                $request,
        EntityManagerInterface $em
    ): Response
    {
        $utilisateur = new Utilisateur();
        $formation = new Formation();
        $utilisateurForm = $this->createForm(AjouterType::class, $utilisateur);
        $formationForm = $this->createForm(AjouterFType::class, $formation);
        $utilisateurForm->handleRequest($request);
        $formationForm->handleRequest($request);

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {
            $utilisateur->setRoles(['ROLE_USER']);
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirectToRoute('accueil_ajouter');
        }

        if ($formationForm->isSubmitted() && $formationForm->isValid()) {
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('accueil_ajouter');
        }

        return $this->renderForm('ajouter/ajouter.html.twig',
            compact('utilisateurForm', 'formationForm'));
    }
}
