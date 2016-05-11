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
        //拿上传的头像
        $upload = new \Think\Upload();
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = 'Uploads/'; // 设置附件上传根目录
        $upload->savePath = 'icon/'; // 设置附件上传（子）目录
        $upload->saveName = md5(uniqid());
        //get extention of the file:
        $nameReverse = strrev($_FILES["icon"]['name']); //reverse the file name
        $cutString = explode(".", $nameReverse);
        $upload->saveExt = strrev($cutString[0]);
        $upload->autoSub = false;
        // 上传文件
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            //$this->error($upload->getError());
        } else {// 上传成功
            $params['icon'] = C('webroot') . $upload->rootPath . $upload->savePath . $upload->saveName . '.' . $upload->saveExt;
        }
        $result = D("User")->editUserInfo($params);
        echo json_encode($result);
    }

    /**
     * 获取地址
     * 
     * @return void
     */
    public function getAddress()
    {
        $result = D('User')->getAddress();
        echo json_encode($result);
    }

    /**
     * 全量获取用户信息
     * 
     * @return void
     */
    public function getUserInfo()
    {
        $result = D('User')->getUserInfo();
        echo json_encode($result);
    }

}
