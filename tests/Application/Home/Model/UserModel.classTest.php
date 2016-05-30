<?php

/**
 * 用户模块单元测试
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

/**
 * 用户模块model层单元测试用例
 *
 * @category Test
 * @package  User_Test
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class UserModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var UserModel
     */
    protected $object;

    /**
     * 建立基境
     * 
     * @return void
     */
    protected function setUp()
    {
        //模拟登录
        session("login", 5);
        $this->object = new UserModel;
    }

    /**
     * 释放基境
     * 
     * @return void
     */
    protected function tearDown()
    {
        
    }

    /**
     * 测试编辑用户信息
     * 
     * @return void
     */
    public function testEditUserInfo()
    {
        $args = [
            "realname" => '郝瀚',
            "idcard" => '123456789012345678',
            "address1" => "neijiang",
            "icon" => "me.png",
            "email" => "haohan@126.com",
            "birthdate" => '1993-12-18'
        ];
        $result = $this->object->editUserInfo($args);
        $this->assertEquals(0, $result['result']);
    }

    /**
     * 测试获取地址
     * 
     * @return void
     */
    public function testGetAddress()
    {
        $result = $this->object->getAddress();
        $this->assertEquals(0, $result['result']);
        $this->assertTrue(is_array($result['address']));
    }

    /**
     * 测试全量获取用户信息
     * 
     * @return void
     */
    public function testGetUserInfo()
    {
        $result = $this->object->getUserInfo();
        $this->assertEquals(0, $result['result']);
        $this->assertTrue(is_array($result['userinfo']));
    }

}
