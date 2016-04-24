<?php

/**
 * 反馈模块单元测试
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
 * 反馈模块model层单元测试用例
 *
 * @category Test
 * @package  Feedback_Test
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class FeedbackModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FeedbackModel
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //模拟用户登录:
        session("login", 5);
        //模拟管理员登录：
        session("adminLogin", 1);
        $this->object = new FeedbackModel;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * 测试添加反馈
     * 
     * @return void
     */
    public function testAddFeedback()
    {
        $args = [
            "content" => "单元测试"
        ];
        $this->assertEquals(0, $this->object->addFeedback($args)->result);
    }

    /**
     * 测试管理员查看反馈
     * 
     * @return void
     */
    public function testViewFeedback()
    {
        $args = [
            "bytime" => 1,
            "pageindex" => -3,
            "pagesize" => 0
        ];
        $result = $this->object->viewFeedback($args);
        $this->assertGreaterThanOrEqual(0, $result->count);
    }

}
