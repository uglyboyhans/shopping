<?php

/**
 * 审核订单模块单元测试
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
 * 订单审核模块model层单元测试用例
 *
 * @category Test
 * @package  ReviewIndent_Test
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ReviewIndentModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ReviewIndentModel
     */
    protected $object;

    /**
     * 建立基境
     * 
     * @return void
     */
    protected function setUp()
    {
        //模拟登录:
        session('adminLogin', 1);
        $this->object = new ReviewIndentModel;
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
     * 测试查看未审核订单
     * 
     * @return void
     */
    public function testViewIndent()
    {
        $result = $this->object->viewIndent();
        $this->assertGreaterThanOrEqual(0, $result['count']);
        $this->assertTrue(is_array($result['indentreviewlist']));
    }

    /**
     * 测试开始审核
     * 
     * @return void
     */
    public function testStartReview()
    {
        $args = [
            'reviewid' => 5,
        ];
        $this->assertEquals(0, $this->object->startReview($args)['result']);
    }

    /**
     * 测试查看属于我审核的订单
     * 
     * @return void
     */
    public function testMyReview()
    {
        $result = $this->object->myReview();
        $this->assertGreaterThanOrEqual(0, $result['count']);
        $this->assertTrue(is_array($result['indentreviewlist']));
    }

    /**
     * 测试发货
     * 
     * @return void
     */
    public function testSendOutProduct()
    {
        $args = [];
        $this->assertEquals(1, $this->object->sendOutProduct($args)['result']);
        $args = [
            "reviewid" => 5,
            "company" => 'G7',
            "logisticsnumber" => "avsr3232325",
        ];
        $this->assertEquals(0, $this->object->sendOutProduct($args)['result']);
    }

    /**
     * 测试查看退款申请
     * 
     * @return void
     */
    public function testViewDrawback()
    {
        $result = $this->object->viewDrawback();
        $this->assertGreaterThanOrEqual(0, $result['count']);
        $this->assertTrue(is_array($result['drawbacklist']));
    }

    /**
     * 测试处理退款
     * 
     * @return void
     */
    public function testDealDrawback()
    {
        $args = []; //测试参数错误
        $this->assertEquals(1, $this->object->dealDrawback($args)['result']);
        $args = [
            'drawbackid' => 1,
            "status" => 1,
        ];
        $this->assertEquals(0, $this->object->dealDrawback($args)['result']);
        $args = [
            'drawbackid' => 1,
            "status" => 2,
        ];
        $this->assertEquals(0, $this->object->dealDrawback($args)['result']);
        $args = [
            'drawbackid' => 2,
            "status" => 3,
        ];
        $this->assertEquals(0, $this->object->dealDrawback($args)['result']);
    }

}
