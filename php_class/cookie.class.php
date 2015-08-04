<?php
class My_cookie{
	private $errMes=array();
	public function __construct(){

	}
	// 设置cookie
	public function setCookie($cookie_name,$cookie_value,$cookie_time){
		$errMes=setcookie($cookie_name,$cookie_value,time()+$cookie_time);
		if($errMes){
			$errMes="Cookie set Success !";
		}else{
			$errMes="Error : cookie set error !";
		}
		$this->errMes=$errMes;
	}
	// 获取cookie
	public function getCookie($cookie_name){
		return $_COOKIE[$cookie_name];
	}
	// 删除cookie
	public function delCookie($cookie_name){
		$errMes=setcookie($cookie_name,'',time()-1);
		if($errMes){
			$errMes="Cookie delete Success !";
		}else{
			$errMes="Error : delete  error !";
		}
		$this->errMes=$errMes;
	}
	// 获取errMes信息
	public function getMes(){
		return $this->errMes;
	}
}
error_reporting(0);

$exe=new My_cookie();
$exe->setCookie('name','zhangbingshuai',100);
echo $exe->getCookie('name');
echo "<hr/>";
// $exe->delCookie('name');
// var_dump($exe->getMes());

?>