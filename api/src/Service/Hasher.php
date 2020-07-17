<?php

declare(strict_types=1);

namespace App\Service;

/**
 * Class Hasher
 * @package App\Service
 */
class Hasher
{
    /**
     * @param string $password
     * @param string $alg
     * @return string
     */
    public function hash(string $password, string $alg = 'argon2id'): string
    {
        $hash = password_hash($password, $alg);
        if ($hash === false) {
            throw new \RuntimeException('Невозможно зашифровать пароль!');
        }
        return $hash;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
