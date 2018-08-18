<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

/**
 * You do not need to edit this class.
 * (Or that is not the intention. Do what you want)
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class Database
{
    private $dataPath;

    public function __construct()
    {
        $this->dataPath = __DIR__.'/../../var';
    }

    public function getAllUsers(): array
    {
        $file = $this->dataPath.'/users.json';
        if (!file_exists($file)) {
            $file = $this->dataPath.'/users.original.json';
        }

        $fileContent = file_get_contents($file);
        $data = json_decode($fileContent, true);

        $users = [];
        foreach ($data as $d) {
            $users[] = User::fromArray($d);
        }

        return $users;
    }

    /**
     * Save back to database
     *
     * @param User[] $users
     */
    public function saveUsers(array $users)
    {
        $data = [];
        foreach ($users as $user) {
            $data[] = $user->toArray();
        }

        file_put_contents($this->dataPath.'/users.json', json_encode($data));
    }
}
