<?php

namespace App\Interface;

interface PublishedDateEntityInterface
{
    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface;
}