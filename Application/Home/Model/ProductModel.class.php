<?php

/**
 * 商品相关
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
 * The model class of Product
 *
 * @category Product
 * @package  Product_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ProductModel extends Model
{

    //数据库表，查不到会报错
    protected $tableName = 'productdetail';
    //管理员id
    protected $userId;

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->userId = session('login');
    }

    /**
     * 查找商品
     * 
     * @param array $args 搜索条件
     * 
     * @return array
     */
    public function searchProduct($args)
    {
        $productname = $args['productname'] ? $args['productname'] : '';
        $byPrice = $args['byprice'] ? $args['byprice'] : 0;
        $byScore = $args['byscore'] ? $args['byscore'] : 0;
        $bySales = $args['bysales'] ? $args['bysales'] : 0;
        $pageIndex = $args['pageindex'] > 0 ? $args['pageindex'] : 1;
        $pageSize = $args['pagesize'] > 0 ? $args['pagesize'] : 24;

        $sql = "select d.productid,d.productname,d.price,d.cover,s.sales,s.avescore"
            . " from productdetail d left join productsummary s on d.productid = s.product"
            . " where d.isused=1 and d.isdelete=0 and d.productname like '%$productname%'"
            . " order by s.summaryid desc";
        if ($byPrice == 1) {
            $sql = $sql . ",d.price desc";
        }
        if ($byScore == 1) {
            $sql = $sql . ",s.avescore";
        }
        if ($bySales == 1) {
            $sql = $sql . ",s.sales";
        }
        $start = ($pageIndex - 1) * $pageSize;
        $sql = $sql . " limit $start,$pageSize";
        $result = $this->query($sql); //结果集
        if ($result) {
            return [
                'result' => 0,
                'count' => count($result),
                'product' => $result,
                'pageindex' => $pageIndex,
                'pagesize' => $pageSize,
            ];
        } else {
            return[
                'result' => 1,
                "error" => "搜索失败，或没有符合查询条件的商品"
            ];
        }
    }

    /**
     * 获取商品详情
     * 
     * @param array $args 商品id
     * 
     * @return array
     */
    public function productDetail($args)
    {
        $productId = $args['productid'];
        $sql = "select d.productname,d.price,d.cover,s.sales,s.avescore,"
            . " d.chargeunit,d.stock,d.abstract,d.content,d.createtime"
            . " from productdetail d"
            . " left join productsummary s on d.productid = s.product"
            . " where d.productid = $productId and d.isdelete=0 and d.isused=1";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "获取商品信息失败"
            ];
        } else {
            $result[0]['content'] = htmlspecialchars_decode($result[0]['content']);
            return [
                "result" => 0,
                "product" => $result[0],
            ];
        }
    }

}
