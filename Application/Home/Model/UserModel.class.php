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
        $gender = $args['gender'] ? $args['gender'] : 2;
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
        if ($this->execute($sql) >= 0) {
            $result = [
                "result" => 0
            ];
        } else {
            $result = [
                "result" => 1
            ];
        }
        return $result;
    }

}
