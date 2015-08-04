<?php
header('content-type:text/html;charset=utf-8');
class My_session{
	public $last_visited_time=array();
	public $live_time=10;
	public $errMes=array();
	public function __construct(){
		session_start();
	}
	// 建立一个session
	public function setSession($session_name,$session_value){
		$_SESSION[$session_name]=$session_value;
		$this->last_visited_time[$session_name]=time();
	}
	// 获取session 设置其失效时间
	public function getSessionUseTime($session_name){
		if(isset($_SESSION[$session_name])&&(time()-$this->last_visited_time[$session_name])<60){
			return $_SESSION[$session_name];
		}else{
			$this->errMes="Session 过期 !";
		}
	}
	// 获取session
	public function getSession($session_name){
		return $_SESSION[$session_name];
	}
	// 删除一个seesion
	public function delSession($session_name){
		unset($_SESSION[$session_name]);
	}
	// 删除所有的session
	public function delAllSession(){
		session_destroy();
		session_unset() ;
	}
}


$exe=new My_session();


$exe->setSession('xx','hello world');
$exe->setSession('xxx','hello world');
// $exe->setSession('username','ZhangBIngShuai');
// $exe->delAllSession();
echo $exe->getSession('xx');
echo $exe->getSession('xxx');
echo $exe->last_visited_time['xxx'];

echo "<hr/>";
echo $exe->getSessionUseTime('username');

echo "<hr/>";
var_dump($exe->errMes);
?>