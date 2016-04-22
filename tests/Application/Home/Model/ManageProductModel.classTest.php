<?php
/**
 * 管理商品模块单元测试
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
 * 管理商品模块model层单元测试用例
 *
 * @category Test
 * @package  ManageProduct_Test
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ManageProductModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ManageProductModel
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
        session('adminLogin', 1);
        $this->object = new ManageProductModel;
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
     * 测试添加商品
     * 
     * @return void
     */
    public function testCreateProduct()
    {
        $args = [
            "productname" => "单元测试",
            "price" => 992,
            "chargeunit" => "套",
            "stock" => 2333,
            "content" => "这是单元测试",
        ];
        $this->assertEquals(0, $this->object->createProduct($args)->result);
    }

    /**
     * 测试搜索商品
     * 
     * @return void
     */
    public function testSearchProduct()
    {
        $args = [
            "pageindex" => 0,
            "pagesize" => 0,
        ];
        $this->assertGreaterThanOrEqual(0, $this->object->searchProduct($args)->count);
    }

    /**
     * 测试将商品置为使用
     * 
     * @return void
     */
    public function testSetUse()
    {
        $args = [
            "productid" => 1,
        ];
        $this->assertEquals(0, $this->object->setUse($args)->result);
    }

    /**
     * 测试将商品置为存档
     * 
     * @return void
     */
    public function testSetNoUse()
    {
        $args = [
            "productid" => 1,
        ];
        $this->assertEquals(0, $this->object->setNoUse($args)->result);
    }

    /**
     * 测试删除商品
     * 
     * @return void
     */
    public function testDeleteProduct()
    {
        $args = [
            "productid" => 1,
        ];
        $this->assertEquals(0, $this->object->deleteProduct($args)->result);
    }

    
    /**
     * 测试编辑商品获取信息
     * 
     * @return void
     */
    public function testEditProduct()
    {
        $args = [
            "productid" => 1,
        ];
        $this->assertTrue(is_array($this->object->editProduct($args)));
    }

    
    /**
     * 测试保存编辑状态
     * 
     * @return void
     */
    public function testSaveEdit()
    {
        $args = [
            "productid" => 1,
            "productname" => "单元测试" . time(),
            "price" => 122,
            "abstract" => "单元测试"
        ];
        $this->assertGreaterThanOrEqual(0, ($this->object->saveEdit($args)->result));
    }

}
