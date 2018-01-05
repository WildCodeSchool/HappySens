<?php

namespace AppBundle\Repository;

/**
 * SkillRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SkillRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Count the number of skill
     * @return mixed
     */
    public function getNumberSkill() {
        $qb = $this
            ->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->getQuery();
        return $qb->getResult();
    }

}
