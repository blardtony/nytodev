<?php

namespace App\Service;

class TokenService
{
    public function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}
