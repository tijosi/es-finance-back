<?php

namespace App\Enums;

use Exception;
use Illuminate\Support\Str;
use ReflectionClass;

class Tenum {

    public function getEnumValues($enumClass) {

        $constants = $this->getConstants($enumClass);

        $enumValues = [];
        $i = 0;
        foreach ($constants  as $key => $value) {

            if (strpos($key, '__') === false) $enumValues[$i] = ['id'    => $value];

            else {

                $pattern = '/__([A-Z]+)_(.+)/';
                if (preg_match($pattern, $key, $matches)) {

                    $id = $matches[2];
                    $property = strtolower($matches[1]);

                    foreach ($enumValues as $keyEnum => $enumV) {
                        if ($enumV['id'] != $id) continue;

                        $enumValues[$keyEnum][$property] = $value;
                    }

                } else {
                    echo "Padrão não encontrado na string da classe: ". $enumClass;
                }

            }
            $i++;
        }

        return response()->json($enumValues);
    }

    private function getConstants($enumClass) {

        $nomeDaClasse = Str::studly($enumClass);
        $pastaPadrao = base_path('app/Enums');

        $arquivos = array_merge(
            glob("$pastaPadrao/*.php"),
            glob("$pastaPadrao/**/*.php"),
            glob("$pastaPadrao/**/**/*.php")
        );

        foreach ($arquivos as $arquivo) {

            if (!strpos($arquivo, $nomeDaClasse)) continue;
            $classe = 'App\Enums' . str_replace([$pastaPadrao, '/', '.php'], ['', '\\', ''], $arquivo);

        }

        if (!class_exists($classe)) {
            throw new Exception('A classe ' . $nomeDaClasse . '. Não foi encontrada');
        }


        $reflector = new ReflectionClass($classe);
        $constants = $reflector->getConstants();

        return $constants;
    }

}
