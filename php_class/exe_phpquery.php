<?php
header('content-type:text/html;charset=utf-8');
require_once('phpQuery.php');
// 抓取文章的标题和链接
function SeizeList(){
		phpQuery::newDocumentFile('http://www.nowamagic.net/php/'); 
		$artlist = pq("table:eq(1) a");
		$path="http://www.nowamagic.net/php/";
			foreach($artlist as $i){
				$link=pq($i)->attr('href');
				$href=$path."/".$link;
				$title=pq($i)->html();
				echo $href." : ".$title."<br/>";
			}	
}

function SeizePassage(){
		phpQuery::newDocumentFile('http://www.nowamagic.net/php/php_UseReflectionToGenerateSql.php'); 
		$artlist = pq(".mainContent")->html();
		echo "<pre>";
		echo $artlist;
		echo "</pre>";
 }

 /*
分析总结:

phpquery 是基于 juqery 而开发的,所以使用极其类似 jquery .
其中: pq() 相当于 juqery 中的 $() 方法.
循环:
	$article=pq('.article');
	foreach($article as $i){
		pq($i)->find('a')->html();
	}
抓取属性:
	$link = pq('.article a')->attr('href');	
 */