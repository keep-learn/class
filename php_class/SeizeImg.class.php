<?php
// Notice:首先要require() pdo.class.php
class SeizeImg{
// 下载列表图片 , tb_passgelist
// 下载某一期下所有的图片连接,并放到数据库
// $url_name 是这个期刊的url
	public function SeizeOneIndexImg_ToMysql($url_name){ 
		// $file_name=date('Y-m-d--H-i-s').'.txt';
		// 提取url中的文件名
		$pattern="/\/(\w+\.html)/i";
		preg_match($pattern,$url_name,$match);
		$file_name=$match[1];

		if(!is_file($file_name)){
			$str_charset=file_get_contents($url_name);
			$str=iconv('gbk','utf-8',$str_charset);
			file_put_contents('log/'.$file_name, $str);
		}else{
			$str=file_get_contents($file_name);
		}
		$pattern='/<table width="960" border="0" cellspacing="10" cellpadding="0" class="bg_white">([\S\s]*)<\/table>\s*<table width="960" border="0" align="center" cellpadding="0" cellspacing="0" class="bg_white">/';
		// 经过第一次匹配,得到所有的列表的html
		preg_match($pattern,$str,$match);
		$str2=$match[1];
		$pattern2='/<table width="960" border="0" cellspacing="10" cellpadding="0" class="bg_white">/';
		// 然后将其分割为,一个一个的小的table
		$match2=preg_split($pattern2,$str2);
		// 对每一个小的table进行提取内容
		foreach($match2 as $key=>$arr){
			echo "---------------------".$key."-------------------------<br/>"; 
			$arr1=$arr;
			$pattern3='/"(http:[\w\.\/]*)"/i';
			preg_match($pattern3,$arr1,$match3);
			$pattern4='/src="(http:[\w\.\/]*)"/';
			preg_match($pattern4,$arr1,$match4);
			$pattern5='/title="([\S]*)"/';
			preg_match($pattern5,$arr1,$match5);
			// $pattern6="/\d+/";
			// preg_match($pattern6,$match5[1],$match6);	

			if(!empty($match3[1])&&!empty($match4[1])&&!empty($match5[1])){ 
				echo "列表的url是: ".$match3[1]."<br/>";
				echo "列表的src是: ".$match4[1]."<br/>";
				echo "列表的内容是:".$match5[1]."<br/>";
				// echo "期刊号是 :".$match6[0]."<br/>";
				echo "<br/><br/>";
				$sql="insert into tb_passagelist (PeriodicalId,title,url,imgsrc) values ('{$match6[0]}','{$match5[1]}','{$match3[1]}','{$match4[1]}')";
				echo "<br/>++++++++++++++++++++<br/>";
				$pdo=new PdoMySQL();
				echo $pdo->add($sql)."<br/>";			
			}
		  }
	}
// 将某个确切的期刊url传入函数,将下载其下所有的img
// $url_value指定的期刊url
// $dayid指定的期刊号的id
	public function SeizeOneUrlImg_ToMysql($url_value,$dayid){
		// foreach($url_name as $key=>$url_value){
			// $dayid=time();
	   // echo "<br/>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx".$dayid."<br/>";
			$string=file_get_contents($url_value);

			// 从url中提取数字.html作为缓存的文件名
			$pattern='/\/(\d*\.html)/i';
			preg_match($pattern,$url_value,$match);
			$file_name=$match[1];

			if(empty($file_name)){
				// continue;
			}

			file_put_contents('passage/'.$file_name, $string);

			$pattern='/<textarea name="gallery-data" style="display:none;">([\S\s]*)<\/textarea>[\s]*<\/div>/';
			preg_match($pattern,$string,$match);
			$str_new=$match[1];

			if(empty($str_new)){
				// continue;
			}
			
			$str2_new=iconv('gbk','utf-8',$str_new);
			// file_put_contents('me.txt',$str_new);
			// echo "<hr/><br/>\n";

			// $str2=substr(trim($str_new),1,-1);
			$str3=json_decode($str2_new);

			if(empty($str3)){
				// continue;
			}

			foreach($str3->list as $v){
				// echo "大图 img : ".$v->img."<br/>";
				// echo "小图 img : ".$v->timg."<br/>";
				// echo "描述 : ".$v->note."<br/>";
				// echo "<hr color='red'/>";
			$pdo=new PdoMySQL();
			$sql="insert into tb_passagedetail (dayid,imgBigUrl,imgSmallUrl,details) values ('{$dayid}','{$v->img}','{$v->timg}','{$v->note}')";
			echo "<br/>+++++++++++++++++".$pdo->add($sql)."+++++++++++++++++++++++++<br/>";
			}			

    }
// 抓取,所有的图片,和题目概要信息,并写入数据库
// 首先使用SeizeOneIndexImg_ToMysql()将期刊号插入到mysql中

    public function SeizeAllImg_ToMysql($sql="select id,url from tb_passagelist"){ 

// 因为抓取时间可能较长,避免浏览器30s超时,设置可以较长的执行时间
	    set_time_limit(10000);
	    $pdo=new PdoMySQL();
		$sql=$sql;
		$res=$pdo->getAll($sql);

		foreach($res as $val){
			$url_value=$val['url'];
		    $dayid=$val['id'];
			// echo $url_value."<br/>";
			$this->SeizeOneUrlImg_ToMysql($url_value,$dayid);
		}
	}

// 显示期刊列表
    public function showList($sql="select * from tb_passagelist"){
		$pdo=new PdoMySQL();
		$sql=$sql;
		$res=$pdo->getAll($sql);

		foreach($res as $v){ 
			// var_dump($res);
			$imgurl=$v['imgsrc'];
			// echo $v['title'];
			echo "<img src='".$imgurl."'/>";
			// echo "<hr/>";
		}
	}
// 显示所有图片的列表
	public function showDetails($sql="select * from tb_passagedetail"){
		$pdo=new PdoMySQL();
		$sql=$sql;
		$res=$pdo->getAll($sql);

		foreach($res as $v){ 
			// var_dump($res);
			$imgurl=$v['imgSmallUrl'];
			
			$imgurl."<br/>";
			// echo $v['details'];
			echo "<img style='width:150px;height:100px;' src='".$imgurl."'/>";
			// echo "<hr/>";
		}
	}

	public function test(){
		echo "hello world !";
	}


}

?>