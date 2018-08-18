<?php

namespace Greenenjoy\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Greenenjoy\CoreBundle\Roles\Profil;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Greenenjoy\SecurityBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Votre prénom doit faire au minimum {{ limit }} caractères.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Votre nom doit faire au minimum {{ limit }} caractères.")
     */
    private $lastName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     * @Assert\Length(min=2, minMessage="Votre nom d'affichage doit faire au minimum {{ limit }} caracctères.")
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\Email(message="Veuillez entrer une adresse e-mail correcte.", checkMX=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="instagram", type="string", length=255, nullable=true)
     * @Assert\Length(min=2, minMessage="Votre nom Instagram doit faire au minimum {{ limit }} caractères.")
     */
    private $instagram;

    /**
     * @ORM\OneToOne(targetEntity="Greenenjoy\PostBundle\Entity\Image", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $profilePicture;

    /**
     * @var string|null
     *
     * @ORM\Column(name="biography", type="text", nullable=true)
     * @Assert\Length(min=50, minMessage="Votre biographie doit faire au minimum {{ limit }} caracctères.")
     */
    private $biography;

    /**
     * @ORM\OneToOne(targetEntity="Greenenjoy\PostBundle\Entity\Image", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $coverBiography;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Regex(pattern="#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}$#", message="Votre mot de passe doit faire au minimum 6 caractères et contenir au moins 1 lettre min, 1 lettre maj et 1 chiffre.")
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     * @Assert\NotBlank(message="Aucun type de profil n'a été affilié à ce compte.")
     */
    private $roles;

    /**
     * @var string|null
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true, unique=true)
     */
    private $token;


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
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set username
     *
     * @param string|null $username
     *
     * @return User
     */
    public function setUsername($username = null)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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
     * Set facebook
     *
     * @param string|null $facebook
     *
     * @return User
     */
    public function setFacebook($facebook = null)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set instagram
     *
     * @param string|null $instagram
     *
     * @return User
     */
    public function setInstagram($instagram = null)
    {
        $this->instagram = $instagram;

        return $this;
    }

    /**
     * Get instagram
     *
     * @return string|null
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * Set tweeter
     *
     * @param string|null $tweeter
     *
     * @return User
     */
    public function setTweeter($tweeter = null)
    {
        $this->tweeter = $tweeter;

        return $this;
    }

    /**
     * Get tweeter
     *
     * @return string|null
     */
    public function getTweeter()
    {
        return $this->tweeter;
    }

    /**
     * Set profilePicture
     *
     * @param string|null $profilePicture
     *
     * @return User
     */
    public function setProfilePicture($profilePicture = null)
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    /**
     * Get profilePicture
     *
     * @return string|null
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    /**
     * Set biography
     *
     * @param string|null $biography
     *
     * @return User
     */
    public function setBiography($biography = null)
    {
        $this->biography = $biography;

        return $this;
    }

    /**
     * Get biography
     *
     * @return string|null
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * Set coverBiography
     *
     * @param string|null $coverBiography
     *
     * @return User
     */
    public function setCoverBiography($coverBiography = null)
    {
        $this->coverBiography = $coverBiography;

        return $this;
    }

    /**
     * Get coverBiography
     *
     * @return string|null
     */
    public function getCoverBiography()
    {
        return $this->coverBiography;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string|null $salt
     *
     * @return User
     */
    public function setSalt($salt = null)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string|null
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
    	if (in_array($roles, Profil::getValues()))
    	{
    		$this->roles = array($roles);
        	return $this;
    	} 
    	else
    	{
    		throw new InvalidArgumentException('Ce type de profil est inconnu.');
    	}
    }

    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set token
     *
     * @param string|null $token
     *
     * @return User
     */
    public function setToken($token = null)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    public function eraseCredentials()
    {

    }
}
