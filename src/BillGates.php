<?php

namespace JesseJusoli\BillGates;

use Symfony\Component\Process\Process;

class BillGates
{
    /**
     * Atualiza as dependências do Composer, garantindo que seja executada no máximo uma vez por dia.
     *
     * @return bool True se a atualização foi realizada com sucesso, false se não foi necessário atualizar ou houve um erro.
     * @throws \RuntimeException Se houver um erro durante a atualização das dependências.
     */
    public static function updateDependencies()
    {
        $lastUpdateFile = __DIR__ . '/../last_update.txt';
        $lastUpdate = file_exists($lastUpdateFile) ? file_get_contents($lastUpdateFile) : null;

        // Verifica se já se passou um dia desde a última atualização
        if ($lastUpdate === null || strtotime($lastUpdate) < strtotime('-1 day')) {
            $composerPath = realpath(__DIR__ . '/../../composer.json');
            $composerPath = escapeshellarg($composerPath); // Escapa o caminho para evitar injeção de comandos maliciosos

            // Cria um novo processo para executar o comando Composer
            $process = new Process(['composer', 'update', '-d', dirname($composerPath)]);
            $process->run();

            // Verifica se a execução do comando foi bem-sucedida
            if ($process->isSuccessful()) {
                // Atualiza a data da última atualização
                file_put_contents($lastUpdateFile, date('Y-m-d H:i:s'));
                return true; // Indica que a atualização foi bem-sucedida
            } else {
                throw new \RuntimeException('Erro ao atualizar as dependências do Composer: '.$process->getErrorOutput());
            }
        } else {
            // A atualização já foi realizada nas últimas 24 horas
            return false; // Indica que a atualização não foi realizada
        }
    }
}
