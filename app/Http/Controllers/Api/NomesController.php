<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Nome;
use App\Lince;
use Illuminate\Http\Request;

use Validator;

class NomesController extends Controller
{
	public function index()
	{
		//$nomes = Nome::all();
		$nomes = Nome::take(50)->paginate(10);
		return $nomes;
	}
	
	public function view($id)
	{
		//$emails = Email::findOrFail($id);
		$nomes = Nome::find($id);
		return $nomes;
	}
	
	public function store(Request $request)
	{
		$nome = Nome::create($request->all());
		return $mome;
	}
	
	public function update(Request $request, $id)
	{
		$nome = Nome::findOrFail($id);
		$nome->update($request->all());
		return $nome;
	}
	
	public function destroy($id)
	{
		$nome = Nome::findOrFail($id);
		$nome->delete();
		return $nome;
	}
	

	public function pesquisaExterno($nome, $cidade, $uf, $cep){
		$lince = new Lince();
		$dados = $lince->consultaNome($nome, $cidade, $uf, $cep);

		if(!is_array($dados)){
			$lince->getCredencial();

			$dados = $lince->consultaNome($nome, $cidade, $uf, $cep);
		}

		if(count($dados) > 0)
		{
			$dados_novos = array();
			if(strlen($cidade) == 0){ $cidade = '';}
			if(strlen($uf) == 0){ $uf = '';}
			if(strlen($cep) == 0){ $cep = '';}
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
           		 		'cidade' => $cidade,
           		 		'uf' => $uf,
           		 		'cep' => $cep
        			));
        			
				}
			}

			Nome::insert($dados_novos);
			return true;
		}
		else
		{
			return false;
		}		
	}
	
	public function pesquisaInterna($nome, $cidade, $uf, $cep, $pagina)
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
		
		if(strlen($nome) > 3 && strlen($cidade) > 3 && strlen($uf) > 1 && strlen($cep) > 4)
		{
			$nomesTotal = Nome::where('nome', $nome)
						->where('cidade', $cidade)
						->where('uf', $uf)
						->where('cep', $cep)
						->orWhere(function($query) use ($nome, $cidade, $uf, $cep)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like',  '%' . $uf . '%')
        			    		->where('cep', 'like',  '%' . $cep . '%');
   							})
						->count();
						
			$nomes = Nome::where('nome', $nome)
						->where('cidade', $cidade)
						->where('uf', $uf)
						->where('cep', $cep)
						->orWhere(function($query) use ($nome, $cidade, $uf, $cep)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like',  '%' . $uf . '%')
        			    		->where('cep', 'like',  '%' . $cep . '%');
   							})
						->offset($offset)->limit($limite)->get();
		}
		elseif(strlen($nome) > 3 && strlen($cidade) > 3 && strlen($uf) > 1)
		{
			$nomesTotal = Nome::where('nome', $nome)
						->where('cidade', $cidade)
						->where('uf', $uf)
						->orWhere(function($query) use ($nome, $cidade, $uf)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like',  '%' . $uf . '%');
   							})
						->count();
						
			$nomes = Nome::where('nome', $nome)
						->where('cidade', $cidade)
						->where('uf', $uf)
						->orWhere(function($query) use ($nome, $cidade, $uf)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%')
        			    		->where('uf', 'like',  '%' . $uf . '%');
   							})
						->offset($offset)->limit($limite)->get();
		}
		elseif(strlen($nome) > 3 && strlen($cidade) > 3)
		{
			$nomesTotal = Nome::where('nome', $nome)
						->where('cidade', $cidade)
						->orWhere(function($query) use ($nome, $cidade)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%');
   							})
						->count();

			$nomes = Nome::where('nome', $nome)
						->where('cidade', $cidade)
						->orWhere(function($query) use ($nome, $cidade)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cidade', 'like',  '%' . $cidade . '%');
   							})
						->offset($offset)->limit($limite)->get();
		}
		elseif(strlen($nome) > 3 && strlen($uf) > 1)
		{
			$nomesTotal = Nome::where('nome', $nome)
						->where('uf', $uf)
						->orWhere(function($query) use ($nome, $uf)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('uf', 'like',  '%' . $uf . '%');
   							})
						->count();

			$nomes = Nome::where('nome', $nome)
						->where('uf', $uf)
						->orWhere(function($query) use ($nome, $uf)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('uf', 'like',  '%' . $uf . '%');
   							})
						->offset($offset)->limit($limite)->get();
						
		}
		elseif(strlen($nome) > 3 && strlen($cep) > 4)
		{
			$nomesTotal = Nome::where('nome', $nome)
						->where('cep', $cep)
						->orWhere(function($query) use ($nome, $cep)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cep', 'like',  '%' . $cep . '%');
   							})
						->count();
		
			$nomes = Nome::where('nome', $nome)
						->where('cep', $cep)
						->orWhere(function($query) use ($nome, $cep)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%')
        			    		->where('cep', 'like',  '%' . $cep . '%');
   							})
						->offset($offset)->limit($limite)->get();

		}
		elseif(strlen($nome) > 3)
		{
			$nomesTotal = Nome::where('nome', $nome)
						->orWhere(function($query) use ($nome, $cidade)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%');
   							})
						->count();
					
			$nomes = Nome::where('nome', $nome)
						->orWhere(function($query) use ($nome, $cidade)
							{
    				    		$query->where('nome', 'like', '%' . $nome . '%');
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
	
	public function pesquisa(Request $request) {
		$nome   = $request->get('nome');
		$cidade = $request->get('cidade');
		$uf     = $request->get('uf');
		$cep    = $request->get('cep');
		$pagina = $request->get('pagina');

		if(strlen($pagina) == 0){
			$pagina = 1;
		}elseif($pagina > 5){
			$pagina = 5;
		}

		$nomes = $this->pesquisaInterna($nome, $cidade, $uf, $cep, $pagina);
		
		$total = $nomes['total'];
		if($total > 50){
			$total = 50;
		}

		$paginas = ceil($total / 10);		
		
		$nomes = $nomes['nomes'];

		if(count($nomes) > 11){
			$nomes = array(
				'resultado' => $nomes,
				'paginas'   => $paginas,
				'pagina' 	=> $pagina
			);
			return $nomes;
		}else{
			$novos = $this->pesquisaExterno($nome, $cidade, $uf, $cep);

			$nomes = $this->pesquisaInterna($nome, $cidade, $uf, $cep, $pagina);
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







