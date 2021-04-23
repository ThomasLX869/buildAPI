<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiAuteurController extends AbstractController
{
    /**
     * @Route("/api/auteur", name="api_auteur")
     */
    public function index(SerializerInterface $serializer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $genres = $em->getRepository(Auteur::class);
        $genresObject = $genres->findAll();
        $result = $serializer->serialize(
            $genresObject,
            'json',
            [
                'groups' => 'listAuteurFull'
            ]
        );
        return new JsonResponse($result, 200, [], true);
    }

    /**
     * @Route ("api/auteur/{id}", name="api_show_auteur")
     */
    public function show(SerializerInterface $serializer, Auteur $auteur){
        $result = $serializer->serialize(
            $auteur,
            'json',
            [
                'groups' => 'listAuteurSimple'
            ]
        );

        return new JsonResponse($result, Response::HTTP_OK, [], true);

    }

    /**
     * @Route ("api/auteur", name="api_create_auteur")
     */
    public function create(SerializerInterface $serializer, Request $request, ValidatorInterface $validator) {
        $data = $request->getContent();
        $em = $this->getDoctrine()->getManager();

        $auteur = $serializer->deserialize($data, Auteur::class, 'json');

        // Gestion des erreurs de validation
        $errors = $validator->validate($auteur);
        if (count($errors) > 0) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        $em->persist($auteur);
        $em->flush();
        return new JsonResponse("Le genre a bien été créé", Response::HTTP_CREATED,
//            ['location' => "api/genres/".$genre->getId()],
            ['location' => $this->generateUrl(
                'api_genres_show',
                ["id" => $auteur->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL)],
            true);
    }
}
