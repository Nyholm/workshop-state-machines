<?php

declare(strict_types=1);

namespace App;

use App\Service\Database;
use App\Service\MailerService;

class Worker
{
    private $db;
    private $mailer;

    public function __construct(Database $db, MailerService $mailer)
    {
        $this->db = $db;
        $this->mailer = $mailer;
    }

    public function run(): void
    {
        $users = $this->db->getAllUsers();

        foreach ($users as $user) {
            // TODO Create a new StateMachine() object and call ->start()
            // No DI required, just create a new object.
        }

        $this->db->saveUsers($users);
    }
}
