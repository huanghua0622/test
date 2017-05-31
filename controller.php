<?php
//作用是引入Smarty到项目
class Controller{
	public $smarty; //存放Smarty 的对象，供子类使用

	public function __construct(){
		include_once FRAME_DIR . 'Smarty/Smarty.class.php';
		$this->smarty = new Smarty(); //实例化Smarty，赋值给当前类中的成员属性
		//设置模板目录
		$this->smarty->setTemplateDir(VIEW_PATH . C);
		//设置编译目录
		$this->smarty->setCompileDir(APP_PATH . 'Runtime');
	}

	//重写Smarty的assign方法
	public function assign($key, $value){
		$this->smarty->assign($key, $value);
	}

	//重写display方法
	public function display($template){
		$this->smarty->display($template);
	}

	//成功跳转方法
    //三个参数的意思成功提示、跳转地址、等待时间
    public function success($msg, $url, $time=3){
        ob_start();
        header('content-type:text/html;charset=utf-8');
        echo "<h1 style='font-size:100px;'>:)</h1>";
        echo "<h3>$msg</h3>";
        header("refresh:$time ;url=$url");
    }

    //失败的跳转方法
    //三个参数的意思成功提示、跳转地址、等待时间
    public function error($msg, $url, $time=3){
        ob_start();
        header('content-type:text/html;charset=utf-8');
        echo "<h1 style='font-size:100px;'>:(</h1>";
        echo "<h3>$msg</h3>";
        header("refresh:$time ;url=$url");
    }
}























?>