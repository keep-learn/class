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
