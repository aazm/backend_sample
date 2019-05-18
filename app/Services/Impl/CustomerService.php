<?php
/**
 * Created by PhpStorm.
 * User: aborovkov
 * Date: 29/03/2019
 * Time: 12:01
 */

namespace Turing\Services\Impl;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Turing\Services\CustomerServiceInterface;
use Turing\User;

class CustomerService implements CustomerServiceInterface
{
    /**
     * @inheritdoc
     */
    public function getById(int $id): ?User
    {
        return User::find($id);
    }

    public function update(int $id, array $params): User
    {
        try {

            DB::beginTransaction();

            $user = $this->getById($id);

            $user->update($params);

            DB::commit();

            return $user;

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('error during user update', ['e' => $e]);

            throw $e;
        }

    }
}