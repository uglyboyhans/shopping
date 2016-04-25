<?php

/**
 * 订单模块单元测试
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
 * 订单模块model层单元测试用例
 *
 * @category Test
 * @package  Indent_Test
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class IndentModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var IndentModel
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

        $this->object = new IndentModel;
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
     * 测试单个付款
     * 
     * @return void
     */
    public function testPayForOne()
    {
        $args = [];
        $this->assertEquals(1, $this->object->payForOne($args)['result']);
        $args = [
            "productid" => 3,
            "count" => -2,
        ];
        $this->assertEquals(0, $this->object->payForOne($args)['result']);
    }

    /**
     * 测试从购物车批量付款
     * 
     * @return void
     */
    public function testPayForCart()
    {
        $args = [
            3, 6, 18
        ];
        $this->assertEquals(0, $this->object->payForCart($args)['result']);
    }

    /**
     * 测试查看订单
     * 
     * @return void
     */
    public function testViewIndent()
    {
        // Remove the following lines when you implement this test.
        $this->assertGreaterThanOrEqual(0, $this->object->viewIndent()['count']);
    }

    /**
     * 测试删除
     * 
     * @return void
     */
    public function testDelete()
    {
        $args = [
            "indentid" => 2
        ];
        $this->assertGreaterThanOrEqual(0, $this->object->delete($args)['result']);
    }

    /**
     * 测试申请退款
     * 
     * @return void
     */
    public function testApplyDrawback()
    {
        $args = [
            "indentid" => 3
        ];
        $this->assertEquals(0, $this->object->applyDrawback($args)['result']);
    }

    /**
     * 测试收货
     * 
     * @return void
     */
    public function testReceipt()
    {
        $args = [
            "indentid" => 1
        ];
        $this->assertEquals(0, $this->object->receipt($args)['result']);
    }

    /**
     * 测试添加评价
     * 
     * @return void
     */
    public function testComment()
    {
        $args = [
            "indentid" => 2,
            "content" => '单元测试',
            "score" => -222,
        ];
        $this->assertEquals(0, $this->object->comment($args)['result']);
        $args = [
            "indentid" => 4,
            "score" => 3.3,
        ];
        $this->assertEquals(0, $this->object->comment($args)['result']);
    }

}
