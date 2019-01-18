<?php

namespace App;

use App\Http\Controllers\Controller;
use App\Config;

class Lince extends Controller
{
	private $credencial, $url, $cookie, $proxy, $cliente, $usuario, $token;

	public function __construct()
	{
		$config = $this->getConfig();
		$this->proxy  = $config['proxy'];
		$this->token  = base64_decode($config['token']);
		$this->cookie = base64_decode($config['cookie']);
		$this->url    = $config['url'];
		$this->cliente = $config['cliente'];
		$this->usuario = $config['usuario'];
		$this->credencial = array(
			'pw' => $config['pw'],
			'us' => $config['us']
		);				
	}

	public function mask($val, $mask)
	{
 		$maskared = '';
 		$k = 0;
 		for($i = 0; $i<=strlen($mask)-1; $i++)
 		{
		 if($mask[$i] == '#')
		 {	
		 if(isset($val[$k]))
 		$maskared .= $val[$k++];
		 }
 	else
 	{
 	if(isset($mask[$i]))
 	$maskared .= $mask[$i];
	 }
 	}
	 return $maskared;	
	}



public function decodex($doc)
{
    $doc = base64_decode($doc);
    $doc = explode(':', $doc);
    $doc = $doc[0];
    $doc = str_replace('x', '0', $doc);
    $doc = str_replace('u', '1', $doc);
    $doc = str_replace('r', '2', $doc);
    $doc = str_replace('b', '3', $doc);
    $doc = str_replace('o', '4', $doc);
    $doc = str_replace('s', '5', $doc);
    $doc = str_replace('t', '6', $doc);
    $doc = str_replace('p', '7', $doc);
    $doc = str_replace('w', '8', $doc);
    $doc = str_replace('q', '9', $doc);
    return $doc;
}

public function encodex($doc)
{
    $doc = str_replace('0', 'x', $doc);
    $doc = str_replace('1', 'u', $doc);
    $doc = str_replace('2', 'r', $doc);
    $doc = str_replace('3', 'b', $doc);
    $doc = str_replace('4', 'o', $doc);
    $doc = str_replace('5', 's', $doc);
    $doc = str_replace('6', 't', $doc);
    $doc = str_replace('7', 'p', $doc);
    $doc = str_replace('8', 'w', $doc);
    $doc = str_replace('9', 'q', $doc);
    $doc = $doc . ':' . md5(time());
    $doc = base64_encode($doc);
    return $doc;
}

public function limpadocexten($doc)
{
	$ver = substr($doc, 0, 3);
	if($ver == '000')
	{
		$doc = substr($doc, 3);
	}
	return $doc;
}

    public function en($string, $key='987654')
    {
        $result = "";
        for($i=0; $i<strlen($string); $i++)
        {
            $char    = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char    = chr(ord($char)+ord($keychar));
            $result .=$char;
        }

        $salt_string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxys0123456789~!@#$^&*()_+`-={}|:<>?[]\;',./";
        $length = rand(19, 101);
        $salt   = "";
        for($i=0; $i<=$length; $i++)
        {
            $salt .= substr($salt_string, rand(0, strlen($salt_string)), 1);
        }
        $salt_length = strlen($salt);
        $end_length  = strlen(strval($salt_length));

        return base64_encode($result.$salt.$salt_length.$end_length);
    }

    public function de($string, $key='987654')
    {
        $result      = "";
        $string      = base64_decode($string);
        $end_length  = intval(substr($string, -1, 1));
        $string      = substr($string, 0, -1);
        $salt_length = intval(substr($string, $end_length*-1, $end_length));
        $string      = substr($string, 0, $end_length*-1+$salt_length*-1);
        for($i=0; $i<strlen($string); $i++)
        {
            $char    = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char    = chr(ord($char)-ord($keychar));
            $result .=$char;
        }
        return $result;
    }
	
	
	public function getConfig()
	{
		$config = Config::find(1);
		return $config;
	}

	public function curl($url,$cookies,$post,$header=true,$referer=null,$rer=null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, $header);
		
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

		if(strlen($cookies) > 5)
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: " . $cookies));
		}


		if(isset($referer)){ curl_setopt($ch, CURLOPT_REFERER,$referer); }
		else{ curl_setopt($ch, CURLOPT_REFERER,$url); }

		if($rer != null)
		{
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $rer);
		}

		if ($post)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
		}

		if(stristr($this->proxy, ':'))
		{
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
		}
		
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 20);
		
		$res = curl_exec( $ch);

		curl_close($ch); 
	    return ($res);
	}
    
	public function corta($str, $left, $right)
	{
		$str 	  = substr ( stristr ( $str, $left ), strlen ( $left ) );
    	@$leftLen = strlen ( stristr ( $str, $right ) );
    	$leftLen  = $leftLen ? - ($leftLen) : strlen ( $str );  
    	$str 	  = substr ( $str, 0, $leftLen );
    	return $str;
	}

	public function getCookies($get)
	{
		preg_match_all('/Set-Cookie: (.*);/U',$get,$temp);
    	$cookie  = $temp[1];
    	$cookies = implode('; ',$cookie);
    	return $cookies;
	}

	public function logar()
	{
		$url  = $this->url . 'user/checkSession';
		$ref  = $this->url;
		$post = $this->credencial;

		$res    = $this->curl($url, null, http_build_query($post), true, $ref);
		$cookie = $this->getCookies($res);
		$url    = $this->url . 'oauth/access_token';
		$post   = array(
			'client_id' 	=> 'appid1',
			'client_secret' => 'secret',
			'grant_type'    => 'password',
			'password' 		=> $post['pw'],
			'username'      => $post['us']
		);

		$res     = $this->curl($url, $cookie, http_build_query($post) , true, $ref) . '=::';
		$cooki   = $this->getCookies($res);
		$data    = array();
		$data['access_token'] = $this->corta($res, 'access_token":"', '"');
		$token   = urlencode('{' . $this->corta($res, '{', '=::'));
		$cookie  = $cooki . '; token=' . $token;

		if(stristr($res, 'access_token'))
		{
			$url = $this->url . 'user/authenticated';
			$rer =  array(
				'Accept: application/json, text/plain, */*'.
				'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
				"Authorization: Bearer ".$data['access_token']
			);

			$res    = $this->curl($url, $cookie, null, true, $ref, $rer) . '=::';
			$user   = urlencode('{'. $this->corta($res, '{', '=::'));
			$cookie = $cooki . ';' . ' token=' . $token . '; user=' . $user;
			$cc     = explode(';', $cookie);

			$url  = $this->url . 'consulta/consultaCPFCNPJ';
			$ref  = $this->url;
			$post = 'cliente='.$this->cliente.'&cpf_cnpj=02744792497&usuario=' . $this->usuario;
			$rer  =  array(
				'Accept: application/json, text/plain, */*'.
				'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
				'Content-Type: application/x-www-form-urlencoded',
				"Authorization: Bearer ".$data['access_token']
			);

			$res  = $this->curl($url, $cookie, $post, true, $ref, $rer);
		
			if(stristr($res, 'cpf_cnpj":'))
			{
				$cooki  = $this->getCookies($res);
				$cookie = $cooki . ';' . ' token=' . $token . '; user=' . $user;

				$dados = array(
					'cookie' => base64_encode($cooki),
					'token'  => base64_encode($data['access_token'])
				);
				
				Config::where('id', 1)->update(['cookie' => base64_encode($cooki), 'token' => base64_encode($data['access_token'])]);
				
				return $dados;
		
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	public function consultaEmail($email)
	{	
		$dados = $this->getConfig();

		$rer =  array(
			'Accept: application/json, text/plain, */*'.
			'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
			'Content-Type: application/x-www-form-urlencoded',
			"Authorization: Bearer ".$dados['token']
		);

		$url  = $dados['url'] . 'consulta/consultaEmail';
		$ref  = $dados['url'];
		$post = 'cliente='.$dados['cliente'].'&email=' .$email . '&usuario=' . $dados['usuario'];

		$res  = $this->curl($url, $dados['cookie'], $post, false, $ref, $rer);
		$res  = json_decode($res, true);
		return $res;
	}

	/*
	$nome = $_REQUEST['nome'];
	$cidade = $_REQUEST['cidade'];
	$uf = $_REQUEST['uf'];
	$cep = $_REQUEST['cep'];
	$dados = json_decode(ler(), true);
	$res = consultaNome($nome, $cidade, $uf, $cep, $dados);
	
	*/
	public function consultaNome($nome, $cidade, $uf, $cep)
	{
		$dados = $this->getConfig();

		$rer =  array(
			'Accept: application/json, text/plain, */*'.
			'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
			'Content-Type: application/x-www-form-urlencoded',
			"Authorization: Bearer ".base64_decode($dados['token'])
		);

		$url  = $dados['url'] . 'consulta/consultaNome';
		$ref  = $dados['url'];
	
		$post = '';

		if(strlen($nome) > 3){ 
			$post .= 'nome=' . $nome . '&';
		}

		if(strlen($cidade) > 3){
			$post .= 'cidade=' . $cidade . '&';
		}
	
		if(strlen($uf) == 2){
			$post .= 'uf=' . $uf . '&';
		}
	
		if(strlen($cep) > 4){
			$post .= 'cep=' . $cep . '&';
		}
	
		$post .= 'cliente='. $dados['cliente']. '&usuario=' . $dados['usuario'];
		$res  = $this->curl($url, base64_decode($dados['cookie']), $post, false, $ref, $rer);
		$res  = json_decode($res, true);
		return $res;
	}
	/*
	$dados = json_decode(ler(), true);
	$rua = $_REQUEST['rua'];
	$cidade = $_REQUEST['cidade'];
	$uf = $_REQUEST['uf'];
	$cep = $_REQUEST['cep'];
	$res = consultaEnd($rua, $cidade, $uf, $cep, $dados);
	
	*/
	public function consultaEnd($rua, $cidade, $uf, $cep)
	{
		$dados = $this->getConfig();
	
		$rer =  array(
			'Accept: application/json, text/plain, */*'.
			'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
			'Content-Type: application/x-www-form-urlencoded',
			"Authorization: Bearer ".base64_decode($dados['token'])
		);

		$url  = $dados['url'] . 'consulta/consultaEndereco';
		$ref  = $dados['url'];
	
		$post = '';

		if(strlen($rua) > 3)
		{ 
			$post .= 'logradouro=' . $rua . '&';
		}

		if(strlen($cidade) > 3)
		{
			$post .= 'cidade=' . $cidade . '&';
		}
	
		if(strlen($uf) == 2)
		{
			$post .= 'uf=' . $uf . '&';
		}
	
		if(strlen($cep) > 4)
		{
			$post .= 'cep=' . $cep . '&';
		}
		else
		{ 
			$post .= 'cep=&';
		}
	
		$post .= 'cliente= '. $dados['cliente'] .'&usuario=' . $dados['usuario'];
		$res  = $this->curl($url, base64_decode($dados['cookie']), $post, false, $ref, $rer);
		$res  = json_decode($res, true);
		return $res;
	}
	/*

		$dados = json_decode(ler(), true);
		$res   = consultaDoc($doc, $dados);	
	*/
	public function consultaDoc($doc)
	{
		$dados = $this->getConfig();
	
		$rer =  array(
			'Accept: application/json, text/plain, */*'.
			'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
			'Content-Type: application/x-www-form-urlencoded',
			"Authorization: Bearer ".base64_decode($dados['token'])
		);

		$url  = $dados['url'] . 'consulta/cpf_cnpj';
		$ref  = $dados['url'];
		$post = 'cpf_cnpj=' .$doc;

		$res  = $this->curl($url, base64_decode($dados['cookie']), $post, false, $ref, $rer);
		$res  = json_decode($res, true);
		return $res;
	}
	
	/*
	$tel = $_REQUEST['tel'];

	if(strlen($tel) > 6)
	{
		$dados = json_decode(ler(), true);
		$res   = consultaTel($tel, $dados);
	}
	echo "<pre>";
	print_r($res);
	die;	
	*/
	public function consultaTel($tel)
	{
		$dados = $this->getConfig();
		$dados = json_decode($dados, true);

		$rer =  array(
			'Accept: application/json, text/plain, */*'.
			'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.5,en;q=0.3',
			'Content-Type: application/x-www-form-urlencoded',
			"Authorization: Bearer ".base64_decode($dados['token'])
		);

		$url  = $dados['url'] . 'consulta/consultaTelefone';
		$ref  = $dados['url'];
		$post = 'cliente='.$dados['cliente'].'&numero='.$tel.'&usuario=' . $dados['usuario'];
		$res  = $this->curl($url, base64_decode($dados['cookie']), $post, true, $ref, $rer);

		if(stristr($res, '500 Internal Server Error')){
			return 'relog';
		}elseif(stristr($res, 'os on-line ultrapassa a permis')){
			return 'y';
		}elseif(strlen($res) < 20){
			return false;
		}
		if(stristr($res, '","')){
			$resm = $this->corta($res, 'HTTP/', 'Connection: close');
			$res = str_replace($resm, '', $res);
			$res = str_replace('HTTP/Connection: close', '', $res);
			$res = str_replace("\r\n", '', $res);

			$res  = json_decode($res, true);
			return $res;
		}
	}
	
	public function getCredencial()
	{
		$dados = $this->logar();
		
		return $dados;		
	}
}

