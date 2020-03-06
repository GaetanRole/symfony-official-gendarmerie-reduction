<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin", name="app_admin_")
 * @IsGranted("ROLE_ADMIN")
 * @author  Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
final class AdminController extends AbstractController
{
    /**
     * @todo    Add all useful data for admin index.
     *
     * @Route("/dashboard", name="dashboard", methods={"GET"})
     */
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
}
