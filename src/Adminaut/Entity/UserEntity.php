<?php

namespace Adminaut\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class UserEntity
 * @ORM\Entity(repositoryClass="\Adminaut\Repository\UserRepository")
 * @ORM\Table(name="mfcc_admin_user")
 * @property integer $id
 * @package Adminaut\Entity
 */
class UserEntity extends Base implements UserInterface
{
    /**
     * @ORM\Column(type="string", length=128);
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=128, unique=true);
     * @var string
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=128);
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=128);
     */
    protected $role;

    /**
     * @ORM\Column(type="smallint", nullable=true);
     * @var int
     */
    protected $status;

    /**
     * User constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getGravatarHash() {
        $email = trim( $this->getEmail() );
        $email = strtolower( $email );
        return md5( $email );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'active' => $this->active,
        ];
    }
}