<?php

/**
 * 审核订单相关
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
 * The model class of Review Indent
 *
 * @category ReviewIndent
 * @package  ReviewIndent_Model
 * @author   haohan <uglyboyhans@126.com>
 * @license  http://www.cqu.edu.cn  None
 * @link     http://www.cqu.edu.cn 
 * 
 */
class ReviewIndentModel extends Model
{

    //数据库表，查不到会报错:
    protected $tableName = 'indentreviewlist';
    //用户id：
    protected $adminId;

    /**
     * 构造函数
     * 
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->adminId = session('adminLogin');
    }

    /**
     * 查看未审核订单
     * 
     * @return array
     */
    public function viewIndent()
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        //indentreviewlist:r | productdetail:p | indent:i
        $sql = "select r.reviewid,r.indent,p.productname,p.productid,"
            . " i.count,i.remark,r.createtime"
            . " from indentreviewlist r"
            . " left join indent i on r.indent=i.indentid"
            . " left join productdetail p on i.product=p.productid"
            . " where r.admin is null and r.isdelete=0"
            . " order by r.createtime"; //按时间顺序，越早下单越靠前
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "查询失败，或暂无订单"
            ];
        } else {
            return [
                "rusult" => 0,
                "count" => count($result),
                "indentreviewlist" => $result,
            ];
        }
    }

    /**
     * 开始审核
     * 
     * @param array $args 审核清单id
     * 
     * @return array
     */
    public function startReview($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $reviewid = $args['reviewid'];
        if (!$reviewid) {
            return [
                "result" => 1,
                "error" => "参数错误",
            ];
        }
        $sql = "update indentreviewlist set"
            . " admin = $this->adminId,updatetime=now()"
            . " where reviewid=$reviewid";
        if ($this->execute($sql) !== false) {
            return [
                "result" => 0,
            ];
        } else {
            return [
                "result" => 1,
                "error" => "操作失败",
            ];
        }
    }

    /**
     * 查看属于我审核的订单
     * 
     * @return array
     */
    public function myReview()
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        //indentreviewlist:r | productdetail:p | indent:i
        $sql = "select r.reviewid,r.indent,r.status,"
            . " p.productname,p.productid,i.remark,i.count,r.createtime"
            . " from indentreviewlist r"
            . " left join indent i on r.indent=i.indentid"
            . " left join productdetail p on i.product=p.productid"
            . " where r.admin=$this->adminId and r.isdelete=0"
            . " order by r.createtime"; //按时间顺序，越早下单越靠前
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "查询失败，或暂无订单"
            ];
        } else {
            return [
                "rusult" => 0,
                "count" => count($result),
                "indentreviewlist" => $result,
            ];
        }
    }

    /**
     * 发货
     * 
     * @param array $args 审核清单id,物流信息
     * 
     * @return array
     */
    public function sendOutProduct($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }
        $reviewid = $args['reviewid'];
        $company = $args['company'];
        $logisticsNumber = $args['logisticsnumber'];
        if (!$reviewid || !$company || !$logisticsNumber) {
            return [
                "result" => 1,
                "error" => "参数错误",
            ];
        }
        //获取订单号
        $sql = "select indent from indentreviewlist where reviewid=$reviewid";
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "获取订单信息失败",
            ];
        } else {
            $indent = $result[0]['indent'];
        }

        //添加物流信息
        $sql = "insert into logisticslist (indent,company,logisticsnumber)"
            . " values ($indent,'$company','$logisticsNumber')";
        if ($this->execute($sql) === false) {
            return [
                "result" => 1,
                "error" => "添加物流信息失败",
            ];
        }
        //改变订单状态:
        $sql = "update indent set status=1 where indentid = $indent";
        if ($this->execute($sql) === false) {
            return [
                "result" => 1,
                "error" => "更新订单状态失败",
            ];
        }
        //改变审核状态:
        $sql = "update indentreviewlist set status=1 where reviewid=$reviewid";
        if ($this->execute($sql) === false) {
            return [
                "result" => 1,
                "error" => "更新订单审核清单状态失败",
            ];
        } else {
            return [
                'result' => 0
            ];
        }
    }

    /**
     * 查看所有未处理退款
     * 
     * @return array
     */
    public function viewDrawback()
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }

        //drawbacklist:d | productdetail:p | indent:i
        $sql = "select d.drawbackid,d.indent,d.reason,"
            . " p.productname,p.productid,i.count,d.createtime"
            . " from drawbacklist d"
            . " left join indent i on d.indent=i.indentid"
            . " left join productdetail p on i.product=p.productid"
            . " where d.admin is null and d.isdelete=0 and d.status<4"
            . " order by d.createtime"; //按时间顺序，越早申请越靠前
        $result = $this->query($sql);
        if (!$result) {
            return [
                "result" => 1,
                "error" => "查询失败，或暂无退款申请"
            ];
        } else {
            return [
                "rusult" => 0,
                "count" => count($result),
                "drawbacklist" => $result,
            ];
        }
    }

    /**
     * 处理退款
     * 
     * @param array $args 退款清单id,处理状态
     * 
     * @return array
     */
    public function dealDrawback($args)
    {
        if (!$this->adminId) {//检查登录
            return [
                "result" => 2,
                "error" => "管理员未登录",
            ];
        }

        $drawbackid = $args['drawbackid'];
        $status = $args['status'];
        if (!$drawbackid || !in_array(intval($status), [1, 2, 3])) {
            return [
                "result" => 1,
                "error" => "参数错误",
            ];
        }

        $sql = "update drawbacklist set"
            . " status=$status,"
            . " admin=$this->adminId,"
            . " updatetime=now()"
            . " where drawbackid = $drawbackid";
        if ($this->execute($sql) === false) {
            return [
                "result" => 1,
                "error" => "修改退款清单状态失败"
            ];
        }

        //当退款完成：修改订单状态，从订单审核删除
        if (3 === intval($status)) {
            $sql = "select indent from drawbacklist"
                . " where drawbackid = $drawbackid";
            $result = $this->query($sql);
            if (!$result) {
                return [
                    "result" => 1,
                    "error" => "查询订单信息失败"
                ];
            } else {
                $indent = $result[0]['indent'];
                $sql = "update indent set status=5,updatetime=now()"
                    . " where indentid=$indent";
                if ($this->execute($sql) === false) {
                    return [
                        "result" => 1,
                        "error" => "更新订单信息失败"
                    ];
                } else {
                    $sql = "update indentreviewlist set isdelete=1"
                        . " where indent=$indent";
                    if ($this->execute($sql) === false) {
                        return [
                            "result" => 1,
                            "error" => "删除审核清单失败"
                        ];
                    }
                }
            }
        }

        return [
            "result" => 0
        ];
    }

}
