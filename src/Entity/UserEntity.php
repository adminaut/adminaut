<?php

namespace Adminaut\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
//use Zend\Crypt\Password\Bcrypt;
use Zend\Form\Annotation;

/**
 * Class UserEntity
 * @package Adminaut\Entity
 * @ORM\Entity(repositoryClass="Adminaut\Repository\UserRepository")
 * @ORM\Table(name="adminaut_user")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\HasLifecycleCallbacks()
 */
class UserEntity implements UserEntityInterface
{
    use AdminautEntityTrait;

    /**
     * Constants
     */
    const STATUS_UNKNOWN = 0;
    const STATUS_NEW = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_LOCKED = 3;
    const STATUS_BANNED = 4;

    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Name", "listed":true});
     * @Annotation\Flags({"priority":25});
     * @Annotation\Type("Adminaut\Datatype\Text");
     * @Annotation\Required(true);
     * @var string
     */
    protected $name = '';

    /**
     * @ORM\Column(type="string", length=128, unique=true);
     * @Annotation\Options({"label":"Email", "listed":true, "primary":true});
     * @Annotation\Flags({"priority":20});
     * @Annotation\Type("Zend\Form\Element\Email");
     * @Annotation\Required(true);
     * @var string
     */
    protected $email = '';

    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Password"});
     * @Annotation\Flags({"priority":15});
     * @Annotation\Type("Zend\Form\Element\Password");
     * @var string
     */
    protected $password = '';

    /**
     * @ORM\Column(name="password_change_on_next_logon", type="boolean", options={"default":0});
     * @Annotation\Options({"listed":false, "label":"Must change password at next logon"});
     * @Annotation\Flags({"priority":13});
     * @Annotation\Type("Adminaut\Datatype\Checkbox");
     * @var boolean
     */
    protected $passwordChangeOnNextLogon = false;

    /**
     * @ORM\Column(type="string", length=128);
     * @Annotation\Options({"label":"Role", "empty_option":"Select role", "listed":true});
     * @Annotation\Flags({"priority":10});
     * @Annotation\Type("Zend\Form\Element\Select");
     * @Annotation\Required(true);
     * @var string
     */
    protected $role = '';

    /**
     * @ORM\Column(type="string", length=128, options={"default":"en"});
     * @Annotation\Options({"label":"Language", "availableLanguages":{"cs", "sk", "en", "de"}, "listed":true});
     * @Annotation\Flags({"priority":5});
     * @Annotation\Type("Adminaut\Datatype\Language");
     * @Annotation\Required(true);
     * @var string
     */
    protected $language = 'en';

    /**
     * @ORM\Column(type="integer", name="status", options={"default":0});
     * @Annotation\Options({
     *     "label":"Status",
     *     "value_options":{
     *          "0":"Unknown",
     *          "1":"New",
     *          "2":"Active",
     *          "3":"Locked",
     *          "4":"Banned"
     *      },
     *     "listed":true
     * });
     * @Annotation\Flags({"priority":0});
     * @Annotation\Type("Zend\Form\Element\Select");
     * @Annotation\Required(true);
     * @var int
     */
    protected $status = self::STATUS_ACTIVE;

    /**
     * Inverse side.
     * @ORM\OneToMany(targetEntity="UserAccessTokenEntity", mappedBy="user");
     * @Annotation\Exclude();
     * @var ArrayCollection
     */
    protected $accessTokens;

    /**
     * Inverse side.
     * @ORM\OneToMany(targetEntity="UserLoginEntity", mappedBy="user");
     * @Annotation\Exclude();
     * @var ArrayCollection
     */
    protected $logins;

    /**
     * @Annotation\Exclude();
     * @var bool
     */
    protected $active = true;

    //-------------------------------------------------------------------------

    /**
     * UserEntity constructor.
     */
    public function __construct()
    {
        $this->accessTokens = new ArrayCollection();
        $this->logins = new ArrayCollection();
    }

    //-------------------------------------------------------------------------

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
     * @return bool
     */
    public function isPasswordChangeOnNextLogon(): bool
    {
        return $this->passwordChangeOnNextLogon;
    }

    /**
     * @param bool $passwordChangeOnNextLogon
     */
    public function setPasswordChangeOnNextLogon(bool $passwordChangeOnNextLogon)
    {
        $this->passwordChangeOnNextLogon = $passwordChangeOnNextLogon;
    }

//    todo: implement setters and getters as below instead of generating password somewhere else in code
//    /**
//     * @return string
//     */
//    public function getPasswordHash()
//    {
//        return $this->password;
//    }
//
//    /**
//     * @param string $passwordHash
//     */
//    public function setPasswordHash($passwordHash)
//    {
//        $this->password = $passwordHash;
//    }
//
//    /**
//     * @param string $password
//     * @param int $cost
//     */
//    public function setPassword($password, $cost = 10)
//    {
//        $passwordHash = (new Bcrypt())->setCost($cost)->create($password);
//
//        $this->setPasswordHash($passwordHash);
//    }
//
//    /**
//     * @param string $password
//     * @return bool
//     */
//    public function verifyPassword($password)
//    {
//        return (new Bcrypt())->verify($password, $this->getPasswordHash());
//    }

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
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
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

    /**
     * @return string
     */
    public function getGravatarHash()
    {
        return md5(strtolower(trim($this->getEmail())));
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
            'language' => $this->language,
            'status' => $this->status,
            'active' => $this->active,
        ];
    }
}
