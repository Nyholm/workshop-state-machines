<?php

declare(strict_types=1);

namespace App\Entity;

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

    private $open = false;
    private $merged = false;
    private $closed = false;
    private $locked = false;

}