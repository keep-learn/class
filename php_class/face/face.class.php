<?php
class Face{

    public $api_key         = 'f0974200f83728691673c99889430f40';        // set your API KEY or set the key static in the property
    public $api_secret      = 'AkXwOavansSpEjJkc4CDGLxVSsqBfoJh';        // set your API SECRET or set the secret static in the property

    public $face_id='';
    public $sex='';
    public $age='';
    public $race='';
    public $glasses='';


    public $face_width='';
    public $face_height='';

    public $img_center_x='';
    public $img_center_y='';

    public $img_height='';
    public $img_width='';
    public $session_id='';
// 获取最基本的信息
	public function showFaceInfo($img_url){
		 $url="http://apicn.faceplusplus.com/v2/detection/detect?api_secret=".$this->api_secret."&api_key=".$this->api_key."&url=".$img_url;
		 $res=file_get_contents($url);
		 $json_de=json_decode($res);
		 $this->face_id      =  $json_de->face[0]->face_id;
		 $this->sex          =  $json_de->face[0]->attribute->gender->value;
		 $this->age          =  $json_de->face[0]->attribute->age->value;
		 $this->race         =  $json_de->face[0]->attribute->race->value;
		 $this->img_height   =  $json_de->img_height;
		 $this->img_width    =  $json_de->img_width;
		 $this->session_id   =  $json_de->session_id;
		 $this->img_center_x =  $json_de->face[0]->position->center->x;
		 $this->img_center_y =  $json_de->face[0]->position->center->y;
		 $this->face_width   =  $json_de->face[0]->position->width;
		 $this->face_height  =  $json_de->face[0]->position->height;
		 // $this->glasses  =  $json_de->face[0]->attribute->glass->value;
		 return $json_de;

	}

// 获取头像的具体位置信息
	public function getPosition(){
		$url="http://api.faceplusplus.com/detection/landmark?api_secret=".$this->api_secret."&api_key=".$this->api_key."&face_id=".$this->face_id;
		$resource=file_get_contents($url);
		return $json_deco=json_decode($resource);
		// var_dump($json_deco);
	}
}


// echo "img width : ".$zbs->img_width; echo "<br/>";
// echo "img height : ".$zbs->img_height; echo "<br/>";
// echo "center x : ".$zbs->img_center_x; echo "<br/>";
// echo "center y : ".$zbs->img_center_y; echo "<br/>";
// echo "face widht : ".$zbs->face_width; echo "<br/>";
// echo "face height : ".$zbs->face_height; echo "<br/>";

// echo "<hr color='red'/>";
// echo "head width is : ".$img_width; echo "<br/>";
// echo "head height is : ".$img_height; echo "<br/>";
// echo "Xiang dui left true width: ".$img_top; echo "<br/>";
// echo "Xiang dui top  true height:".$img_left;echo "<br/>";
// echo "<hr color='red'/>";



// var_dump($res);


// echo "id is : ".$zbs->face_id;
// echo "<hr/>";
// echo "sex is : ".$zbs->sex;
// echo "<hr/>";
// echo "age is : ".$zbs->age;
// echo "<hr/>";
// echo "race is :".$zbs->race;
// echo "<hr/>";
// echo "glasses : ".$zbs->glasses;


// echo "<hr/>";
// $zbs->getPosition();


// var_dump($url);
// echo "<hr/>";
// echo "face id is : ".$url->face[0]->face_id;
// https://apicn.faceplusplus.com/v2/detection/detec)t?api_secret=AkXwOavansSpEjJkc4CDGLxVSsqBfoJh&api_key=f0974200f83728691673c99889430f40&url=http://www.lovezbs.com/face/images/1438586361_me.png;
	 

?>