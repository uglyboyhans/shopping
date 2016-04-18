<?php

/**
 * 用户相关
 *
 * PHP version 5
 *
 * @category Appserver
 * @package  Shopping
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 *
 */

namespace Home\Controller;

use Think\Controller;

/**
 * The control class of User
 *
 * @category User
 * @package  User_Controller
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class UserController extends Controller
{

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
     * 编辑个人信息
     * 
     * @return void
     */
    public function editUserInfo()
    {
        $params = I("post.");
        $result = D("User")->editUserInfo($params);
        echo json_encode($result);
    }

}
