<?php

function curl($url,$cookies,$post,$referer=null,$header=true, $follow=false,$proxy=null)
{
	$user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:54.0) Gecko/20100101 Firefox/54.0';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, $header);
	if(strlen($cookies) > 5)
	{
		curl_setopt($ch, CURLOPT_COOKIE, $cookies);
	}
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

	if(isset($referer)){ curl_setopt($ch, CURLOPT_REFERER,$referer); }
	else{ curl_setopt($ch, CURLOPT_REFERER,$url); }
	if ($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
	}
	
	if($proxy != null)
	{
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
	}

	curl_setopt ($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
	$res = curl_exec( $ch);
	curl_close($ch); 
	return $res;
}

$url_proxy = 'http://falcon.proxyrotator.com:51337/?apiKey=VYZK892qeodPDML7fU6BFAjGtQuh4HWc&country=br';
$url = 'http://app.linceconsultadedados.com.br/';
while(true)
{
	$res_proxy = curl($url_proxy, null, null, null, false, false, null);
	$res_proxy = json_decode($res_proxy, true);
	$proxy     = $res_proxy['proxy'];	
	if(!stristr($proxy, ':3128'))
	{
		echo "Proxy invalido: {$proxy}\n";
		continue;			
	}

	$res 	   = curl($url, null, null, null, true, false, $proxy);
	if(strlen($res) > 10)
	{
		if(stristr($res, 'Proibido o Acesso')){
			echo "Proxy invalido: {$proxy} - acesso invalido\n";
			continue;
		}elseif(stristr($res, 'Cache Access Denied.')){
			echo "Proxy invalido: {$proxy} - acesso invalido\n";
			continue;			
		}
		echo "Proxy okkkkkk: {$proxy}\n";
		//echo $res;
		break;
	}
	else
	{
		echo "Proxy invalido: {$proxy}\n";
		continue;
	}
}


