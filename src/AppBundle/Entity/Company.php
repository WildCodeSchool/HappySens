<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="activity", type="string", length=100, nullable=true)
     */
    private $activity;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="nbSalary", type="integer", nullable=true)
     */
    private $nbSalary;

    /**
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="slogan", type="string", length=255, nullable=true)
     */
    private $slogan;

    /**
     * @var string
     *
     * @ORM\Column(name="quality", type="text")
     */
    private $quality;

    /**
     *
     * @ORM\Column(name="facebook", type="string",  length=100, nullable=true)
     */
    private $facebook;

    /**
     *
     * @ORM\Column(name="twitter", type="string",  length=100, nullable=true)
     */
    private $twitter;

    /**
     *
     * @ORM\Column(name="linkedin", type="string",  length=100, nullable=true)
     */
    private $linkedin;

    /**
     *
     * @ORM\Column(name="logo", type="string",  length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="company")
     */
    private $users;






    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set activity
     *
     * @param string $activity
     *
     * @return Company
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return string
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nbSalary
     *
     * @param integer $nbSalary
     *
     * @return Company
     */
    public function setNbSalary($nbSalary)
    {
        $this->nbSalary = $nbSalary;

        return $this;
    }

    /**
     * Get nbSalary
     *
     * @return integer
     */
    public function getNbSalary()
    {
        return $this->nbSalary;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Company
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set slogan
     *
     * @param string $slogan
     *
     * @return Company
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set quality
     *
     * @param string $quality
     *
     * @return Company
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * Get quality
     *
     * @return string
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Company
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return mixed
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param mixed $facebook
     * @return Company
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $twitter
     * @return Company
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * @param mixed $linkedin
     * @return Company
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function __toString()
    {
        return $this->getLogo() . $this->getName();
    }


}
