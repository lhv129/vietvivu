<?php

namespace App\Repositories;

use App\Models\ClientMenu;

class ClientMenuRepository extends BaseRepository
{
    public function model()
    {
        return ClientMenu::class;
    }
}
