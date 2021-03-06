<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Establishment;
use App\Entity\Reservation;
use App\Entity\Room;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_GERANT')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hypnos');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Voir le site', 'fa fa-home', 'app_establishment_index');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class)
            ->setPermission('ROLE_GERANT');
        yield MenuItem::linkToCrud('√Čtablissements', 'fas fa-building', Establishment::class)
            ->setPermission('ROLE_GERANT');
        yield MenuItem::linkToCrud('Suite', 'fas fa-bed', Room::class)
            ->setPermission('ROLE_GERANT');
        yield MenuItem::linkToCrud('Reservation', 'fa fa-calendar', Reservation::class)
            ->setPermission('ROLE_GERANT');
    }
}
