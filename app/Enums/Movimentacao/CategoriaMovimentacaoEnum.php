<?php

namespace App\Enums\Movimentacao;

use App\Enums\movimentacao\TipoMovimentacaoEnum;

class CategoriaMovimentacaoEnum {

    CONST LAZER                             = 'LAZER';
    CONST COMPRASEMGERAL                    = 'COMPRASEMGERAL';
    CONST BESTEIRA                          = 'BESTEIRA';
    CONST TRABALHO                          = 'TRABALHO';
    CONST PRINCESA                          = 'PRINCESA';
    CONST SAUDE                             = 'SAUDE';
    CONST COMIDA                            = 'COMIDA';
    CONST EDUCACAO                          = 'EDUCACAO';
    CONST PAGAMENTO                         = 'PAGAMENTO';
    CONST VESTUARIO                         = 'VESTUARIO';
    CONST OUTROS                            = 'OUTROS';
    CONST TRANSPORTE                        = 'TRANSPORTE';
    CONST SALARIO                           = 'SALARIO';
    CONST AJUSTE                            = 'AJUSTE';
    CONST EXTRA                             = 'EXTRA';

    CONST __LOCALIZADOR_LAZER               = ['lazer', 'serviÃ§os', 'casa', 'eletrÃ´nicos'];
    CONST __LOCALIZADOR_COMPRASEMGERAL      = ['supermercado'];
    CONST __LOCALIZADOR_BESTEIRA            = null;
    CONST __LOCALIZADOR_TRABALHO            = null;
    CONST __LOCALIZADOR_PRINCESA            = null;
    CONST __LOCALIZADOR_SAUDE               = ['saÃºde'];
    CONST __LOCALIZADOR_COMIDA              = ['restaurante'];
    CONST __LOCALIZADOR_EDUCACAO            = ['educaÃ§Ã£o'];
    CONST __LOCALIZADOR_PAGAMENTO           = ['payment'];
    CONST __LOCALIZADOR_TRANSPORTE          = ['transporte'];
    CONST __LOCALIZADOR_OUTROS              = ['outros'];
    CONST __LOCALIZADOR_SALARIO             = null;
    CONST __LOCALIZADOR_AJUSTE              = null;
    CONST __LOCALIZADOR_EXTRA               = null;
    CONST __LOCALIZADOR_VESTUARIO           = ['vestuÃ¡rio'];

    CONST __LABEL_LAZER                     = 'Lazer';
    CONST __LABEL_COMPRASEMGERAL            = 'Compras em Geral';
    CONST __LABEL_BESTEIRA                  = 'Besteira';
    CONST __LABEL_TRABALHO                  = 'Trabalho';
    CONST __LABEL_PRINCESA                  = 'Princesa';
    CONST __LABEL_SAUDE                     = 'Saúde';
    CONST __LABEL_COMIDA                    = 'comida';
    CONST __LABEL_EDUCACAO                  = 'Educação';
    CONST __LABEL_PAGAMENTO                 = 'Pagamento';
    CONST __LABEL_OUTROS                    = 'Outros';
    CONST __LABEL_TRANSPORTE                = 'Transporte';
    CONST __LABEL_SALARIO                   = 'Salário';
    CONST __LABEL_AJUSTE                    = 'Ajuste';
    CONST __LABEL_EXTRA                     = 'Extra';
    CONST __LABEL_VESTUARIO                 = 'Vestuário';

    CONST __TIPO_LAZER                      = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_COMPRASEMGERAL             = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_BESTEIRA                   = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_TRABALHO                   = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_PRINCESA                   = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_SAUDE                      = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_COMIDA                     = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_EDUCACAO                   = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_OUTROS                     = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_TRANSPORTE                 = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_VESTUARIO                  = TipoMovimentacaoEnum::DEBITO;
    CONST __TIPO_PAGAMENTO                  = TipoMovimentacaoEnum::CREDITO;
    CONST __TIPO_SALARIO                    = TipoMovimentacaoEnum::CREDITO;
    CONST __TIPO_AJUSTE                     = TipoMovimentacaoEnum::CREDITO;
    CONST __TIPO_EXTRA                      = TipoMovimentacaoEnum::CREDITO;

}
