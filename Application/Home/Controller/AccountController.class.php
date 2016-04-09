<?php

/**
 * 账号相关
 *
 * PHP version 5
 *
 * @category Appserver
 * @package  shopping
 * @author     haohan <uglyboyhans@126.com>
 * @license    http://www.cqu.edu.cn  None
 * @link          http://www.cqu.edu.cn 
 *
 */

namespace Home\Controller;

use Think\Controller;

/**
 * The control class of Account
 *
 * @category Account
 * @package  Account_Controller
 * @author     haohan <uglyboyhans@126.com>
 * @license    http://www.cqu.edu.cn  None
 * @link          http://www.cqu.edu.cn 
 * 
 */
class AccountController extends Controller
{

    protected $userid;

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 注册账号
     * 
     * @return void
     */
    public function register()
    {
        $params = I('post.');
        $result = D('Account')->register($params);
        echo json_encode($result);
    }

    /**
     * 登录
     * 
     * @return void
     */
    public function login()
    {
        $params = I('post.');
        $result = D('Account')->login($params);
        echo json_encode($result);
    }

    /**
     * 检查是否登录
     * 
     * @return void
     */
    public function isLogin()
    {
        $result = D('Account')->isLogin();
        echo json_encode($result);
    }

    /**
     * 退出登录
     * 
     * return void
     */
    public function logout()
    {
        $result = D('Account')->logout();
        echo json_encode($result);
    }

}
