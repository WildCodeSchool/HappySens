<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChangePwd
 *
 * @ORM\Table(name="change_pwd")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChangePwdRepository")
 */
class ChangePwd
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
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer")
     */
    private $idUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateSend", type="datetime")
     */
    private $dateSend;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     */
    private $pwd;

    /**
     * @var string
     */
    private $confirmPwd;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param $firstName
     * @param $email
     * @param $id
     * @return string
     */
    public function setToken($firstName, $email, $id)
    {
        return $this->token = hash('md5', $firstName . $email . $id);
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param $pwd
     * @return $this
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmPwd()
    {
        return $this->confirmPwd;
    }

    /**
     * @param $confirmPwd
     * @return $this
     */
    public function setConfirmPwd($confirmPwd)
    {
        $this->confirmPwd = $confirmPwd;
        return $this;
    }




    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return ChangePwd
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set dateSend
     *
     * @param \DateTime $dateSend
     *
     * @return ChangePwd
     */
    public function setDateSend($dateSend)
    {
        $this->dateSend = $dateSend;

        return $this;
    }

    /**
     * Get dateSend
     *
     * @return \DateTime
     */
    public function getDateSend()
    {
        return $this->dateSend;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ChangePwd
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return boolean
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param $isActive
     * @return boolean
     */
    public function setIsActive($isActive)
    {
        return $this->isActive = $isActive;
    }


}
