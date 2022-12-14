<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Usuario;
use App\Categoria;
use App\Mail\EmailUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Http::get('https://api.first.org/data/v1/countries?region=South America');

        $paisesJson = $response->json();
        $paisesArray = [];

        foreach($paisesJson['data'] as $pais){
            $paisesArray[] =  $pais['country'];
        }
        $resp = [
            "list" =>Usuario::with('categorias')->get(),
            "categories" => Categoria::all(),
            "paises" => $paisesArray
        ];


        return $resp;
    }


    public function getPaises(){
        $response = Http::get('https://api.first.org/data/v1/countries?region=South America');
        $paisesJson = $response->json();
        $paisesArray = [];

        foreach($paisesJson['data'] as $pais){
            $paisesArray[] =  $pais['country'];
        }
        $resp = [
            "code" => 200,
            "data" =>$paisesArray
        ];
        return $resp;
    }

    public function getCategorias(){
        return Categoria::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $messages = [
            'email.unique' => 'Este email ya esta registrado',
            'email.required' => 'Campo email es obligatorio',
            'cedula.unique' => 'Esta cedula ya esta registrada',
            'celular.digits_between' => 'Celular debe tener 10 digitos',
        ];


        $validator = Validator::make($data, [
            'nombres' => 'required|min:5|max:100',
            'apellidos' => 'required|max:100',
            'email' => 'required|unique:usuario|email|max:150',
            'cedula' => 'required|unique:usuario',
            'direccion' => 'required|max:180',
            'celular' => 'required|digits_between:10,10|numeric'
        ],$messages);

        if ($validator->fails()) {

            return response([
                "code"    => 500,
                'errors' => $validator->errors()->all()
            ]);
        }


        Usuario::create($data);
        //Mail::to($data['email'])->send(new EmailUser());
        return [
            "code" => 200,
            "mensaje" => "Creado exitosamente"
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = Usuario::with('categorias')->where("id", $id)->first();
        return response()->json($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        $messages = [
            'celular.digits_between' => 'Celular debe tener 10 digitos',
        ];


        $validator = Validator::make($data, [
            'nombres' => 'required|min:5|max:100',
            'apellidos' => 'required|max:100',
            'pais' => 'required',
            'direccion' => 'required|max:180',
            'celular' => 'required|digits_between:10,10|numeric'
        ],$messages);

        if ($validator->fails()) {

            return response([
                "code"    => 500,
                'errors' => $validator->errors()->all()
            ]);
        }

        Usuario::whereId($id)->update($data);

        return [
            "code" => 200,
            "mensaje" => "Actualizado exitosamente"
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Usuario::whereId($id)->delete();

        return [
            "code" => 200,
            "mensaje" => "Eliminado exitosamente"
        ];
    }

    public function search($buscar=''){
        if($buscar!= ''){
            $users = Usuario::where('nombres', 'LIKE', "%$buscar%")
            ->Orwhere('apellidos', 'LIKE', "%$buscar%")
            ->Orwhere('cedula', 'LIKE', "%$buscar%")
            ->Orwhere('pais', 'LIKE', "%$buscar%")
            ->Orwhere('direccion', 'LIKE', "%$buscar%")
            ->Orwhere('celular', 'LIKE', "%$buscar%")
            ->Orwhere('id', 'LIKE', "%$buscar%")
            ->with('categorias')
            ->get();
        }else{
            $users = Usuario::with('categorias')->get();
        }

        //dd($users);
        return $users;
    }
}
