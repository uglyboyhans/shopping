<?php

/**
 * 账号相关
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
 * The model class of Account
 *
 * @category Account
 * @package  Account_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class AccountModel extends Model
{

    //数据库表，查不到会报错
    protected $tableName = 'memberaccount';
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
     * 注册
     * 
     * @param array $args 账号密码
     * 
     * @return array 结果代码
     */
    public function register($args)
    {
        $account = $args['account'];
        $password = $args['password'];
        //先用户检查是否存在
        $sql = "select userid from memberaccount where account = '$account'";
        $result = $this->query($sql);
        if ($result) {
            return [
                "result" => 1
            ];
        }
        $sql = "insert into memberaccount (account,password) values ('$account',password('$password'))";
        if ($this->execute($sql)) {
            return [
                "result" => 0
            ];
        } else {
            return [
                "result" => 2
            ];
        }
    }

    /**
     * 登录
     * 
     * @param array $args 账号密码
     * 
     * @return array 结果代码
     */
    public function login($args)
    {
        $account = $args['account'];
        $password = $args['password'];
        //先用户检查是否存在
        $sql = "select userid from memberaccount where account = '$account'";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1
            ];
        }
        $sql = "select userid from memberaccount where account = '$account' and password=password('$password')";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 2
            ];
        } else {
            session('login', $result[0]['userid']);
            return [
                "result" => 0
            ];
        }
    }

    /**
     * 检查是否登录
     * 
     * @return array 结果代码
     */
    public function isLogin()
    {
        if (!$this->userid) {
            $result = [
                "result" => 1
            ];
        } else {
            $result = [
                "result" => 0
            ];
        }
        return $result;
    }

    /**
     * 退出登录
     * 
     * @return array
     */
    public function logout()
    {
        session(null);
        return [
            "result " => 0
        ];
    }

}
