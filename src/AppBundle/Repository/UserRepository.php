<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $companyId
     * @return mixed
     */
    public function getProjectsInCompany($companyId) {
        $qb = $this
            ->createQueryBuilder('u')
            ->join('u.authorProject', 'p')
            ->setParameter('idCompany', $companyId)
            ->where('u.company=:idCompany')
            ->getQuery();
        return $qb->getResult();
    }

    /**
     * Return user by type (salary or happyCoach)
     * @return mixed
     */
    public function getUserByType($status)
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->join('u.company', 'c');
        if ($status == 'salary') {
            $qb = $qb->where('u.status = 2 or u.status = 3');
        } else if ($status == 'happyCoach') {
            $qb = $qb->where('u.status = 4');
        }
        $qb = $qb->getQuery();
        return $qb->getResult();
    }

    /**
     * Return count number of user by Role
     * @return mixed
     */
    public function getNumberUserByRole() {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->groupBy('u.status')
            ->getQuery();
        return $qb->getResult();
    }
}
