<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Genre;
use App\Entity\Nationalite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(LivreCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Librairie web');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Livres', 'fa fa-book');
        yield MenuItem::linkToCrud('Auteurs', 'fas fa-feather', Auteur::class);
        yield MenuItem::linkToCrud('Editeurs', 'fas fa-marker', Editeur::class);
        yield MenuItem::linkToCrud('Genres', 'fas fa-list', Genre::class);
        yield MenuItem::linkToCrud('Nationalités', 'fas fa-flag', Nationalite::class);
    }
}
