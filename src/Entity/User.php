<?php

declare(strict_types=1);

namespace App\Entity;

class User
{
    private $id;
    private $name;
    private $email;
    private $twitter;

    /**
     * Convert a user to array (used when we store user in database)
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'twitter' => $this->twitter,
        ];
    }

    /**
     * Convert a array to a user
     */
    public static function fromArray(array $data): User
    {
        $user = new self();

        $user->id = (int)$data['id'];
        $user->name = $data['name'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->twitter = $data['twitter'] ?? null;

        return $user;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter)
    {
        $this->twitter = $twitter;
    }
}
