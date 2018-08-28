<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class PullRequest
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private $name;

    /** @var @ORM\Column(type="boolean")  */
    private $preSubmit = true;

    /** @var @ORM\Column(type="boolean")  */
    private $open = false;

    /** @var @ORM\Column(type="boolean")  */
    private $merged = false;

    /** @var @ORM\Column(type="boolean")  */
    private $closed = false;

    /** @var @ORM\Column(type="boolean")  */
    private $locked = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPreSubmit(): bool
    {
        return $this->preSubmit;
    }

    public function setPreSubmit(bool $preSubmit): void
    {
        $this->preSubmit = $preSubmit;
    }

    public function getOpen(): bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): void
    {
        $this->open = $open;
    }

    public function getMerged(): bool
    {
        return $this->merged;
    }

    public function setMerged(bool $merged): void
    {
        $this->merged = $merged;
    }

    public function getClosed(): bool
    {
        return $this->closed;
    }

    public function setClosed(bool $closed): void
    {
        $this->closed = $closed;
    }

    public function getLocked(): bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): void
    {
        $this->locked = $locked;
    }
}
