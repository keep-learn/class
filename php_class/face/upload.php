<meta http-equiv="content" content-type="text/html" charset="utf-8"/>
<?php
    //包含一个文件上传类中的上传类
	require_once('fileUpload.class.php');
	require_once('face.class.php');
    $up = new fileupload;
    //设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
    $up -> set("path", "./images/");
    $up -> set("maxsize", 2000000);
    $up -> set("allowtype", array("gif", "png", "jpg","jpeg"));
    $up -> set("israndname", false);
  
    //使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
    if($up -> upload("pic")) {
// 获取上传文件的路径
         $img_url='http://www.lovezbs.com/face/images/'.$up->getFileName();

// 实例化face类
		$zbs=new Face();
		$res=$zbs->showFaceInfo($img_url);

		$img_true_width=$zbs->img_width;
		$img_ture_height=$zbs->img_height;

		$img_width=$zbs->face_width * $zbs->img_width/100;
		$img_height=$zbs->face_height * $zbs->img_height/100;
		$img_top=($zbs->img_center_y - $zbs->face_height/2)*$zbs->img_height/100;
		$img_left=($zbs->img_center_x - $zbs->face_width/2)*$zbs->img_width/100;

    } else {
        echo '<pre>';
        //获取上传失败以后的错误提示
        var_dump($up->getErrorMsg());
        echo '</pre>';
    }



?>

<style type="text/css">
body{
	padding: 0px;
	margin:0px;
}
.pic{
	width:<?php echo $img_true_width;?>;
	height:<?php echo $img_ture_height;?>;
	/*background: gray;*/
	position: relative;
}
.box{
	width:<?php echo $img_width;?>;
	height:<?php echo $img_height;?>;
	position: absolute;
	left:<?php echo $img_left;?>;
	top:<?php echo $img_top;?>;
	border:1.5px solid red;
}

</style>

<div class="pic">
	<img  src="<?php echo $img_url;?>"/>
	<div class="box">
		
	</div>
</div>
<br/>
<hr color='red'/>
<br/>


<?php
echo "性别是 : ".$zbs->sex."<br/>";
echo "年龄是 : ".$zbs->age."<br/>";
echo "人种是 : ".$zbs->race."<br/>";
// echo "眼镜否 : ".$zbs->glasses;

?>