<?php

/**
 * 商品模块单元测试
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
 * 商品模块model层单元测试用例
 *
 * @category Test
 * @package  Product_Test
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ProductModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ProductModel
     */
    protected $object;

    /**
     * 建立基境
     * 
     * @return void
     */
    protected function setUp()
    {
        $this->object = new ProductModel;
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
     * 测试搜索商品
     * 
     * @return void
     */
    public function testSearchProduct()
    {
        $args = [
            "productname" => "",
            "byprice" => 1,
            "bysales" => 0,
            "pageindex" => 0,
            "pagesize" => 12,
        ];
        $this->assertGreaterThanOrEqual(0, $this->object->searchProduct($args)->count);
    }

    /**
     * 测试查看单个商品详情
     * 
     * @return void
     */
    public function testProductDetail()
    {
        $args['productid'] = 1;
        $this->assertTrue(is_array($this->object->productDetail($args)));
    }

}
