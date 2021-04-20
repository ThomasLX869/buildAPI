<?php

namespace App\Controller;

use App\Entity\Genre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     */
    public function list(SerializerInterface $serializer)
    {

        $em = $this->getDoctrine()->getManager();
        $genres = $em->getRepository(Genre::class);
        $genresObject = $genres->findAll();
        $result = $serializer->serialize(
            $genresObject,
            'json',
            [
                'groups'=>'listGenreFull'
            ]
        );
        return new JsonResponse($result, 200, [], true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     */
    public function show(SerializerInterface $serializer, Genre $genre)
    {
        $result = $serializer->serialize(
            $genre,
            'json',
            [
                'groups'=>'listGenreSimple'
                //'groups'=>'listGenreFull'
            ]
        );
        return new JsonResponse($result, Response::HTTP_OK, [], true);
    }


    /**
     * @Route("/api/genres", name="api_genres_create", methods={"POST"})
     */
    public function create(SerializerInterface $serializer, Request $request)
    {
        $data = $request->getContent();
        $em = $this->getDoctrine()->getManager();
        $genre = new Genre();

        $genre = $serializer->deserialize($data, Genre::class, 'json');
        $em->persist($genre);
        $em->flush();

        return new JsonResponse("Le genre a bien été créé", Response::HTTP_CREATED,
//            ['location' => "api/genres/".$genre->getId()],
            ['location' => $this->generateUrl(
                'api_genres_show',
                ["id" => $genre->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL)],
            true);
    }


    /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"PUT"})
     */
    public function edit(SerializerInterface $serializer, Genre $genre, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->getContent();
        $serializer->deserialize($data, Genre::class,'json', ['object_to_populate'=>$genre]);
        $em->persist($genre);
        $em->flush();

        return new JsonResponse("Mise à jour effectuée", Response::HTTP_OK, [], true);
    }
}
