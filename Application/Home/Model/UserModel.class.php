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

namespace Home\Model;

use Think\Model;

/**
 * The model class of User
 *
 * @category User
 * @package  User_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class UserModel extends Model
{

    //数据库表，查不到会报错
    protected $tableName = 'userinfo';
    //用户id
    protected $userid;

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->userid = session('login');
    }

    /**
     * 编辑用户个人信息
     * 
     * @param array $args 用户个人信息
     * 
     * @return array
     */
    public function editUserInfo($args)
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }
        $realname = $args['realname'] ? $args['realname'] : "";
        $idcard = $args['idcard'] ? $args['idcard'] : "";
        $address1 = $args['address1'] ? $args['address1'] : "";
        $address2 = $args['address2'] ? $args['address2'] : "";
        $address3 = $args['address3'] ? $args['address3'] : "";
        $address4 = $args['address4'] ? $args['address4'] : "";
        $icon = $args['icon'] ? $args['icon'] : "";
        $email = $args['email'] ? $args['email'] : "";
        $birthdate = $args['birthdate'] ? $args['birthdate'] : "";
        $phonenumber = $args['phonenumber'] ? $args['phonenumber'] : "";
        $gender = intval($args['gender']) >= 0 ? intval($args['gender']) : 2;
        if ($gender !== 0 && $gender !== 1 & $gender !== 2) {
            $gender = 2;
        }
        //sql:若存在就update.不存在就insert
        $sql = "insert into userinfo"
            . " (user,realname,idcard,address1,address2,address3,address4,"
            . " icon,email,birthdate,phonenumber,gender)"
            . " values"
            . " ($this->userid,'$realname','$idcard','$address1','$address2','$address3','$address4',"
            . " '$icon','$email','$birthdate','$phonenumber',$gender)"
            . " ON DUPLICATE KEY UPDATE"
            . " realname = '$realname',"
            . " idcard = '$idcard',"
            . " address1 = '$address1',"
            . " address2 = '$address2',"
            . " address3 = '$address3',"
            . " address4 = '$address4',"
            . " icon = '$icon',"
            . " email = '$email',"
            . " birthdate = '$birthdate',"
            . " phonenumber = '$phonenumber',"
            . " gender = $gender";
        if ($this->execute($sql) !== false) {
            return [
                "result" => 0
            ];
        } else {
            return [
                "result" => 1,
                "error" => "保存失败"
            ];
        }
    }

    /**
     * 获取地址
     * 
     * @return array
     */
    public function getAddress()
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }
        $sql = "select address1,address2,address3,address4 from userinfo"
            . " where user=$this->userid";
        $result = $this->query($sql);
        if ($result) {
            return [
                "result" => 0,
                "address" => $result,
            ];
        } else {
            return [
                "result" => 1,
                "error" => '查询失败，也许您尚未设置自己的地址信息'
            ];
        }
    }

    /**
     * 全量获取用户信息
     * 
     * @return array
     */
    public function getUserInfo()
    {
        if (!$this->userid) {//检查登录
            return [
                "result" => 2,
                "error" => '用户未登录'
            ];
        }
        $sql = "select * from userinfo"
            . " where user=$this->userid";
        $result = $this->query($sql);
        if ($result) {
            $result[0]['birthdate'] = date("Y-m-d", strtotime($result[0]['birthdate']));
            return [
                "result" => 0,
                "userinfo" => $result,
            ];
        } else {
            return [
                "result" => 1,
                "error" => '查询失败，也许您尚未设置自己的详细信息'
            ];
        }
    }

}
