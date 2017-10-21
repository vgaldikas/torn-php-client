<?php

declare(strict_types=1);

/*
 * This file is part of Torn PHP Client.
 *
 * (c) Vytautas Galdikas <galdikas.vytautas@gmail.com>
 */

namespace VGaldikas\Torn;

class Torn
{
    /**
     * The API key used for authentication
     *
     * @var string
     */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }
}
