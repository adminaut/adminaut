<?php

namespace Adminaut\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role
 * @ORM\Entity(repositoryClass="\Adminaut\Repository\Role")
 * @ORM\Table(name="mfcc_admin_role")
 * @property integer $id
 * @package Adminaut\Entity
 */
class Role
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer");
     * @ORM\GeneratedValue(strategy="AUTO");
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true);
     * @var string
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Adminaut\Entity\Resource", mappedBy="role")
     */
    protected $resources;

    /**
     * @ORM\OneToMany(targetEntity="Adminaut\Entity\User", mappedBy="role")
     */
    protected $users;

    /**
     * Role constructor.
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->resource = new ArrayCollection();
        $this->users = new ArrayCollection();
        if ($name) {
            $this->name = $name;
        }
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
     * @return mixed
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param mixed $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}