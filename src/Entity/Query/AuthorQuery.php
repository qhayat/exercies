<?php

namespace App\Entity\Query;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider]
class AuthorQuery
{
    public function __construct(
        private readonly AuthorRepository $authorRepository,
    ) {
    }

    /**
     * @return Author[]
     */
    #[GQL\Query(name: "Authors", type: "[Author]")]
    public function Authors(): array
    {
        return $this->authorRepository->findBy([]);
    }

    /**
     * @param int $id
     * @return null|Author
     */
    #[GQL\Query(name: "Author", type: "Author")]
    public function author(int $id): ?Author
    {
        return $this->authorRepository->find($id);
    }
}
