<?php

namespace Home\Controller;

use Think\Controller;

class AccountController extends Controller
{

    protected $userid;

    public function __construct()
    {
        parent::__construct();
    }

    public function register()
    {
        $params = I('post.');
        $result = D('Account')->register($params);
        echo json_encode($result);
    }

    public function login()
    {
        $params = I('post.');
        $result = D('Account')->login($params);
        if ($result['result'] == 0) {
            $result = [
                "result" => $result['userid']
            ];
        }
        echo json_encode($result);
    }

    public function isLogin()
    {
        $result = D('Account')->isLogin();
        echo json_encode($result);
    }

}
