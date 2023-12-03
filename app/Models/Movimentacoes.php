<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimentacoes extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes';
    public $timestamps = false;

    protected $fillable = [
        'dt_movimentacao',
        'mes',
        'ano',
        'meio',
        'parcelas',
        'parcela_atual',
        'descricao',
        'grupo',
        'area',
        'valor',
        'tipo',
        'status',
        'dt_pagamento',
        'id_destinatario',
    ];
}
