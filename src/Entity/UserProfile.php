<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity()
 */
class UserProfile
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private $state = 'start';

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $name;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $email;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $twitter;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private $favoriteColor;

    public function __construct()
    {
    }

    public function __toString()
    {
        return $this->name ?? '';
    }


    public function getId()
    {
        return $this->id;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getTwitter()
    {
        return $this->twitter;
    }

    public function setTwitter($twitter): void
    {
        $this->twitter = $twitter;
    }

    public function getFavoriteColor()
    {
        return $this->favoriteColor;
    }

    public function setFavoriteColor($favoriteColor): void
    {
        $this->favoriteColor = $favoriteColor;
    }
}
