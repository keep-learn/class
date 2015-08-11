<?php
// 这个是memcache的类
class cache{
	// 全局变量,用于保存连接信息
	private  $memcache;

	// 初始化,建立连接
	public function __construct(){
		if(empty($this->memcache)){
			$this->memcache=new Memcache();
			$this->memcache->connect('127.0.0.1',11211);
		}else{
			// do nothing !
		}
	}

	// 设置数据
	public function setMem($key,$val,$time){
		$this->memcache->set($key,$val,0,$time);
	}

	// 获取数据
	public function getMem($key){
		return $this->memcache->get($key);
	}

	// 删除信息
	public function delMem($key){
		 if($this->memcache->delete($key)==1){
		 	return "Success to delete !";
		 }else{
		 	return "Soory , please try again !";
		 }
	}


	// 增加数据
	public function addMes($key,$value,$timeout=60){
		$this->memcache($key,$value,false,$timeout);
	}
	// 更新数据
	public function replaceMem($key,$val){
		$this->memcache->replace($key,$val);
	}

	// 关闭连接
	public function closeMem(){
		$this->memcache->close();
	}
}

?>