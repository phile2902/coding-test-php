<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Authentication\PasswordHasher\DefaultPasswordHasher;

class User extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];

    protected $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password): string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }
}
