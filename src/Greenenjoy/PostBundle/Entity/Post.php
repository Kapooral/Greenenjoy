<?php

namespace Greenenjoy\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
use Greenenjoy\PostBundle\State\State;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Greenenjoy\PostBundle\Repository\PostRepository")
 */
class Post
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
     * @ORM\ManyToOne(targetEntity="Greenenjoy\PostBundle\Entity\Categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity="Greenenjoy\PostBundle\Entity\Image", cascade={"persist","remove"})
     * @Assert\Valid()
     */
    private $image;

    /**
     * @var array
     *
     * @ORM\Column(name="likes", type="array")
     */
    private $likes;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="postDate", type="date")
     */
    private $postDate;

    public function __construct()
    {
        $this->state = State::STANDBY;
        $this->likes = [];
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle.
     *
     * @param string|null $subtitle
     *
     * @return Post
     */
    public function setSubtitle($subtitle = null)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle.
     *
     * @return string|null
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set postDate.
     *
     * @param \DateTime $postDate
     *
     * @return Post
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get postDate.
     *
     * @return \DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set likes.
     *
     * @param array $likes
     *
     * @return Post
     */
    public function setLikes($likes)
    {
        $this->likes[] = $likes;

        return $this;
    }

    /**
     * Get likes.
     *
     * @return array
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Set state.
     *
     * @param string $state
     *
     * @return Post
     */
    public function setState($state)
    {
        if (in_array($state, State::getValues()))
        {
            $this->state = $state;
            return $this;
        }
        else
        {
            throw new InvalidArgumentException('Impossible de publier l\'article.');
        }
    }

    /**
     * Get state.
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set categorie.
     *
     * @param \Greenenjoy\PostBundle\Entity\Categories $categorie
     *
     * @return Post
     */
    public function setCategorie(\Greenenjoy\PostBundle\Entity\Categories $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie.
     *
     * @return \Greenenjoy\PostBundle\Entity\Categories
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set image.
     *
     * @param \Greenenjoy\PostBundle\Entity\Image|null $image
     *
     * @return Post
     */
    public function setImage(\Greenenjoy\PostBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \Greenenjoy\PostBundle\Entity\Image|null
     */
    public function getImage()
    {
        return $this->image;
    }
}
