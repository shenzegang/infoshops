<?php
/**
 * 发送接口请求
 * @param req 请求参数
 * @param resp 请求参数
 * return 是否成功
 */
function trade($url, $req, &$resp){
	$respString = post($url, $req);
	return verifyResponse($respString, $resp);
}

/**
 * 应答解析
 * @param respString
 * @param resp
 * return 应答是否成功
 * 			'000' 表示成功
 */
function verifyResponse($respString, &$resp){
	if($respString != ""){
		$resp = json_decode($respString);
		$resp = (array)$resp;
		if($resp['returnStatus'] == '000'){
			return true;
		}
	}
	return false;
}

/**
 * curl_call()
 * @param url String 请求的完整路径
 * @param content Array 请求的内容（数组）
 *
 * return param:
 * 			false : error in happen
 * 			String : curl return data
 */
function post($url, $content = null){
	if (function_exists("curl_init")) {
		$curl = curl_init();

		if (is_array($content)) {
		    if(!empty($content)){
		        $data = json_encode($content);
		    }else{
		        $data = json_encode((Object)$content);
		    }
			
			$content_type = 'Content-Type: application/json';
		}else{
			$data = $content;
			$content_type = 'Content-Type: application/xml';
		}
		$header = array($content_type, 'Content-Length: ' . strlen($data), 'Authorization:Basic YWRtaW46YWRtaW4=');

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60); //seconds

		$ret_data = curl_exec($curl);

		if (curl_errno($curl)) {
			//printf("curl call error(%s): %s\n", curl_errno($curl), curl_error($curl));
			throw new Exception("服务器连接失败，" . curl_error($curl));
			curl_close($curl);
			return false;
		}
		else {
			curl_close($curl);
			return $ret_data;
		}
	} else {
		throw new Exception("[PHP] curl module is required");
	}
}
?>