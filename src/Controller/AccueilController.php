<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/affichage', name: '_affichage')]
    public function affichage(Request $request,EntityManagerInterface $entityManager, ReservationRepository $repository): Response
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


}
