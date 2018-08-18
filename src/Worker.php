<?php

declare(strict_types=1);

namespace App;

use App\Service\Database;
use App\Service\MailerService;
use App\StateMachine\StateMachine;
use App\StateMachine\Step\AddYourName;

class Worker
{
    private $db;
    private $mailer;

    public function __construct(Database $em, MailerService $mailer)
    {
        $this->db = $em;
        $this->mailer = $mailer;
    }

    public function run()
    {
        $users = $this->db->getAllUsers();

        foreach ($users as $user) {
            // TODO Create a StateMachine object and call ->start()
        }

        $this->db->saveUsers($users);
    }
}
