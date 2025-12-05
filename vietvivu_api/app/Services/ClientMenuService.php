<?php

namespace App\Services;

use App\Repositories\ClientMenuRepository;

class ClientMenuService extends BaseService
{
    public function repository()
    {
        return ClientMenuRepository::class;
    }
}
