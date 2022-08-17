<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ValidationRepasType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $numeroSemaine = new DateTime();
        $Lundi = strtotime('next monday +2 weeks');
        $Mardi = strtotime('next monday +2 weeks +1 day');
        $Mercredi = strtotime('next monday +2 weeks +2 days');
        $Jeudi = strtotime('next monday +2 weeks +3 days');
        $Vendredi = strtotime('next monday +2 weeks +4 days');

        $LundiPlus = strtotime('next monday +3 weeks');
        $MardiPlus = strtotime('next monday +3 weeks +1 day');
        $MercrediPlus = strtotime('next monday +3 weeks +2 days');
        $JeudiPlus = strtotime('next monday +3 weeks +3 days');
        $VendrediPlus = strtotime('next monday +3 weeks +4 days');

        $reservation = new Reservation();
        $form = $this->createForm(ValidationRepasType::class, $reservation);
        $form->handleRequest($request);
        dump($reservation);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($reservation);
            $em->flush();

            $this->addFlash('succes','Repas valider merci à vous !!!');

        }



        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'numeroSemaine'=>$numeroSemaine,
            'Lundi'=>$Lundi,
            'Mardi'=>$Mardi,
            'Mercredi'=>$Mercredi,
            'Jeudi'=>$Jeudi,
            'Vendredi'=>$Vendredi,
            'LundiPlus'=>$LundiPlus,
            'MardiPlus'=>$MardiPlus,
            'MercrediPlus'=>$MercrediPlus,
            'JeudiPlus'=>$JeudiPlus,
            'VendrediPlus'=>$VendrediPlus,

            'formValidationRepas'=> $form->createView()
        ]);
    }

}
