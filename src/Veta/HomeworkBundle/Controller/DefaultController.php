<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
        $response = new JsonResponse();
        $response->setData([
            'content' => 'HomePage',
        ]);
        return $response;
    }

    /**
     * @Route("/about", name="about")
     * @param Request $request
     * @return Response
     */
    public function aboutAction(Request $request)
    {
        $route = $request->attributes->get('_route');
        $text = 'This page route: '.$route;

        $response = new Response();
        $response->setContent("<h1>About</h1> <p>$text</p>");
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * @Route("/{_locale}/news/{slug}", name="news_view", requirements={"slug": "\d+", "_locale": "en|uk"})
     * @param $slug
     * @param $_locale
     * @return Response
     */
    public function viewAction($slug = 1, $_locale)
    {
        return $this->forward('VetaHomeworkBundle:Default:show', [
            'slug' => $slug,
            '_locale' => $_locale
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request)
    {
        $slug = $request->attributes->get('slug');
        $_locale = $request->attributes->get('_locale');

        $fileContent = "<h1>".$request->server->get('HTTP_HOST')."/$_locale/news/$slug</h1>"; // the generated file content

        $response = new Response($fileContent);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'foo.txt'
        );
        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
}
