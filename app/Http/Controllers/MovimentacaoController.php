<?php

namespace App\Http\Controllers;

use App\Enums\Movimentacao\AreaMovimentacaoEnum;
use App\Enums\Movimentacao\CategoriaMovimentacaoEnum;
use App\Enums\Movimentacao\StatusMovimentacaoEnum;
use App\Enums\Movimentacao\TipoMovimentacaoEnum;
use App\Models\Movimentacoes;
use App\Models\Repository\MovimentacoesRepository;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimentacaoController extends Controller
{
    public function handle(Request $request) {

        if($request->method() == 'GET')             return $this->getList($request);

        else if ($request->method() == 'POST')      return $this->save($request->input());

        else if ($request->method() == 'DELETE')    return $this->delete($request);

    }

    private function getList() {
        $registros = DB::table('Movimentacoes')->get();

        $data = array_map(function ($line) {
            $line->parcela =
                ($line->parcela_atual < 10 ? '0' . $line->parcela_atual : $line->parcela_atual) . '/' .
                ($line->parcelas < 10 ? '0' . $line->parcelas : $line->parcelas);

            return $line;
        }, $registros->toArray());

        $this->atualizaStatus($data);

        return $data;
    }

    private function atualizaStatus( array $data ) {

        foreach ($data as $key => $value) {
            $value = (array) $value;

            if ($value['status'] != StatusMovimentacaoEnum::PENDENTE) continue;

            $dtPag = new DateTime($value['dt_pagamento']);
            $dtAtual = new DateTime();
            $vencido = $dtPag < $dtAtual;

            if ( !$vencido ) continue;

            $repositoryMov = new MovimentacoesRepository();
            $value['status'] = StatusMovimentacaoEnum::VENCIDO;

            $repositoryMov->update($value);
        }

        return $data;
    }

    private function save($param) {
        $repositoryMov = new MovimentacoesRepository();

        try {
            if ( isset($param[0]) ) {
                return $repositoryMov->saveLote($param);
            } else {
                if (!empty($param['id'])) {
                    $record = $repositoryMov->update($param);
                } else {
                    $parcelas = $param['parcelas'] - $param['parcela_atual'] + 1;

                    for ($i=0; $i < $parcelas; $i++) {

                        $dateMov = new DateTime($param['dt_pagamento']);
                        $param['mes'] = (int) $dateMov->format('m');
                        $param['ano'] = (int) $dateMov->format('Y');

                        $repositoryMov->save((array) $param);

                        if ($i+1 >= $parcelas) return;

                        $param['parcela_atual'] += 1;
                        $param['dt_pagamento']  = ($dateMov->add(new DateInterval('P1M')))->format('Y-m-d');
                    }
                }
            }
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }


    }

    private function delete( Request $item) {
        return Movimentacoes::findOrFail($item->input('id'))->delete();
    }

    public function getImportar( Request $request ) {

        if (!$request->hasFile('file')) {
            throw new Exception('Arquivo não encontrado');
        }

        $file = $request->file('file');
        $fileExtension = $file->extension();
        $contentFile = file_get_contents($file->getRealPath());

        switch ($fileExtension) {
            case 'csv':
                throw new Exception('A extenão ' . $fileExtension . ' ainda está em desenvolvimento');
                break;
            case ('ofx' || 'text'):
                $result = $this->tratarOfx($contentFile);
                break;
            default:
                throw new Exception('extensão do arquivo selecionado (' . $fileExtension . ') não está na lista');
        }

        return response()->json($result);
    }

    private function tratarOfx($contentFile) {
        preg_match_all('/<([A-Z]+)>([^<]*?)<\/\1>/',$contentFile, $tExtrato, PREG_SET_ORDER);
        preg_match_all('/<([A-Z]+)>(.*?)\n/',$contentFile, $tCartao, PREG_SET_ORDER);

        $tags = count($tExtrato) == 0 ? $tCartao : $tExtrato;
        foreach ($tags as $key => $value) {

            if ($value[1] == "ORG")         $result['instituicao'] = $value[2];
            if ($value[1] == "DTSTART")     $result['dt_inicial'] = date('Y-m-d', strtotime(substr($value[2], 0, 8)));
            if ($value[1] == "DTEND")       $result['dt_final'] = date('Y-m-d', strtotime(substr($value[2], 0, 8)));

            if ($value[1] == "TRNTYPE") {

                $descricao = $this->getFileDescricao($tags[$key + 4][2]);

                preg_match('/(\d+)\/(\d+)/', $descricao[0], $detailsParcela);

                $result['movimentacoes'][] = [
                    'tipo' => $value[2] == 'DEBIT' ? TipoMovimentacaoEnum::DEBITO : TipoMovimentacaoEnum::CREDITO,
                    'dt_movimentacao' => date('Y-m-d', strtotime(substr($tags[$key + 1][2], 0, 8))),
                    'valor' => abs((float)$tags[$key + 2][2]),
                    'parcelas' => $detailsParcela[2] ?? '1',
                    'parcela_atual' => $detailsParcela[1] ?? '1',
                    'transacao_id' => $tags[$key + 3][2],
                    'descricao' => $descricao[0],
                    'destinatario' => $descricao[1] ?? $descricao[0],
                    'documento_destinatario' => $descricao[2] ?? NULL,
                    'flg_warning_parcela' => false,
                ];

                if ( preg_match('/(\d+)\/(\d+)/', $descricao[0], $detailsParcela) ) {

                    $dtParcela = new DateTime( date( 'Y-m-d', strtotime( substr($tags[$key + 1][2], 0, 8) ) ) );

                    for ((int) $detailsParcela[1] ; (int) $detailsParcela[1] < (int) $detailsParcela[2] ; (int) $detailsParcela[1]++) {

                        $parcelaAtual = $detailsParcela[1];
                        $parcelaPosterior = ((int) $detailsParcela[1] + 1);

                        $descricao[0] = str_replace($parcelaAtual . '/', $parcelaPosterior . '/', $descricao[0]);


                        $result['movimentacoes'][] = [
                            'tipo' => $value[2] == 'DEBIT' ? TipoMovimentacaoEnum::DEBITO : TipoMovimentacaoEnum::CREDITO,
                            'dt_movimentacao' => $dtParcela->format('Y-m-d'),
                            'dt_pagamento' => $dtParcela->add(new DateInterval("P1M"))->format('Y-m-d'),
                            'valor' => abs((float)$tags[$key + 2][2]),
                            'parcelas' => $detailsParcela[2],
                            'parcela_atual' => $parcelaAtual,
                            'transacao_id' => $tags[$key + 3][2],
                            'descricao' => $descricao[0],
                            'destinatario' => $descricao[1] ?? $descricao[0],
                            'documento_destinatario' => $descricao[2] ?? NULL,
                            'flg_warning_parcela' => true,
                        ];

                    }
                }

            }
        }

        return $result;
    }

    private function getFileDescricao($descricao): array {
        return explode(' - ', $descricao);
    }

    public function importar(Request $request) {

        $dados = $request->input();

        switch ($dados['tipo_importacao']) {
            case 'Cartão':
                $this->importarCartao($dados);
                break;
            case 'Transferência':
                $this->importarTransferencia($dados);
                break;

            default:
                throw new Exception('Tipo de Importação não indentificado');
        }

    }

    private function importarCartao($dados) {


        foreach ($dados['registros'] as $mov) {

            $dateMov = new DateTime($mov['dt_movimentacao']);
            $area = $meio['area'] ?? $mov['tipo'] == TipoMovimentacaoEnum::CREDITO ?
                AreaMovimentacaoEnum::RECEITA :
                AreaMovimentacaoEnum::DESPESA;


            if ($mov['flg_warning_parcela']) {

                $datePag = $mov['dt_pagamento'];
                $status = $meio['status'] ?? StatusMovimentacaoEnum::PENDENTE;

            } else {

                $datePag = ( new DateTime($dados['dt_pagamento']) )->format('Y-m-d');
                $status = $meio['status'] ?? $mov['tipo'] == TipoMovimentacaoEnum::CREDITO ?
                    StatusMovimentacaoEnum::RECEBIDO :
                    StatusMovimentacaoEnum::PAGO;

            }

            $insert = [
                'dt_movimentacao' => $mov['dt_movimentacao'],
                'mes' => $dateMov->format('m'),
                'ano' => $dateMov->format('Y'),
                'descricao' => $mov['descricao'],
                'dt_pagamento' => $datePag,
                'meio' => $mov['meio'] ?? 'CARTAO_NUBANK',
                'parcelas' => empty($mov['parcelas']) ? '1' : $mov['parcelas'],
                'parcela_atual' => empty($mov['parcela_atual']) ? '1' : $mov['parcela_atual'],
                'valor' => $mov['valor'],
                'area' => $area,
                'grupo' => $mov['grupo'] ?? CategoriaMovimentacaoEnum::OUTROS,
                'tipo' => $mov['tipo'] ?? NULL,
                'status' => $status,
                'destinatario' => $mov['destinatario'] ?? NULL
            ];

            $existe = MovimentacoesRepository::verificaExiste($insert);

            if ($existe || $insert['descricao'] == 'Pagamento recebido') continue;

            $inserts[] = $insert;


        }

        $repository = new MovimentacoesRepository();
        $repository->saveLote($inserts);

        return TRUE;
    }

    private function importarTransferencia($dados) {

        return $dados;
    }

}
