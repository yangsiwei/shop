<?php  
//Sina App_Key  
define('SINA_APPKEY', '3271760578');  //</span></span>3271760578是你的App_Key  
   
function curlQuery($url) {  
    //设置附加HTTP头  
    $addHead = array(  
        "Content-type: application/json"  
    );  
  
    //初始化curl，当然，你也可以用fsockopen代替  
    $curl_obj = curl_init();  
   
    //设置网址  
    curl_setopt($curl_obj, CURLOPT_URL, $url);  
   
    //附加Head内容  
    curl_setopt($curl_obj, CURLOPT_HTTPHEADER, $addHead);  
   
    //是否输出返回头信息  
    curl_setopt($curl_obj, CURLOPT_HEADER, 0);  
   
    //将curl_exec的结果返回  
    curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, 1);  
   
    //设置超时时间  
    curl_setopt($curl_obj, CURLOPT_TIMEOUT, 15);  
   
    //执行  
    $result = curl_exec($curl_obj);  
    //关闭curl回话  
    curl_close($curl_obj);  
   
    return $result;  
}  
   
//简单处理下url，sina对于没有协议(http://)开头的和不规范的地址会返回错误  
function filterUrl($url = '') {  
    $url = trim(strtolower($url));  
    $url = trim(preg_replace('/^http:\/\//', '', $url));  
    if ($url == '')  
        return false;  
    else  
        return urlencode('http://' . $url);  
}  
   
//根据长网址获取短网址  
function sinaShortenUrl($long_url) {  
    //拼接请求地址，此地址你可以在官方的文档中查看到  
    $url = 'http://api.t.sina.com.cn/short_url/shorten.json?source=' . SINA_APPKEY .'&url_long=' . $long_url;  
   
    //获取请求结果  
    $result = curlQuery($url);  
   
    //下面这行注释用于调试，你可以把注释去掉看看从sina返回的信息是什么东西  
    //print_r($result);exit();  
   
    //解析json  
    $json = json_decode($result);  
    //异常情况返回false  
    if (isset($json->error) || !isset($json[0]->url_short) || $json[0]->url_short =='')  
        return false;  
    else  
        return $json[0]->url_short;  
}  
   
//根据短网址获取长网址，此函数重用了不少sinaShortenUrl中的代码，以方便你阅读对比，你可以自行合并两个函数  
function sinaExpandUrl($short_url) {  
    //拼接请求地址，此地址你可以在官方的文档中查看到  
    $url = 'http://api.t.sina.com.cn/short_url/expand.json?source=' . SINA_APPKEY .'&url_short=' . $short_url;  
    //获取请求结果  
    $result = curlQuery($url);  
   
    //下面这行注释用于调试，你可以把注释去掉看看从sina返回的信息是什么东西  
    //print_r($result);exit();  
    //解析json  
    $json = json_decode($result);  
   
    //异常情况返回false  
    if (isset($json->error) || !isset($json[0]->url_long) || $json[0]->url_long =='')  
        return false;  
    else  
        return $json[0]->url_long;  
}  
// //要缩短的网址  
// $url = 'http://d.xzxin.cn/123.php?id=1&ref=http://mini.eastday.com/a/161015054039920.html?2345';  
   
// $url = filterUrl($url);  
// $short = sinaShortenUrl($url);  
// $ulong = sinaExpandUrl($short);  
// echo "源网址：".$ulong."<br />短网址：".$short;  
?>  
