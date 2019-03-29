<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 29/03/2019
 * Time: 12:01
 */


namespace Turing\Services;

use Turing\User;

interface CustomerServiceInterface
{
    public function getById(int $id): User;

    public function update(int $id, array $params): User;

}