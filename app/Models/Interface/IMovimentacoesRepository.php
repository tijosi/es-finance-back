<?php

namespace App\Models\Interface;

interface IMovimentacoesRepository {

    public function __construct();

    public function save( array $item ): bool;
    public function saveLote( array $itens ): bool;

}
