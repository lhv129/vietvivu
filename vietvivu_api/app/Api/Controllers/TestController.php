<?php

namespace App\Api\Controllers;

class TestController extends BaseController
{
    public function index()
    {
        return $this->responseCommon(true, 'Lấy dữ liệu thành công', null, 200);
    }
}
