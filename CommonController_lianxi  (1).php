<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/24
 * Time: 21:51
 */
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller{
    public function __construct()
    {
        //要先继承父类的构造方法
        parent::__controller();
        //这个session是在用户登录的时候生成的
        //判断是否已经登录
        if(!session('?manager_user')){
            $this->error('请先登录',U('Admin/Login/login'));
        }

    }
    public function getNav()
    {
        //判断是否顶级目录已经显示  如果已经存在的话直接返回 不存在的话再获取
        if(session('?top_nav')&&session('?second_nav')){
            return;
        }
        //再创建一个方法来获取所拥有的权限
        $this->get_nav();
    }
    //左上角的列表的显示
    public function get_nav()
    {
        //根据用户的登录信息  来获取管理员用户的id
        $role_id = session('manager_user.role_id');
        $model = M('auth');
        if($role_id == 1){  //等于1的话 就是超级管理员 这个拥有全部的权限
            //获取全部的权限   is_nav这个0是不显示  1是显示
            $top = $model->where("pid = 0 and is_nav = 1")->select();
            $second = $model->where("pid > 0 and is_nav = 1")->select();
        }else{
            //role_id不是1的情况下  就获取他所拥有的权限
            $roleModel = M('role');
            $roleRow = $roleModel->find($role_id);
            $role_auth_ids = $roleRow['role_auth_ids'];
            //where里面必须要用双引号  因为要解析里面的内容
            $top = $model->where("pid = 0 and id in ($role_auth_ids) and is_nav = 1")->select();
            $second = $model->where("pid > 0 and id in ($role_auth_ids) and is_nav = 1")->select();
        }
        session('top_nav',$top);
        session('second_nav',$second);
    }
    //管理员登录后  能够访问的页面  获取它的权限
    public function checkAuth()
    {
        $role_id = session('manager_user.role_id');
        if($role_id == 1){
            return;
        }
        //获取用户权限所拥有的权限的控制器和方法
        $c = CONTROLLER_NAME;
        $a = ACTION_NAME;
        //首页都可以访问
        if(strtolower($c) == 'index' && strtolower($a) == 'index'){
            return;
        }
        if(strtolower($c) == 'index' && strtolower($a) == 'logout'){
            return;
        }
        //根据role_id来获取role_auth_ac
        $role_auth = M('Role')->find($role_id);
        $role_auth_ac = $role_auth['role_auth_ac'];
        //这个获取出来的是字符串  不能判断  需要转化为数组
        $ac_arr = explode(',',$role_auth_ac);
        $url_ac = $c.'-'.$a;
        if(!in_array($url_ac,$ac_arr)){
            $this->error('无权访问',U('Admin/Index/index'));
        }
    }
}
