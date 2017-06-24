<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Created by PhpStorm.
 * User: kingf
 * Date: 2017/6/3
 * Time: 19:36
 */


//刷新登录
class LoginController extends Controller{
//    public function Login(){
//        if(!IS_POST){
//            $this->display();
//        }else{
//
//
//
//
//            $data = I('post.');
//
//            //实例化模型类
//            $model = D('Manager');
//            //使用create方法创建数据集
//            if(!$model -> create($data)){
//                //获取模型的错误信息
//                $error = $model -> getError();
//                $this -> error($error,"",1);
//            }
//
//            $verify=new \Think\Verify;
//            //check verify
//            if(!($verify->check($data['verify']))){
//                //$this->error('verfy is wrong, try agin');  //close verify
//            }
//            $res=$model->where(array('username'=> "$data[username]"))->find();
//
//
//            if($res&&($res['password']==$data['password'])){
//                session('manager_user',$res);
//                $this->success('Login success',U('Admin/Index/index'));
//            }else{
//                $this->error('Login error',U('Admin/Login/login'));
//            }
//        }
//    }

    /**
     *ajax to no refresh
     */
    public function AjaxLogin (){
        $data=I('post.');
        $model=M('manager');
        $res=$model->where("username='$data[username]'")->find();
        //$res=$model->getLastSql();
        //dump($res);
        if($res&&($res['password']==$data['password'])){
            session('manager_user',$res);
//            todo 错误次数
            $arr=array(
                'code' => 200,
                'msg'  => 'success',
                'data' => $res
            );
            $this->ajaxReturn($arr);
        }else{

            $model_e=M('Error');
            $res_e=$model_e->where("user_id='$data[username]'")->find();
            $error_res['user_id']=$data['username'];
//            dump($error_res);die;
            if($res_e){
                $newtime=time();
                $time_long=$newtime-$res_e['error_time'];
                if(!($res_e['error_num']>=5)){
                        $error_res['error_num']=$res_e['error_num'] +1;
                        $error_res['error_time']=$res_e['error_time'];
                        $error_res['id']=$res_e['id'];
                        $model_e->save($error_res);
                        $resnum="password or username is wrong</br>你已经错误的尝试了".$error_res['error_num']."次";
                        $this->ajaxReturn(array('code'=>201,'msg'=>"$resnum"));die;
                    }else if($time_long<=(60*60*24)){   //60*60*24
                        $this->ajaxReturn(array('code'=>201,'msg'=>'您在1天之内错误5次请24小后尝试'));die;
                    }else{
                        $error_res['error_time']=time();
                        $error_res['error_num']=1;
                        $error_res['id']=$res_e['id'];
                        $model_e->save($error_res);
                        $this->ajaxReturn(array('code'=>201,'msg'=>"password or username is wrong</br>你已经错误尝试了1次"));
                }


                }else{
                    $error_res['error_time']=time();
                    $error_res['error_num']=1;
                    $model_e->add($error_res);
                    $this->ajaxReturn(array('code'=>201,'msg'=>"password or username is wrong</br>你已经错误尝试了1次"));
                }





        }

    }
    /**
     *create verify fun
     */
    public function captcha(){
        $conf=array(
            'useZh'     =>  false,           // 使用中文验证码
            'useImgBg'  =>  false,           // 使用背景图片
            'fontSize'  =>  25,              // 验证码字体大小(px)25
            'useCurve'  =>  false,            // 是否画混淆曲线
            'useNoise'  =>  true,            // 是否添加杂点
            'imageH'    =>  0,               // 验证码图片高度
            'imageW'    =>  0,               // 验证码图片宽度
            'length'    =>  4,               // 验证码位数
            'fontttf'   =>  '',              // 验证码字体，不设置随机获取
            'bg'        =>  array(243, 251, 254),  // 背景颜色
            'reset'     =>  true,           // 验证成功后是否重置
        );
        $verify=new \Think\Verify($conf);
        $verify->entry();
    }
    public function Logout(){

        session(null);
        $this->redirect('Admin/Login/login');
    }
}