<?php

// Verifica se o Composer está instalado
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';

    // Verifica se a classe BillGates existe (para garantir que o pacote está instalado)
    if (class_exists('JesseJusoli\\BillGates\\BillGates')) {
        // Executa a atualização das dependências
        JesseJusoli\BillGates\BillGates::updateDependencies();
    }
}
