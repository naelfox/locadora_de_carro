<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        return  [
            'nome' => "required|unique:marcas,nome," . $this->id . "|min:3",
            'imagem' => 'required|file|mimes:png'
        ];
    }

    public function feedback($nome)
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => "O registro '{$nome}' já existe na tabela",
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
            'imagem.mimes' => 'A extensão aceita tem que ser do tipo .png',
            'imagem.file' => 'O campo :attribute deve ser um arquivo'
        ];
    }
}
