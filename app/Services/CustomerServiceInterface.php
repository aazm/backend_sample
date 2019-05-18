<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 29/03/2019
 * Time: 12:01
 */


namespace Turing\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Turing\User;

interface CustomerServiceInterface
{
    /**
     * Retrieve User object by given id
     *
     *
     * @throws ModelNotFoundException
     * @param int $id
     * @return User
     */
    public function getById(int $id): ?User;

    /**
     * Update User object by given id
     *
     *
     * @throws ModelNotFoundException $e
     * @param int $id
     * @param array $params
     * @return User
     */
    public function update(int $id, array $params): User;

}