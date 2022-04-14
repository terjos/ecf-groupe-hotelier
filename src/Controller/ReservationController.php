<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_CLIENT')]
    public function new(Request $request, ReservationRepository $reservationRepository, RoomRepository $roomRepository): Response
    {
        $reservation = new Reservation();
        $reservation->setUser($this->getUser());
        if ($request->get('suite')) {
            $reservation->setRoom($roomRepository->find($request->get('suite')));
        }
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            $this->addFlash('success', 'Votre réservation a bien été enregistrée.');
            return $this->redirectToRoute('app_room_show', ['id' => $reservation->getRoom()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/new/validate', name: 'app_reservation_new_validator', methods: ['POST'])]
    #[IsGranted('ROLE_CLIENT')]
    public function newValidator(Request $request): Response
    {
        $reservation = new Reservation();
        $reservation->setUser($this->getUser());
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            $data = [
                'status' => 'Error',
                'errors' => $errors
            ];
            return new JsonResponse($data, 400);
        }

        return new JsonResponse(['status' => 'OK'], 200);
    }

    // #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    // public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
    //         $reservationRepository->remove($reservation);
    //     }

    //     return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    // }
}
