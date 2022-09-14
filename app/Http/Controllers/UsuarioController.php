<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Usuario;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'pais' => 'required',
            'email' => 'required|unique:usuario|email|max:150',
            'cedula' => 'required|unique:usuario',
            'direccion' => 'required|max:180',
            'celular' => 'required|digits_between:10,10|numeric',
            'categoria_id' => 'required'
        ],$messages);

        if ($validator->fails()) {

            return response([
                "code"    => 500,
                'errors' => $validator->errors()->all()
            ]);
        }


        Usuario::create($data);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
