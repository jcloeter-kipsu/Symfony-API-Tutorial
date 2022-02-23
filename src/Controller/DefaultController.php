<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/")
 */

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index")
     */
    public function index(){
        $data=['action' => 'index', 'time'=>time()];

        return new JsonResponse($data);
    }
}