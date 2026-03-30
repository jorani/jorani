<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Leave;

class TypeRepository extends EntityRepository
{
    /**
     * Count the number of times a leave type is used in the database
     * @param int $id identifier of the leave type record
     * @return int number of times the leave type is used
     */
    public function usage(int $id): int
    {
        return (int) $this->getEntityManager()
            ->createQueryBuilder()
            ->select('COUNT(l.id)')
            ->from(Leave::class, 'l')
            ->where('l.type = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
