<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/19
 * Time: 13:25
 */
namespace Admin\Controller;
use Think\Controller;
class DemoController extends Controller{
    public function getOrderByName()
    {
        $username = I('post.username');
        $user = M('Order')
             ->alias('o')
                ->field('o.*,u.username,g.goods_name')
                    ->join("left join tpshop_user as u on o.user_id = u.id")
                        ->where("u.username like '%$username%'")
                            ->select();
        $return = array(
          'code' => 10000,
            'msg' => 'success',
            'data' => $user,
        );
        $this->ajaxReturn($return);
    }
    public function getOrderByTime()
    {
        $time2 = I('post.time2');
        $time1 = I('post.time1');
        $time2 = strtotime($time2);
        $time1 = strtotime($time1);
        $user = M('Order')
            ->alias('o')
                ->field('o.*,u.username,g.goods_name')
                    ->join("left join tpshop_user as u on o.user_id = u.id")
                        ->where("strtotime(o.create_order_time) between $time1 and $time2")
                            ->select();
        $return = array(
            'code' => 10000,
            'msg' =>'success',
            'data' => $user
        );
        $this->ajaxReturn($return);
    }



}