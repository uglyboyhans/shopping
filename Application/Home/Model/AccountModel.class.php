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
        //先检查用户是否存在
        $sql = "select userid from memberaccount where account = '%s'";
        $paramArray = [$account];
        $result = $this->query($sql, $paramArray);
        if ($result) {
            return [
                "result" => 2,
                "error" => '用户名已存在'
            ];
        }
        $sql = "insert into memberaccount (account,password) values ('%s',password('%s'))";
        $paramArray = [$account, $password];
        if ($this->execute($sql, $paramArray)) {
            return [
                "result" => 0
            ];
        } else {
            return [
                "result" => 1,
                "error" => '注册失败',
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
        //先检查用户是否存在
        $sql = "select userid from memberaccount where account = '%s'";
        $paramArray = [$account];
        $result = $this->query($sql, $paramArray);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "用户不存在"
            ];
        }
        $sql = "select userid from memberaccount where account = '%s' and password=password('%s')";
        $paramArray = [$account, $password];
        $result = $this->query($sql, $paramArray);
        if (!$result) {
            return [
                "result" => 2,
                "error" => '密码错误'
            ];
        } else {
            session('login', $result[0]['userid']);
            return [
                "result" => 0
            ];
        }
    }

    /**
     * 管理员登录
     * 
     * @param array $args 账号密码
     * 
     * @return array 结果代码
     */
    public function adminLogin($args)
    {
        session(null); //先清空session
        $account = $args['account'];
        $password = $args['password'];
        //先检查用户是否存在
        $sql = "select adminid from adminaccount where adminaccount = '%s'";
        $paramArray = [$account];
        $result = $this->query($sql, $paramArray);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "用户不存在"
            ];
        }
        $sql = "select adminid from adminaccount where adminaccount = '%s' and password=password('%s')";
        $paramArray = [$account, $password];
        $result = $this->query($sql, $paramArray);
        if (!$result) {
            return [
                "result" => 2,
                "error" => '密码错误'
            ];
        } else {
            session('adminLogin', $result[0]['adminid']);
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
        if ($this->userid) {
            $result = [
                "result" => 0
            ];
        } elseif (session('adminLogin')) {
            $result = [
                "result" => 2
            ];
        } else {
            $result = [
                "result" => 1
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
            "result" => 0
        ];
    }

}
