<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator as Paginator;
use Doctrine\ORM\Query as Query;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }



    /**
    *
    * Takes query object, current page & limit.
    * The offset is calculated using page and limit.
    * Returns an `Paginator` instance, which can be used as follows:
    *
    *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
    *     $paginator->count() # Count of ALL posts (ie: `20` posts)
    *     $paginator->getIterator() # ArrayIterator
    *
    * @param Doctrine\ORM\Query $query DQL Query Object
    * @param integer            $page  Current page (defaults to 1)
    * @param integer            $limit Items per page (defaults to 5)
    *
    * @return \Doctrine\ORM\Tools\Pagination\Paginator
    */

    public function paginate(Query $query, int $page = 1, int $limit = 5) : Paginator{
        $paginator = new Paginator($query);

        $paginator->getQuery()
                  ->setFirstResult($limit * ($page - 1)) // offset
                  ->setMaxResults($limit); // limit

        return $paginator;
    }

    /*
    * Returns Paginator object with number of books
    * corresponding to given page and number.
    * @param integer            $page  Current page (defaults to 1)
    * @param integer            $limit Items per page (defaults to 5)
    *
    * @return \Doctrine\ORM\Tools\Pagination\Paginator
    */
    public function getAllBooks(int $page = 1, int $limit = 5) : Paginator{
        $query = $this->createQueryBuilder('books')
                      ->orderBy('books.id', 'DESC')
                      ->getQuery();

        $paginator = $this->paginate($query, $page, $limit);
        return $paginator;
   }

    /**
    * Returns a Book object or NULL if not found.
    * @param integer            $id
    *
    * @return Book|null
    */
    public function findOneById(int $id): ?Book{
        return $this->createQueryBuilder('books')
            ->andWhere('books.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

}
