<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/authors')]
class AuthorController extends AbstractController
{
    /**
     * @param AuthorRepository $authorRepository
     */
    public function __construct(private readonly ObjectRepository $authorRepository)
    {
    }

    #[Route('', name: 'author_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        return $this->json([
            'data' => [
                'authors' => $this->authorRepository->search($request->query->all())
            ],
        ], Response::HTTP_OK, [], ['groups' => ['author:list']]);
    }

    #[Route('', name: 'author_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator, SerializerInterface $serializer, LoggerInterface $logger): JsonResponse
    {
        /** @var  Author $author */
        $author = $serializer->deserialize($request->getContent(), Author::class, 'json');
        $errors = $validator->validate($author);

        if (count($errors) > 0) {
            return $this->json(['message' => (string) $errors], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->authorRepository->save($author, true);
            return $this->json(['data' => [
                'author' => $author,
            ]], Response::HTTP_CREATED, [], ['groups' => ['author:create']]);
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            return $this->json(['message' => 'An error occurred !', Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
