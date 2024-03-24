<?php

namespace JesseJusoli\BillGates;

class BillGates
{
    public static function updateDependencies()
    {
        $lastUpdateFile = __DIR__ . '/../last_update.txt';
        $lastUpdate = file_exists($lastUpdateFile) ? file_get_contents($lastUpdateFile) : null;

        // Verifica se já se passou um dia desde a última atualização
        if ($lastUpdate === null || strtotime($lastUpdate) < strtotime('-1 day')) {
            $composerPath = realpath(__DIR__ . '/../../composer.json');
            $composerPath = escapeshellarg($composerPath); // Escapa o caminho para evitar injeção de comandos maliciosos
            $command = 'composer update -d ' . dirname($composerPath);

            // Executa o comando e captura a saída e o código de retorno
            exec($command, $output, $returnCode);

            // Verifica se a execução do comando foi bem-sucedida
            if ($returnCode === 0) {
                // Atualiza a data da última atualização
                file_put_contents($lastUpdateFile, date('Y-m-d H:i:s'));
                return true; // Indica que a atualização foi bem-sucedida
            } else {
                return false; // Indica que houve um erro durante a atualização
            }
        } else {
            // A atualização já foi realizada nas últimas 24 horas
            return false; // Indica que a atualização não foi realizada
        }
    }
}
