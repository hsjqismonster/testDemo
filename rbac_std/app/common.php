<?php

use think\facade\Db;
use think\facade\Session;
use think\facade\Request;

function test(){
	echo "test";
}	

function build_serial_number($suffix = 'o') {
    return $suffix.date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

function to_format_time($org_time,$format="Y-m-d H:i:s"){	
	$format=str_replace("Y",substr($org_time,0,4),$format);
	$format=str_replace("m",substr($org_time,4,2),$format);
	$format=str_replace("d",substr($org_time,6,2),$format);
	$format=str_replace("H",substr($org_time,8,2),$format);
	$format=str_replace("i",substr($org_time,10,2),$format);
	$format=str_replace("s",substr($org_time,12,2),$format);
	return $format;
}	


function get_meta($key, $isLock=false){
	$condition=array();
	$condition["meta_key"]=$key;
	$meta =M("MetaInfo")->lock($isLock)->where($condition)->find();
	return $meta["meta_value"];
}

function set_meta($key, $value){
	$condition=array();
	$condition["meta_key"]=$key;
	$meta =M("MetaInfo")->where($condition)->find();
	if($meta){
		$item = array();
		$item["meta_value"]=$value;
		return M("MetaInfo")->where($condition)->save($item);
	}else{
		$item = array();
		$item["meta_key"]=$key;
		$item["meta_value"]=$value;
		return M("MetaInfo")->add($item);
	}
}


/**
 * $data´ýÇ©ÃûÊý¾Ý
 * Ç©ÃûÓÃÉÌ»§Ë½Ô¿£¬±ØÐëÊÇÃ»ÓÐ¾­¹ýpkcs8×ª»»µÄË½Ô¿
 * ×îºóµÄÇ©Ãû£¬ÐèÒªÓÃbase64±àÂë
 * return SignÇ©Ãû
 */
function rsa_sign($data) {
    //¶ÁÈ¡Ë½Ô¿ÎÄ¼þ
    $priKey = file_get_contents('key/rsa_private_key.pem');
    //×ª»»ÎªopensslÃÜÔ¿£¬±ØÐëÊÇÃ»ÓÐ¾­¹ýpkcs8×ª»»µÄË½Ô¿
    $res = openssl_get_privatekey($priKey);
    //µ÷ÓÃopensslÄÚÖÃÇ©Ãû·½·¨£¬Éú³ÉÇ©Ãû$sign
    openssl_sign($data, $sign, $res);
    //ÊÍ·Å×ÊÔ´
    openssl_free_key($res);
    //base64±àÂë
    $sign = base64_encode($sign);
    return $sign;
}

function rsa_sign_str($data,$priKey) {

    //×ª»»ÎªopensslÃÜÔ¿£¬±ØÐëÊÇÃ»ÓÐ¾­¹ýpkcs8×ª»»µÄË½Ô¿
    $res = openssl_get_privatekey($priKey);
    //µ÷ÓÃopensslÄÚÖÃÇ©Ãû·½·¨£¬Éú³ÉÇ©Ãû$sign
    openssl_sign($data, $sign, $res);
    //ÊÍ·Å×ÊÔ´
    openssl_free_key($res);
    //base64±àÂë
    $sign = base64_encode($sign);
    return $sign;
}

/**
 * $data´ýÇ©ÃûÊý¾Ý
 * $signÐèÒªÑéÇ©µÄÇ©Ãû
 * ÑéÇ©ÓÃÖ§¸¶±¦¹«Ô¿
 * return ÑéÇ©ÊÇ·ñÍ¨¹ý boolÖµ
 */
function rsa_verify($data, $sign)  { 
    //¶ÁÈ¡Ö§¸¶±¦¹«Ô¿ÎÄ¼þ
    $pubKey = file_get_contents('key/alipay_public_key.pem');
    //×ª»»Îªopenssl¸ñÊ½ÃÜÔ¿
    $res = openssl_get_publickey($pubKey);
    //µ÷ÓÃopensslÄÚÖÃ·½·¨ÑéÇ©£¬·µ»ØboolÖµ
    $result = (bool)openssl_verify($data, base64_decode($sign), $res);
    //ÊÍ·Å×ÊÔ´
    openssl_free_key($res);
    //·µ»Ø×ÊÔ´ÊÇ·ñ³É¹¦
    return $result;
}

function rsa_verify_str($data,$sign,$pubKey) { 
    //×ª»»Îªopenssl¸ñÊ½ÃÜÔ¿
    $res = openssl_get_publickey($pubKey);
    //µ÷ÓÃopensslÄÚÖÃ·½·¨ÑéÇ©£¬·µ»ØboolÖµ
    $result = (bool)openssl_verify($data, base64_decode($sign), $res);
    //ÊÍ·Å×ÊÔ´
    openssl_free_key($res);
    //·µ»Ø×ÊÔ´ÊÇ·ñ³É¹¦
    return $result;
}

function wx_signed_token($url,$http_method,$timestamp,$nonce,$body, $mch_id, $serial_no, $mch_private_key){
	$url_parts = parse_url($url);
	$canonical_url = ($url_parts['path'] . (!empty($url_parts['query']) ? "?${url_parts['query']}" : ""));
	$message = $http_method."\n".
		$canonical_url."\n".
		$timestamp."\n".
		$nonce."\n".
		$body."\n";
	
	openssl_sign($message, $raw_sign, $mch_private_key, 'sha256WithRSAEncryption');
	$sign = base64_encode($raw_sign);
	
	$schema = 'WECHATPAY2-SHA256-RSA2048';
	$token = sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
		$mch_id, $nonce, $timestamp, $serial_no, $sign);
	return $token;
}


/**
 * ·¢ËÍHTTPÇëÇó
 *
 * @param string $url ÇëÇóµØÖ·
 * @param string $method ÇëÇó·½Ê½ GET/POST
 * @param string $refererUrl ÇëÇóÀ´Ô´µØÖ·
 * @param array $data ·¢ËÍÊý¾Ý
 * @param string $contentType 
 * @param string $timeout
 * @param string $proxy
 * @return boolean
 *
 */
function send_request($url, $data, $refererUrl = '', $method = 'GET', $contentType = 'application/json', $header = "", $timeout = 300, $proxy = false) {
	$ch = null;
	if('POST' === strtoupper($method)) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER,0 );
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		if ($refererUrl) {
			curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
		}
		if($contentType) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
		}
		if($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		if(is_string($data)){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}
	} else if('GET' === strtoupper($method)) {

		if(is_string($data)) {
			$real_url = $url. (strpos($url, '?') === false ? '?' : ''). $data;
		} else {
			$real_url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($data);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $real_url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		if($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		if ($refererUrl) {
			curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
		}
	} else {
		$args = func_get_args();
		return false;
	}

	if($proxy) {
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$ret = curl_exec($ch);
	$info = curl_getinfo($ch);
    $errno = curl_errno($ch);
	$contents = array(
			'httpInfo' => array(
					'send' => $data,
					'url' => $url,
					'ret' => $ret,
					'http' => $info,
			)
	);

	curl_close($ch);
	return $ret;
}

function formated_tel($tel){
	return $tel;
}

function get_real_ip(){ 
	$ip=false; 
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){ 
		$ip=$_SERVER['HTTP_CLIENT_IP']; 
	}
	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
		$ips=explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']); 
		if($ip){ array_unshift($ips, $ip); $ip=FALSE; }
		for ($i=0; $i < count($ips); $i++){
			if(!preg_match('/^(10©¦172.16©¦192.168)./i', $ips[$i])){
				$ip=$ips[$i];
				break;
			}
		}
	}
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
}

 function random_str() {
  if (function_exists ( 'com_create_guid' )) {
    return com_create_guid ();
  } else {
    mt_srand ( ( double ) microtime () * 10000 ); 
    $charid = strtoupper (uniqid()); //根据当前时间（微秒计）生成唯一id.
    return $charid;
  }
}
