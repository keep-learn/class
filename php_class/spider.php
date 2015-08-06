<?php
 
/**
 * 一个用于抓取图片的类
 *
 * @package default
 * @author  WuJunwei
 */
class download_image 
{
     
    public $save_path;                  //抓取图片的保存地址
 
    //抓取图片的大小限制(单位:字节) 只抓比size比这个限制大的图片
    public $img_size=0; 
 
    //定义一个静态数组,用于记录曾经抓取过的的超链接地址,避免重复抓取       
    public static $a_url_arr=array();   
     
    /**
     * @param String $save_path    抓取图片的保存地址
     * @param Int    $img_size     抓取图片的保存地址
     */
    public function __construct($save_path,$img_size)
    {
        $this->save_path=$save_path;
        $this->img_size=$img_size;
    }
     
     
    /**
     * 递归下载抓取首页及其子页面图片的方法  ( recursive 递归)
     *
     * @param   String  $capture_url  用于抓取图片的网址
     * 
     */
    public function recursive_download_images($capture_url)
    {
        if (!in_array($capture_url,self::$a_url_arr))   //没抓取过
        {                         
            self::$a_url_arr[]=$capture_url;   //计入静态数组
        } else   //抓取过,直接退出函数
        {
            return;
        }        
         
        $this->download_current_page_images($capture_url);  //下载当前页面的所有图片
         
        //用@屏蔽掉因为抓取地址无法读取导致的warning错误
        $content=@file_get_contents($capture_url); 
         
        //匹配a标签href属性中?之前部分的正则
        $a_pattern = "|<a[^>]+href=['\" ]?([^ '\"?]+)['\" >]|U";   
        preg_match_all($a_pattern, $content, $a_out, PREG_SET_ORDER);
         
        $tmp_arr=array();  //定义一个数组,用于存放当前循环下抓取图片的超链接地址
        foreach ($a_out as $k => $v) 
        {
            /**
             * 去除超链接中的 空'','#','/'和重复值  
             * 1: 超链接地址的值 不能等于当前抓取页面的url, 否则会陷入死循环
             * 2: 超链接为''或'#','/'也是本页面,这样也会陷入死循环,  
             * 3: 有时一个超连接地址在一个网页中会重复出现多次,如果不去除,会对一个子页面进行重复下载)
             */
            if ( $v[1] && !in_array($v[1],self::$a_url_arr) &&!in_array($v[1],array('#','/',$capture_url) ) ) 
            {
                $tmp_arr[]=$v[1];
            }
        }
   
        foreach ($tmp_arr as $k => $v) 
        {            
            //超链接路径地址
            if ( strpos($v, 'http://')!==false ) //如果url包含http://,可以直接访问
            {
                $a_url = $v;
            }else   //否则证明是相对地址, 需要重新拼凑超链接的访问地址
            {
                $domain_url = substr($capture_url, 0,strpos($capture_url, '/',8)+1);
                $a_url=$domain_url.$v;
            }
 
            $this->recursive_download_images($a_url);
 
        }
         
    }  
     
       
    /**
     * 下载当前网页下的所有图片 
     *
     * @param   String  $capture_url  用于抓取图片的网页地址
     * @return  Array   当前网页上所有图片img标签url地址的一个数组
     */
    public function download_current_page_images($capture_url)
    {
        $content=@file_get_contents($capture_url);   //屏蔽warning错误
 
        //匹配img标签src属性中?之前部分的正则
        $img_pattern = "|<img[^>]+src=['\" ]?([^ '\"?]+)['\" >]|U";   
        preg_match_all($img_pattern, $content, $img_out, PREG_SET_ORDER);
 
        $photo_num = count($img_out);
        //匹配到的图片数量
        echo '<h1>'.$capture_url . "共找到 " . $photo_num . " 张图片</h1>";
        foreach ($img_out as $k => $v) 
        {
            $this->save_one_img($capture_url,$v[1]);
        }
    }
 
 
    /**
     * 保存单个图片的方法 
     *
     * @param String $capture_url   用于抓取图片的网页地址
     * @param String $img_url       需要保存的图片的url
     * 
     */
    public function save_one_img($capture_url,$img_url)
    {        
        //图片路径地址
        if ( strpos($img_url, 'http://')!==false ) 
        {
            // $img_url = $img_url;
        }else  
        {
            $domain_url = substr($capture_url, 0,strpos($capture_url, '/',8)+1);
            $img_url=$domain_url.$img_url;
        }           
        $pathinfo = pathinfo($img_url);    //获取图片路径信息        
        $pic_name=$pathinfo['basename'];   //获取图片的名字
        if (file_exists($this->save_path.$pic_name))  //如果图片存在,证明已经被抓取过,退出函数
        {
            echo $img_url . '<span style="color:red;margin-left:80px">该图片已经抓取过!</span><br/>'; 
            return;
        }                
        //将图片内容读入一个字符串
        $img_data = @file_get_contents($img_url);   //屏蔽掉因为图片地址无法读取导致的warning错误
        if ( strlen($img_data) > $this->img_size )   //下载size比限制大的图片
        {
            $img_size = file_put_contents($this->save_path . $pic_name, $img_data);
            if ($img_size)
            {
                echo $img_url . '<span style="color:green;margin-left:80px">图片保存成功!</span><br/>';
            } else
            {
                echo $img_url . '<span style="color:red;margin-left:80px">图片保存失败!</span><br/>';
            }
        } else
        {
            echo $img_url . '<span style="color:red;margin-left:80px">图片读取失败!</span><br/>';
        } 
    } 
} // END
header("content-type:text/html;charset=utf-8");
set_time_limit(1000);     //设置脚本的最大执行时间  根据情况设置 
$download_img=new download_image('./img/',30*1024);   //实例化下载图片对象
$download_img->recursive_download_images('http://52view.com/');      //递归抓取图片方法
//$download_img->download_current_page_images($_POST['capture_url']);     //只抓取当前页面图片方法
 
?>