<?php
// 输出今天的时间戳
// 开始的时间戳
header('content-type:text/html;charset=utf-8');
$start=strtotime('2015-8-24');
$end=strtotime('2015-8-25');
$now=time();
$hour=ceil(($now-$start)/3600)-1;
$minute=ceil(($now-$start)/60)%60-1;
echo "Start : ".$start." End : ".$end;
echo "<br/>";
echo $hour."小时";
echo "<br/>";
echo $minute."分钟";
echo "<br/>";
echo "<hr/>";

echo showTodayTime();
echo day();
echo "<hr/>";
echo showWeekTime();
echo week();


// 输出今天的时间戳的函数
    function showTodayTime(){
      $start=strtotime('today');
      $end=$start+24*3600;
      return "今天的时间戳的范围: "."Start :".$start." End:".$end."<br/>";
    }
// 输出本周的时间戳的函数
    function showWeekTime(){
      // 今天是一周的第几天
      $dayNum=date('N');
      // 注意,一周是从星期日开始滴
      $start=strtotime('today')-24*3600*($dayNum-1);
      $end=$start+24*3600*7;
      return "本周的时间戳范围:"."Start :".$start." End:".$end."<br/>";   
    }    
    function day(){
      $dayBegin=strtotime('today');
      $dayEnd=strtotime('tomorrow');
      return "本天的时间戳范围:"."Start :".$dayBegin." End:".$dayEnd."<br/>"; 
    }
    function week(){
      $weekBegin=strtotime('this monday');
      $weekEnd=strtotime('next monday')-1;
      return "本周的时间戳范围:"."Start :".$weekBegin." End:".$weekEnd."<br/>"; 
    }

echo "<hr/>";
echo strtotime('next month');
echo "<br/>";
echo "<br/>";
echo strtotime('this week');
echo "<br/>";
echo strtotime('now');
echo "<hr/>";
echo strtotime("7am"); 
echo "<hr/>ssssssss<br/>";
echo strtotime('this month');
echo "<br/>";
echo time();
?>
