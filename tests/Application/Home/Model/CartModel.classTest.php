<?php

/**
 * 购物车模块单元测试
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
 * 购物车模块model层单元测试用例
 *
 * @category Test
 * @package  Cart_Test
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class CartModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CartModel
     */
    protected $object;
    
    protected $indent;

    /**
     * 建立基境
     * 
     * @return void
     */
    protected function setUp()
    {
        //模拟登录
        session("login", 5);

        $this->object = new CartModel;
        $this->indent = new IndentModel;
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
     * 测试添加到购物车
     * 
     * @return void
     */
    public function testAddToCart()
    {
        $args = [
            "productid" => 3,
            "count" => -2,
        ];
        $this->assertEquals(0, $this->object->addToCart($args)->result);
        $args = [
            "productid" => 2,
            "count" => 4,
        ];
        $this->assertEquals(0, $this->object->addToCart($args)->result);
    }

    /**
     * 测试查看购物车
     * 
     * @return void
     */
    public function testViewCart()
    {
        $this->assertGreaterThanOrEqual(0, $this->object->viewCart()->count);
    }

    /**
     * 测试从购物车删除
     * 
     * @return void
     */
    public function testDelete()
    {
        $testargs1 = [
            "cartid" => [
                3, 5, 14
            ],
        ];
        $this->assertEquals(0, $this->object->delete($testargs1)->result);
        $testargs2 = [
            "cartid" => 4
        ];
        $this->assertEquals(0, $this->object->delete($testargs2)->result);
    }

    /**
     * 测试从购物车付款
     * 
     * @return void
     */
    public function testPayFromCart()
    {
        //要调用其他模块，没法测
        //框架问题，用mock也没法测
    }

}
