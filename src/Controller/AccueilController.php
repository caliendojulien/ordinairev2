<?php

namespace App\Controller;

use App\Form\AjouterFType;
use App\Form\AjouterUType;
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
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Ce controller contient les function de la page d'accueil, l'affichage des effectif mangeant,
 * la page d'affichage des personnels et stagiaires, et la page pour ajouter personnels et formations
 */
#[Route('/', name: 'accueil')]
class AccueilController extends AbstractController
{
    /**
     * Cette fonction est celle de la page d'accueil (base.html.twig) elle renvoie simplement sur la page
     */
    #[Route('/', name: '_index')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * Cette fonction fait appelle à des requètes qui vont compté en base de donnée les mangeants, pour une date donnée,
     * le midi et le soir, et elle renvoie sur une page accessible seulement aux utilisateur ayant pour rôle ADMIN
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/affichage', name: '_affichage')]
    public function affichage(
        Request                $request,
        ReservationRepository  $repository
    ): Response
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

    /**
     * Cette fonction fait appelle à des requètes qui permettent d'afficher tout les utilisateurs et formations.
     * De plus la page qu'elle renvoie n'est accessible que par les utilisateurs ayant pour rôle SUPER_ADMIN
     */
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

    /**
     * Cette fonction fait appelle à des requètes qui permettent de supprimer un utilisateur.
     * Elle renvoie sur la même page que la function précedente et n'est accessible que pour le rôle SUPER_ADMIN
     */
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

    /**
     * Cette fonction fait appelle à des requètes qui permettent de supprimer une formation.
     * Elle renvoie sur la même page que la function précedente et n'est accessible que pour le rôle SUPER_ADMIN
     */
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

    /**
     * Cette fonction fait appelle à des requètes qui permettent l'ajout d'utilisateurs et de formations en BDD.
     * Elle renvoie sur une nouvelle page qui elle aussi n'est accessible que par le rôle SUPER_ADMIN
     */
    #[IsGranted("ROLE_SUPER_ADMIN")]
    #[Route('/ajouter', '_ajouter')]
    public function ajouterUtilisateurFormation(
        Request                     $request,
        EntityManagerInterface      $em,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        $utilisateur = new Utilisateur();
        $formation = new Formation();
        $utilisateurForm = $this->createForm(AjouterUType::class, $utilisateur);
        $formationForm = $this->createForm(AjouterFType::class, $formation);

        $utilisateurForm->handleRequest($request);
        $formationForm->handleRequest($request);

        if ($utilisateurForm->isSubmitted() && $utilisateurForm->isValid()) {
            $utilisateur->setRoles(['ROLE_USER']);
            $utilisateur->setPassword(
                $userPasswordHasher->hashPassword(
                    $utilisateur,
                    $utilisateurForm->get('password')->getData()
                )
            );
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
