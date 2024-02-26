<?php

namespace App\Models\Repository;

use App\Models\Interface\IMovimentacoesRepository;
use App\Models\Movimentacoes;
use Illuminate\Support\Facades\DB;

class MovimentacoesRepository implements IMovimentacoesRepository {

    const DATABASE = 'Movimentacoes';

    public function __construct() {}


    public function save ( array $item ): bool {

        $record = DB::table(self::DATABASE)->insert([
            'dt_movimentacao'       => $item['dt_movimentacao'],
            'dt_pagamento'          => $item['dt_pagamento'],
            'mes'                   => $item['mes'],
            'ano'                   => $item['ano'],
            'meio'                  => $item['meio'],
            'parcelas'              => $item['parcelas'],
            'parcela_atual'         => $item['parcela_atual'],
            'descricao'             => $item['descricao'],
            'grupo'                 => $item['grupo'],
            'area'                  => $item['area'],
            'valor'                 => $item['valor'],
            'tipo'                  => $item['tipo'],
            'status'                => $item['status']
        ]);

        return $record;
    }

    public function saveLote ( $itens ): bool {

        DB::table(self::DATABASE)->insert($itens);

        return TRUE;
    }

    public function update ( array $item ) {

        $record = Movimentacoes::find($item['id']);

        $record->dt_movimentacao    = $item['dt_movimentacao'];
        $record->dt_pagamento       = $item['dt_pagamento'];
        $record->mes                = $item['mes'];
        $record->ano                = $item['ano'];
        $record->meio               = $item['meio'];
        $record->parcelas           = $item['parcelas'];
        $record->parcela_atual      = $item['parcela_atual'];
        $record->descricao          = $item['descricao'];
        $record->grupo              = $item['grupo'];
        $record->area               = $item['area'];
        $record->valor              = $item['valor'];
        $record->tipo               = $item['tipo'];
        $record->status             = $item['status'];

        $record->save();

        return $record->toArray();
    }

    public static function verificaExiste ( array $item ) {

        $record = DB::table('Movimentacoes')
            ->where('dt_movimentacao', '=', $item['dt_movimentacao'])
            ->where('mes', '=', $item['mes'])
            ->where('ano', '=', $item['ano'])
            ->where('meio', '=', $item['meio'])
            ->where('parcelas', '=', $item['parcelas'])
            ->where('parcela_atual', '=', $item['parcela_atual'])
            ->where('descricao', '=', $item['descricao'])
            ->where('valor', '=', $item['valor'])->exists();

        return $record;

    }

}
