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
    /** @ORM\Column(type="string", length=255) */
    private $nam2;

    private $open = false;
    private $merged = false;
    private $closed = false;
    private $locked = false;

}