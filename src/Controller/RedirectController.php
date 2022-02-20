<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LinkRepository;

class RedirectController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/{urlKey}", methods={"GET"}, requirements={"urlKey"="^(?!(admin/?|login/?|register/?|logout/?)).*"})
     */
    public function urlRedirect($urlKey, LinkRepository $linkRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $link = $linkRepository->findOneBy([
            'url_key' => $urlKey,
        ]);

        if(is_null($link)){
            throw $this->createNotFoundException();
        }

        $link->setVisits($link->getVisits()+1);
        $entityManager->flush();

        $originalUrl = $link->getOriginal();

        return $this->redirect($originalUrl);
    }
}
