<?php

namespace App\Repository;

use App\Entity\ApiFetchError;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiFetchError>
 *
 * @method ApiFetchError|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiFetchError|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiFetchError[]    findAll()
 * @method ApiFetchError[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiFetchErrorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiFetchError::class);
    }

    public function save(ApiFetchError $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ApiFetchError $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function clearErrors(): int
    {
        $res = $this->createQueryBuilder('e')
            ->delete()
            ->getQuery()
            ->getSingleScalarResult()
        ;
        return $res ?? 0;
    }
}