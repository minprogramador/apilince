<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Email;

use Illuminate\Http\Request;

use Validator;

class EmailsController extends Controller
{
	public function index()
	{
		$emails = Email::all();
		//$emails = Email::paginate();
		return $emails;
	}
	
	public function view($id)
	{
		//$emails = Email::findOrFail($id);
		$emails = Email::find($id);
		return $emails;
	}
	
	public function store(Request $request)
	{
		$email = Email::create($request->all());
		return $email;
	}
	
	public function update(Request $request, $id)
	{
		$email = Email::findOrFail($id);
		$email->update($request->all());
		return $email;
	}
	
	public function destroy($id)
	{
		$email = Email::findOrFail($id);
		$email->delete();
		return $email;
	}
	
	public function pesquisa($email)
	{
		$emails = Email::where('email', $email)->orWhere('email', 'like', '%' . $email . '%')->get();
		//$emails = Email::where('email', $email)->orWhere('email', 'like', '%' . $email . '%')->paginate();

		if(count($emails) > 0)
		{
			return $emails;
		}
		
		//nao tem no bd? busca externo e salva no bd e mostra o where novamente.
	
		// faz pesquisa no BD, se retornar menos que 50, fazer busca na lince
		// salvar dados achados.
		//mostrar pro usuario.
		// fazer paginacao...
	}
}







