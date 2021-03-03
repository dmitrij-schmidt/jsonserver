<?php

namespace App\Controllers\Api;

use App\Core\App;
use App\Models\User;
use App\Core\AppResponse;

class UsersController
{
    public function index()
    {
        $data = App::get('QueryBuilder')
            ->for(User::class)
            ->selectSortedBy('points', 'DESC');

        return AppResponse::json($data);
    }

    public function create()
    {
        $user = App::get('QueryBuilder')
            ->for(User::class)
            ->append(User::fromRequest($_POST));

        return AppResponse::json($user);
    }

    public function delete($id)
    {
        App::get('QueryBuilder')
            ->for(User::class)
            ->deleteBy('id', (int) $id);

        return AppResponse::json([$id]);
    }

    public function deleteByUsername($username)
    {
        App::get('QueryBuilder')
        ->for(User::class)
        ->deleteBy('username', (string) $username);

        return AppResponse::json([$username]);
    }

}
