<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $book_title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $issue_year;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author_name;

    public function getId(): ?int
    {
        return $this->id;
    }
    /* This setter is needed only to build form for deleting the books.
     * Avoid using it elsewhere. 
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getBookTitle(): ?string
    {
        return $this->book_title;
    }

    public function setBookTitle(string $book_title): self
    {
        $this->book_title = $book_title;

        return $this;
    }

    public function getIssueYear(): ?int
    {
        return $this->issue_year;
    }

    public function setIssueYear(?int $issue_year): self
    {
        $this->issue_year = $issue_year;

        return $this;
    }

    public function getAuthorName(): ?string
    {
        return $this->author_name;
    }

    public function setAuthorName(string $author_name): self
    {
        $this->author_name = $author_name;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata) : void{
        $metadata->addPropertyConstraint('book_title', new Assert\Length([
        'min' => 1,
        'max' => 255,
        'minMessage' => 'Название книги не может быть пустым!',
        'maxMessage' => 'Слишком длинное название книги!'
        ]));

        $metadata->addPropertyConstraint('issue_year', new Assert\Type([
        'type'   => "integer",
        'message' => 'Год должен быть целым положительным числом!'
        ]));
        $metadata->addPropertyConstraint('issue_year', new Assert\NotBlank([
        'message' => 'Год должен быть целым положительным числом!'
        ]));
        $metadata->addPropertyConstraint('issue_year', new Assert\Positive([
        'message' => 'Год должен быть целым положительным числом!'
        ]));
        $metadata->addPropertyConstraint('issue_year', new Assert\LessThanOrEqual([
        'value'   => date("Y"), // prevents from entering 'future' books
        'message' => 'Год не может быть больше текущего!'
        ]));

        $metadata->addPropertyConstraint('author_name', new Assert\Length([
        'min' => 1,
        'max' => 255,
        'minMessage' => 'Имя автора не может быть пустым!',
        'maxMessage' => 'Слишком длинное имя автора!'
        ]));
    }

}
