<?php

namespace App\Enums\Movimentacao;

class StatusMovimentacaoEnum {

    CONST PENDENTE             = 'PENDENTE';
    CONST VENCIDO              = 'VENCIDO';
    CONST RECEBIDO             = 'RECEBIDO';
    CONST PAGO                 = 'PAGO';
    CONST VINCULADO            = 'VINCULADO';

    CONST __LABEL_PENDENTE     = 'Pendente';
    CONST __LABEL_VENCIDO      = 'Vencido';
    CONST __LABEL_RECEBIDO     = 'Recebido';
    CONST __LABEL_PAGO         = 'Pago';
    CONST __LABEL_VINCULADO    = 'Vinculado';

    CONST __TIPO_PENDENTE      = TipoMovimentacaoEnum::AMBOS;
    CONST __TIPO_VENCIDO       = TipoMovimentacaoEnum::AMBOS;
    CONST __TIPO_RECEBIDO      = TipoMovimentacaoEnum::CREDITO;
    CONST __TIPO_PAGO          = TipoMovimentacaoEnum::DEBITO;

}
