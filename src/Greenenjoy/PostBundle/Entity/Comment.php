<?php

namespace Greenenjoy\PostBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="Greenenjoy\PostBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="Greenenjoy\PostBundle\Entity\Post")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="Ce champs ne peut être vide.")
     * @Assert\Length(min=2, minMessage="Votre nom doit être de {{ limit }} caractères minimum.")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message="Ce champs ne peut être vide.")
     * @Assert\Length(min=2, minMessage="Votre commentaire doit être de {{ limit }} caractères minimum.")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="commentDate", type="datetime")
     * @Assert\DateTime(message="La date de publication est incorrecte.")
     */
    private $commentDate;

    /**
     * @var int
     *
     * @ORM\Column(name="reported", type="integer")
     */
    private $reported;

    public function __construct()
    {
        $this->commentDate = new \DateTime();
        $this->reported = 0;
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
     * Set name.
     *
     * @param string $name
     *
     * @return Comment
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Comment
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
     * Set commentDate.
     *
     * @param \DateTime $commentDate
     *
     * @return Comment
     */
    public function setCommentDate($commentDate)
    {
        $this->commentDate = $commentDate;

        return $this;
    }

    /**
     * Get commentDate.
     *
     * @return \DateTime
     */
    public function getCommentDate()
    {
        return $this->commentDate;
    }

    /**
     * Set reported.
     *
     * @param int $reported
     *
     * @return Comment
     */
    public function setReported($reported)
    {
        $this->reported += $reported;

        return $this;
    }

    /**
     * Get reported.
     *
     * @return int
     */
    public function getReported()
    {
        return $this->reported;
    }

    /**
     * Set post.
     *
     * @param \Greenenjoy\PostBundle\Entity\Post $post
     *
     * @return Comment
     */
    public function setPost(\Greenenjoy\PostBundle\Entity\Post $post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post.
     *
     * @return \Greenenjoy\PostBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
