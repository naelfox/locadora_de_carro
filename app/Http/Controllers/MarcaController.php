<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{

    private $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->all();
        return $marcas;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $nome =  $request->input('nome');
        $request->validate($this->marca->rules(), $this->marca->feedback($nome));

        $image = $request->file('imagem');

        $imagemUrn =  $image->store('imagens', 'public');


        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagemUrn
        ]);
        return  response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->find($id);
        if (is_null($marca)) {
            return response()->json(['erro' => 'O recurso não foi encontrado'], 404);
        }
        return $marca;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if (is_null($marca)) {
            return response()->json(['erro' => 'Não foi possível realizar a atualização o recurso não foi encontrado.'], 404);
        }


        $nome =  $request->input('nome');

        if ($request->method() == 'PATCH') {

            $regrasDinamicas = [];


            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback($nome));
        } else {


            $request->validate($marca->rules(), $marca->feedback($nome));
        }



        if (!is_null($request->file('imagem'))) {
            Storage::disk('public')->delete($marca->imagem);
        }

        $image = $request->file('imagem');

        $imagemUrn =  $image->store('imagens', 'public');


        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagemUrn
        ]);

        // $marca->update($request->all());
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if (is_null($marca)) {
            return response()->json(
                [
                    'erro' => 'Não foi possível excluir o recurso não foi encontrado'
                ],
                404
            );
        }



        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return ['msg' => 'Marca removida com sucesso'];
    }
}
