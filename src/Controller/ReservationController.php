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
    #[IsGranted("ROLE_UTILISATEUR")]
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $numeroSemaine = new DateTime();
        $Lundi = strtotime('next monday +2 weeks');
        $dateLundi = date("d/m/y", $Lundi);
        $dateLundiFinal = DateTime::createFromFormat("d/m/y", $dateLundi);
        $getLundi = getdate($Lundi);
        $Mardi = strtotime('next monday +2 weeks +1 day');
        $dateMardi = date("d/m/y", $Mardi);
        $dateMardiFinal = DateTime::createFromFormat("d/m/y", $dateMardi);
        $Mercredi = strtotime('next monday +2 weeks +2 days');
        $dateMercredi = date("d/m/y", $Mercredi);
        $dateMercrediFinal = DateTime::createFromFormat("d/m/y", $dateMercredi);
        $Jeudi = strtotime('next monday +2 weeks +3 days');
        $dateJeudi = date("d/m/y", $Jeudi);
        $dateJeudiFinal = DateTime::createFromFormat("d/m/y", $dateJeudi);
        $Vendredi = strtotime('next monday +2 weeks +4 days');
        $dateVendredi = date("d/m/y", $Vendredi);
        $dateVendrediFinal = DateTime::createFromFormat("d/m/y", $dateVendredi);

        $reservationLundi = new Reservation();
        $formLundi = $this->createForm(ValidationRepasType::class, $reservationLundi);
        $formLundi->handleRequest($request);

        $reservationMardi = new Reservation();
        $formMardi = $this->createForm(ValidationRepasType::class, $reservationMardi);
        $formMardi->handleRequest($request);

        $reservationMercredi = new Reservation();
        $formMercredi = $this->createForm(ValidationRepasType::class, $reservationMercredi);
        $formMercredi->handleRequest($request);

        $reservationJeudi = new Reservation();
        $formJeudi = $this->createForm(ValidationRepasType::class, $reservationJeudi);
        $formJeudi->handleRequest($request);

        $reservationVendredi = new Reservation();
        $formVendredi = $this->createForm(ValidationRepasType::class, $reservationVendredi);
        $formVendredi->handleRequest($request);

        $toutEnvoyer = new Reservation();
        $form = $this->createForm(ValidationRepasType::class, $toutEnvoyer);
        $form->handleRequest($request);


        if ($request->request->get('lundiEnvoyer', '') == 'lundiEnvoyer') {
            if ($reservationLundi->isMidi()==null){
                $reservationLundi->setMidi(false);
            }
            if ($reservationLundi->isSoir()==null){
                $reservationLundi->setSoir(false);
            }

            $reservationLundi->setDate($dateLundiFinal);
            $em->persist($reservationLundi);
            $em->flush();

            }
        if ($request->request->get('mardiEnvoyer', '') == 'mardiEnvoyer') {
            if ($reservationMardi->isMidi()==null){
                $reservationMardi->setMidi(false);
            }
            if ($reservationMardi->isSoir()==null){
                $reservationMardi->setSoir(false);
            }

            $reservationMardi->setDate($dateMardiFinal);
            $em->persist($reservationMardi);
            $em->flush();

            }
        if ($request->request->get('mercrediEnvoyer', '') == 'mercrediEnvoyer') {
            if ($reservationMercredi->isMidi()==null){
                $reservationMercredi->setMidi(false);
            }
            if ($reservationMercredi->isSoir()==null){
                $reservationMercredi->setSoir(false);
            }

            $reservationMercredi->setDate($dateMercrediFinal);
            $em->persist($reservationMercredi);
            $em->flush();

        }
        if ($request->request->get('jeudiEnvoyer', '') == 'jeudiEnvoyer') {
            if ($reservationJeudi->isMidi()==null){
                $reservationJeudi->setMidi(false);
            }
            if ($reservationJeudi->isSoir()==null){
                $reservationJeudi->setSoir(false);
            }

            $reservationJeudi->setDate($dateJeudiFinal);
            $em->persist($reservationJeudi);
            $em->flush();

        }
        if ($request->request->get('vendrediEnvoyer', '') == 'vendrediEnvoyer') {
            if ($reservationVendredi->isMidi()==null){
                $reservationVendredi->setMidi(false);
            }
            if ($reservationVendredi->isSoir()==null){
                $reservationVendredi->setSoir(false);
            }

            $reservationVendredi->setDate($dateVendrediFinal);
            $em->persist($reservationVendredi);
            $em->flush();

        }
        if ($request->request->get('toutEnvoyer', '') == 'toutEnvoyer') {
            if ($reservationLundi->isMidi()==null){
                $reservationLundi->setMidi(false);
            }
            if ($reservationLundi->isSoir()==null){
                $reservationLundi->setSoir(false);
            }

            $reservationLundi->setDate($dateLundiFinal);
            $em->persist($reservationLundi);

            if ($reservationMardi->isMidi()==null){
                $reservationMardi->setMidi(false);
            }
            if ($reservationMardi->isSoir()==null){
                $reservationMardi->setSoir(false);
            }

            $reservationMardi->setDate($dateMardiFinal);
            $em->persist($reservationMardi);

            if ($reservationMercredi->isMidi()==null){
                $reservationMercredi->setMidi(false);
            }
            if ($reservationMercredi->isSoir()==null){
                $reservationMercredi->setSoir(false);
            }

            $reservationMercredi->setDate($dateMercrediFinal);
            $em->persist($reservationMercredi);

            if ($reservationJeudi->isMidi()==null){
                $reservationJeudi->setMidi(false);
            }
            if ($reservationJeudi->isSoir()==null){
                $reservationJeudi->setSoir(false);
            }

            $reservationJeudi->setDate($dateJeudiFinal);
            $em->persist($reservationJeudi);

            if ($reservationVendredi->isMidi()==null){
                $reservationVendredi->setMidi(false);
            }
            if ($reservationVendredi->isSoir()==null){
                $reservationVendredi->setSoir(false);
            }

            $reservationVendredi->setDate($dateVendrediFinal);
            $em->persist($reservationVendredi);

            $em->flush();

        }

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'numeroSemaine'=>$numeroSemaine,
            'Lundi'=>$Lundi,
            'Mardi'=>$Mardi,
            'Mercredi'=>$Mercredi,
            'Jeudi'=>$Jeudi,
            'Vendredi'=>$Vendredi,
            'dateLundi'=>$getLundi,
            'dateMardi'=>$dateMardiFinal,
            'dateMecredi'=>$dateMercrediFinal,
            'dateJeudi'=>$dateJeudiFinal,
            'dateVendredi'=>$dateVendrediFinal,

            'formValidationRepasLundi'=> $formLundi->createView(),
            'formValidationRepasMardi'=> $formMardi->createView(),
            'formValidationRepasMercredi'=> $formMercredi->createView(),
            'formValidationRepasJeudi'=> $formMercredi->createView(),
            'formValidationRepasVendredi'=> $formMercredi->createView(),
            'formValidationTout'=> $form->createView(),

        ]);
    }

}
