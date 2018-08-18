<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

class Database
{
    private $dataPath;

    public function __construct()
    {
        $this->dataPath = __DIR__.'/../../var';
    }

    public function getAllUsers(): array
    {
        $fileContent = file_get_contents($this->dataPath.'/users.json');
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