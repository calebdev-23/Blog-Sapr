<?php

namespace App\Interface;

use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setAuthor(UserInterface $user):AuthoredEntityInterface;
}