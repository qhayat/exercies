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
use OpenApi\Attributes as OA;

#[Route('/authors')]
class AuthorController extends AbstractController
{
    /**
     * @param AuthorRepository $authorRepository
     */
    public function __construct(private readonly ObjectRepository $authorRepository)
    {
    }

    #[OA\Parameter(
        name: 'lastName',
        description: 'Author last name',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'firstName',
        description: 'Author first name',
        in: 'query',
        schema: new OA\Schema(type: 'string')
    )]
    #[Route('', name: 'author_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        return $this->json([
            'data' => [
                'authors' => $this->authorRepository->search($request->query->all())
            ],
        ], Response::HTTP_OK, [], ['groups' => ['author:list']]);
    }

    #[OA\RequestBody(
        description: 'Author information',
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'lastName', description: 'Author last name', type: 'string', example: 'Doe'),
                new OA\Property(property: 'firstName', description: 'Author first name', type: 'string', example: 'John'),
                new OA\Property(property: 'books', description: 'Author book', type: 'array', items: new OA\Items(properties: [
                    new OA\Property(property: 'title', description: 'Book title', type: 'string', example: 'Clean architecture')
                ], type: 'object'))
            ],
            type: 'object'
        )
    )]
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
            return $this->json(['message' => 'Author created !'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            return $this->json(['message' => 'An error occurred !', Response::HTTP_INTERNAL_SERVER_ERROR]);
        }
    }
}
