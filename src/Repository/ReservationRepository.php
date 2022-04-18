<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Reservation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findBydate($room, $start, $end)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.room = :room')
            ->andWhere(':start < r.endAt')
            ->andWhere(':end > r.startAt')
            ->setParameter('room', $room)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findByUser($user)
    {
        return $this->createQueryBuilder('resa')
            ->select('resa', 'room', 'estab')
            ->join('resa.room', 'room')
            ->join('room.establishment', 'estab')
            ->andWhere('resa.user = :user')
            ->orderBy('resa.startAt', 'DESC')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
