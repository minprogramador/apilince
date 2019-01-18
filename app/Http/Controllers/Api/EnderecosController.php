<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Endereco;
use App\Lince;
use Illuminate\Http\Request;

use Validator;

class EnderecosController extends Controller
{
	public function index()
	{
		$ends = Endereco::all();
		//$emails = Email::paginate();
		return $ends;
	}
	
// 	public function view($id)
// 	{
// 		//$emails = Email::findOrFail($id);
// 		$nomes = Nome::find($id);
// 		return $nomes;
// 	}
// 	
// 	public function store(Request $request)
// 	{
// 		$nome = Nome::create($request->all());
// 		return $mome;
// 	}
// 	
// 	public function update(Request $request, $id)
// 	{
// 		$nome = Nome::findOrFail($id);
// 		$nome->update($request->all());
// 		return $nome;
// 	}
// 	
// 	public function destroy($id)
// 	{
// 		$nome = Nome::findOrFail($id);
// 		$nome->delete();
// 		return $nome;
// 	}
// 	


	public function pesquisaExterno($logradouro, $numero, $complemento, $cidade, $uf, $cep)
	{
		$lince = new Lince();
		$dados = $lince->consultaEnd($logradouro, $cidade, $uf, $cep);

		if(!is_array($dados))
		{
			$lince->getCredencial();
			$dados = $lince->consultaEnd($logradouro, $cidade, $uf, $cep);
		}
		
		if(count($dados) > 1)
		{
			$dados_novos = array();
			foreach($dados as $ndados)
			{
				$ndoc  = $lince->limpadocexten($ndados['cpf_cnpj']);
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
       		 	    	'logradouro' => $logradouro,
       		 	    	'numero' => $numero,
       		 	    	'complemento' => $complemento,
           		 		'cep' => $cep,       		 	    	
 						'cidade' => $cidade,
           		 		'uf' => $uf,
        			));
        			
				}
			}

			Endereco::insert($dados_novos);
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function pesquisaInterna($logradouro, $numero, $complemento, $cidade, $uf, $cep, $pagina)
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
		if(strlen($logradouro) > 3 && strlen($numero) > 3 && strlen($complemento) > 1 && strlen($cep) > 4 && strlen($cidade) > 3 && strlen($uf) > 1)
		{
			$nomesTotal = Endereco::where('logradouro', $logradouro)
						->where('numero', $numero)
						->where('complemento', $complemento)
						->where('cep', $cep)
						->where('cidade', $cidade)
						->wheew('uf', $uf)
						->orWhere(function($query) use ($logradouro, $numero, $complemento, $cidade, $uf, $cep)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('numero', 'like',  '%' . $numero . '%')
        			    		->where('complemento', 'like',  '%' . $complemento . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like', '%' . $uf . '%')
        			    		->where('cep', 'like', '%' . $cep . '%');
   							})
						->count();
								
			$nomes = Endereco::where('logradouro', $logradouro)
						->where('numero', $numero)
						->where('complemento', $complemento)
						->where('cep', $cep)
						->where('cidade', $cidade)
						->wheew('uf', $uf)
						->orWhere(function($query) use ($logradouro, $numero, $complemento, $cidade, $uf, $cep)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('numero', 'like',  '%' . $numero . '%')
        			    		->where('complemento', 'like',  '%' . $complemento . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like', '%' . $uf . '%')
        			    		->where('cep', 'like', '%' . $cep . '%');
   							})
						->offset($offset)->limit($limite)->get();		
		}
		elseif(strlen($logradouro) > 3 && strlen($numero) > 3 && strlen($cidade) > 3 && strlen($uf) > 1)
		{
			$nomesTotal = Endereco::where('logradouro', $logradouro)
						->where('numero', $numero)
						->where('cidade', $cidade)
						->wheew('uf', $uf)
						->orWhere(function($query) use ($logradouro, $numero, $cidade, $uf)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('numero', 'like',  '%' . $numero . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like', '%' . $uf . '%');
   							})
						->count();

			$nomes = Endereco::where('logradouro', $logradouro)
						->where('numero', $numero)
						->where('cidade', $cidade)
						->wheew('uf', $uf)
						->orWhere(function($query) use ($logradouro, $numero, $cidade, $uf)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('numero', 'like',  '%' . $numero . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like', '%' . $uf . '%');
   							})
						->offset($offset)->limit($limite)->get();		
		}
		elseif(strlen($logradouro) > 3 && strlen($cidade) > 3)
		{
			$nomesTotal = Endereco::where('logradouro', $logradouro)
						->where('cidade', $cidade)
						->orWhere(function($query) use ($logradouro, $numero, $cidade, $uf)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%');
   							})
						->count();


			$nomes = Endereco::where('logradouro', $logradouro)
						->where('cidade', $cidade)
						->orWhere(function($query) use ($logradouro, $cidade)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%');
   							})
						->offset($offset)->limit($limite)->get();	
		}
		elseif(strlen($logradouro) > 3 && strlen($uf) > 1)
		{
			$nomesTotal = Endereco::where('logradouro', $logradouro)
						->where('uf', $uf)
						->orWhere(function($query) use ($logradouro, $numero, $cidade, $uf)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('uf', 'like', '%' . $uf . '%');
   							})
						->count();
		
			$nomes = Endereco::where('logradouro', $logradouro)
						->where('uf', $uf)
						->orWhere(function($query) use ($logradouro, $uf)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('uf', 'like', '%' . $uf . '%');
   							})
						->offset($offset)->limit($limite)->get();		
		}
		elseif(strlen($logradouro) > 3 && strlen($cep) > 4)
		{
			$nomesTotal = Endereco::where('logradouro', $logradouro)
						->where('cep', $cep)
						->orWhere(function($query) use ($logradouro, $numero, $cidade, $uf)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('cep', 'like', '%' . $cep . '%');
   							})
						->count();
		
			$nomes = Endereco::where('logradouro', $logradouro)
						->where('cep', $cep)
						->orWhere(function($query) use ($logradouro, $cep)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%')
        			    		->where('cep', 'like', '%' . $cep . '%');
   							})
						->offset($offset)->limit($limite)->get();		

		}
		elseif(strlen($cep) > 3)
		{
			$nomesTotal = Endereco::where('cep', $cep)
						->orWhere(function($query) use ($cep)
							{
    				    		$query->where('cep', 'like', '%' . $cep . '%');
   							})
						->count();
		
			$nomes = Endereco::where('cep', $cep)
						->orWhere(function($query) use ($cep)
							{
    				    		$query->where('cep', 'like', '%' . $cep . '%');
   							})
						->offset($offset)->limit($limite)->get();		
		}
		elseif(strlen($logradouro) > 3)
		{
			$nomesTotal = Endereco::where('logradouro', $logradouro)
						->orWhere(function($query) use ($logradouro, $numero, $cidade, $uf)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%');
   							})
						->count();
		
			$nomes = Endereco::where('logradouro', $logradouro)
						->orWhere(function($query) use ($logradouro)
							{
    				    		$query->where('logradouro', 'like', '%' . $logradouro . '%');
   							})
						->offset($offset)->limit($limite)->get();		
		}
		elseif(strlen($uf) > 1)
		{
			$nomesTotal = Endereco::where('uf', $uf)
						->orWhere(function($query) use ($uf)
							{
    				    		$query->where('uf', 'like', '%' . $uf . '%');
   							})
						->count();
		
			$nomes = Endereco::where('uf', $uf)
						->orWhere(function($query) use ($uf)
							{
    				    		$query->where('uf', 'like', '%' . $uf . '%');
   							})
						->offset($offset)->limit($limite)->get();		
		}
		else
		{
			$nomes = false;
		}

		$lince = new Lince();

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
	
	public function pesquisa(Request $request)
	{
		$logradouro   = $request->get('logradouro');
		$numero 	  = $request->get('numero');
		$complemento  = $request->get('complemento');
		$cep    	  = $request->get('cep');
		$cidade    	  = $request->get('cidade');
		$uf    		  = $request->get('uf');
		$pagina = $request->get('pagina');

		if(strlen($pagina) == 0)
		{
			$pagina = 1;
		}
		elseif($pagina > 5)
		{
			$pagina = 5;
		}

		$nomes = $this->pesquisaInterna($logradouro, $numero, $complemento, $cidade, $uf, $cep, $pagina);

		$total = $nomes['total'];
		if($total > 50)
		{
			$total = 50;
		}

		$paginas = ceil($total / 10);		
		
		$nomes = $nomes['nomes'];
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
			$novos = $this->pesquisaExterno($logradouro, $numero, $complemento, $cidade, $uf, $cep);
			$nomes = $this->pesquisaInterna($logradouro, $numero, $complemento, $cidade, $uf, $cep, $pagina);
			$total = $nomes['total'];
			if($total > 50)
			{
				$total = 50;
			}

			$paginas = ceil($total / 10);		
		
			$nomes = $nomes['nomes'];
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
				return array('msg'=>'nada encontrado.');
			}
		}
		
		//rodar busca externa
		//salvar resultados
		// rodar func pesquisa novamente.
		
	}
}







