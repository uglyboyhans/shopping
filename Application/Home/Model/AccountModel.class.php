<?php

namespace Home\Model;

use Think\Model;

class AccountModel extends Model
{

    protected $tableName = 'memberaccount';
    protected $userid;

    public function __construct()
    {
        parent::__construct();
        $this->userid = session('login');
    }

    public function register($args)
    {
        $account = $args['account'];
        $password = $args['password'];
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

    public function login($args)
    {
        $account = $args['account'];
        $password = $args['password'];
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
                "result" => 0,
                "userid" => $result[0]['userid']
            ];
        }
    }

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

}
