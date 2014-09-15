<?php

namespace Rottenwood\ArticleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность "Статья"
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="Rottenwood\ArticleBundle\Repository\ArticleRepository")
 */
class Article {

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Авторы
     * @ORM\ManyToMany(targetEntity="Author")
     * @ORM\JoinTable(name="articles_to_authors",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")}
     *      )
     **/
    private $authors;

    /**
     * Название статьи
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * Текст статьи
     * @var string
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

    /**
     * Дата публикации
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * Рейтинг
     * @var integer
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating = 0;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $authors
     */
    public function setAuthors($authors) {
        $this->authors = $authors;
    }

    /**
     * @return mixed
     */
    public function getAuthors() {
        return $this->authors;
    }

    /**
     * Set title
     * @param string $title
     * @return Article
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set content
     * @param string $content
     * @return Article
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set date
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set rating
     * @param integer $rating
     * @return Article
     */
    public function setRating($rating) {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     * @return integer
     */
    public function getRating() {
        return $this->rating;
    }
}
