<?php

namespace App\Repositories\Contracts;

use \App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Create a new User
     *
     * @param array{
     *     name: string,
     *     email: string,
     *     password: string,
     * } $data
     * @return User
     */
    public function store(array $data): User;
}