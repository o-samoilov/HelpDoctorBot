<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    // ########################################

    /**
     * @Route("/",  methods={"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json([]);
    }

    // ########################################
}
