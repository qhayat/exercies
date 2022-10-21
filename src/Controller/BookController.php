<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/books')]
class BookController extends AbstractController
{
    /**
     * @param BookRepository $bookRepository
     */
    public function __construct(private readonly ObjectRepository $bookRepository)
    {
    }

    #[Route('/my-books/{author_id}', name: 'list-of-my-books', methods: ['GET'], format: 'json')]
    #[ParamConverter('author', options: ['mapping' => ['author_id' => 'id']])]
    public function index(Author $author): JsonResponse
    {
        $books = $this->bookRepository->findBy(['author' => $author]);
        return $this->json([
            'data' => [
                'books' => $books,
            ],
        ], Response::HTTP_OK, [], ['groups' => 'book:list']);
    }

    #[Route('/my-books/{author_id}/add-suffix/{suffix}', name: 'add-suffix-on-my-books', methods: ['GET'], format: 'json')]
    #[ParamConverter('author', options: ['mapping' => ['author_id' => 'id']])]
    public function addSuffix(string $suffix, Author $author, EntityManagerInterface $entityManager): JsonResponse
    {
        $books = $this->bookRepository->findBy(['author' => $author]);

        foreach ($books as $book) {
            $book->setTitle($book->getTitle().'-'.$suffix);
            $entityManager->persist($book);
            $entityManager->flush();
        }

        return $this->json([
            'message' => $suffix. ' added with success!',
        ]);
    }
}
