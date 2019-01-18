<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Telefone;
use App\Lince;

use Illuminate\Http\Request;

use Validator;

class TelefonesController extends Controller
{
	public function index()
	{
		$ends = Telefone::all();
		//$emails = Email::paginate();
		return $ends;
	}
	
	public function pesquisaExterno($numero)
	{
		$lince = new Lince();
		$dados = $lince->consultaTel($numero);

		if($dados == 'y'){
			return false;
		}elseif($dados == false){
			$dados = array();
		}elseif(is_array($dados)){
			$dados = $dados;
		}elseif($dados == 'relog'){
			$lince->getCredencial();
			$dados = $lince->consultaTel($numero);
		}else{
			$dados = array();
		}

		if(count($dados) > 0)
		{
			$dados_novos = array();
			foreach($dados as $ndados)
			{
				$ndoc  = $ndados['cpf_cnpj'];
				$nnome = $ndados['nome'];
				
				/*
					validar numero doc. 11 e 14 digitos!
					validar nome?
					validar numero, separar ddd?
				*/
				
				if(strlen($ndoc) > 10)
				{
					array_push($dados_novos, array(
       		 	    	'doc' => $ndoc,
            			'nome' => $nnome,
           		 		'telefone' => $numero,
        			));
        			
				}
			}

			Telefone::insert($dados_novos);
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function pesquisaInterna($numero, $pagina)
	{		
		$max    = 50;

		if (!$pagina)
		{
			$pagina = 1;
		}
		elseif($pagina > 5)
		{
			$pagina = 5;
		}
		else
		{
			$pagina = $pagina;
		}
		
		$limite = 10;
		$offset = ($pagina -1) * $limite;
		$resultado = array();
	
		if(strlen($numero) > 5)
		{

			$nomesTotal = Telefone::where('telefone', $numero)
						->orWhere(function($query) use ($numero) {
    				    	$query->where('telefone', 'like', '%' . $numero . '%');
   						})
						->count();

			$nomes = Telefone::where('telefone', $numero)
						->orWhere(function($query) use ($numero) {
    				    	$query->where('telefone', 'like', '%' . $numero . '%');
   						})
						->offset($offset)->limit($limite)->get();

			$nomes = json_decode($nomes, true);

			if(count($nomes) == 0)
			{
				$nomes = false;
			}
			
	
		}
		else 
		{
			$nomes = false;
		}


		$lince = new Lince();
		$resultado = array();

		if(is_array($nomes))
		{
		foreach($nomes as $nom)
		{
			$ndoc = $nom['doc'];
			if(strlen($ndoc) == 14)
			{
				$doc0 = substr($ndoc, 0, 5);
				
				$doc3 = substr($ndoc, 9, 14);
				$doc  = $doc0 . '****' . $doc3;
				$doc = $lince->mask($doc, '##.###.###/####-##');
				
			}
			elseif(strlen($ndoc) == 11)
			{
				$doc0 = substr($ndoc, 0, 4);
				$doc3 = substr($ndoc, 7, 11);
				$doc  = $doc0 . '***' . $doc3;				
				$doc = $lince->mask($doc, '###.###.###-##');
			}
			
			$x = array(
				'id'     => $lince->encodex($ndoc),
				'doc'    => $doc,
				'nome'   => $nom['nome'],
				'idade'  => '-',
				'cidade' => '-',
				'uf'	 => '-'
			);
    		array_push($resultado, $x);
    	}
		
		return array('nomes' => $resultado, 'total' => $nomesTotal, 'pagina'=> $pagina);
		}
		else
		{
			return false;
		}
	
	}
	
	public function pesquisa(Request $request)
	{
		
		$numero   = $request->get('numero');
		$pagina = $request->get('pagina');

		if(strlen($pagina) == 0)
		{
			$pagina = 1;
		}
		elseif($pagina > 5)
		{
			$pagina = 5;
		}

		$nomes = $this->pesquisaInterna($numero, $pagina);
		if(is_array($nomes)){
			$total = $nomes['total'];			
			$nomes = $nomes['nomes'];

		}else{
			$nomes = array();
			$total = 0;
		}

		if($total > 50)
		{
			$total = 50;
		}

		$paginas = ceil($total / 10);		

		if(count($nomes) > 1)
		{
			$nomes = array(
				'resultado'  => $nomes,
				'paginas'  => $paginas,
				'pagina' => $pagina
			);
			return $nomes;
		}
		else
		{
			$novos = $this->pesquisaExterno($numero);
			$nomes = $this->pesquisaInterna($numero, $pagina);	

			if(is_array($nomes)){
				$total = $nomes['total'];
			}else{
				$total = 0;
			}

			if($total > 50)
			{
				$total = 50;
			}

			$paginas = ceil($total / 10);		
			if(is_array($nomes)){		
				$nomes = $nomes['nomes'];
			}else{
				$nomes = array();
			}

			if(count($nomes) > 1)
			{
				$nomes = array(
					'resultado'  => $nomes,
					'paginas'  => $total,
					'pagina' => $pagina
				);
				return $nomes;
			}
			else
			{
				return array('msg'=>'nada encontrado.');
			}
		}

	}
}







