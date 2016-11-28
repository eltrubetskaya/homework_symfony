<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Veta\HomeworkBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserController extends Controller
{
    /**
     * Query for a single user by its primary key (usually "id")
     *
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $userId = $request->attributes->get('id');
        $user = $this->getDoctrine()
        ->getRepository('VetaHomeworkBundle:User')
        ->find($userId);

        $response = new Response();
        if (!$user) {
            //throw $this->createNotFoundException('No user found for id '.$userId);
            $response->setContent('No user found for id '.$userId);
        } else {
            $jsonContent = $serializer->serialize($user, 'json');
            $response->setContent($jsonContent);
        }
        $response->headers->set('Content-Type', 'text/html');
        return $response;
    }

    /**
     * Create User
     *
     * @return Response
     */
    public function createAction()
    {
        $user = new User();
        $user->setFirstName('Test');
        $user->setLastName('Test');
        $user->setEmail('test_55@test.com');

        $em = $this->getDoctrine()->getManager();

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new user with id '.$user->getId());
    }

    /**
     * Edit data User
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function editAction(Request $request)
    {
        $userId = $request->attributes->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('VetaHomeworkBundle:User')->find($userId);

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$userId
            );
        }

        $user->setFirstName('Foo');
        $em->flush();

        return $this->redirectToRoute('veta_homework_user', ['id' => $userId]);
    }

    /**
     * Delete User
     *
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $userId = $request->attributes->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('VetaHomeworkBundle:User')->find($userId);
        $em->remove($user);
        $em->flush();

        return new Response('Delete user with id '.$userId);
    }


    /**
     * Change data some Users
     *
     * @return Response
     */
    public function changeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u
             FROM VetaHomeworkBundle:User u
             WHERE u.id < :id
             ORDER BY u.id ASC'
        )->setParameter('id', 5);

        $users = $query->getResult();
        foreach ($users as $user) {
            $user->setFirstName('Boo');
        }
        $em->flush();
        return $this->forward('VetaHomeworkBundle:User:all');
    }

    /**
     * Find *all* Users
     *
     * @return Response
     */
    public function allAction()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $repository = $this->getDoctrine()->getRepository('VetaHomeworkBundle:User');
        $user = $repository->findAll();

        $response = new Response();
        $jsonContent = $serializer->serialize($user, 'json');
        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }
}
