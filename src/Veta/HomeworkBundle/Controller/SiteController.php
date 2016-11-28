<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SiteController extends Controller
{

    /**
     * @Route("/site/contacts", name="contacts")
     * @return Response
     */
    public function contactsAction()
    {
        $response = new Response();
        $response->setContent("<h1>Contacts</h1>");
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * @Route("/site/home", name="home")
     * @return RedirectResponse
     */
    public function homeAction()
    {
        return $this->redirect($this->generateUrl('veta_homework_homepage', [], UrlGeneratorInterface::ABSOLUTE_URL));
    }
}
