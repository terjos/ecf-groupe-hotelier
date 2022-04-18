<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    #[IsGranted('ROLE_CLIENT')]
    public function index(ReservationRepository $resaRepository): Response
    {
        $user = $this->getUser();
        $reservations = $resaRepository->findByUser(['user' => $user]);

        return $this->render('account/index.html.twig', [
            'user' => $user,
            'reservations' => $reservations,
        ]);
    }
}
