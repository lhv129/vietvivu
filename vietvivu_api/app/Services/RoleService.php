<?php

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService extends BaseService
{
    public function repository()
    {
        return RoleRepository::class;
    }
}
