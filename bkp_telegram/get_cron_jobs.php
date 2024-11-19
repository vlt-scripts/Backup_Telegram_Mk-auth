<?php
include('addons.class.php');

// VERIFICA SE O USUÁRIO ESTÁ LOGADO --------------------------------------------------------------
session_name('mka');
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['mka_logado']) && !isset($_SESSION['MKA_Logado'])) {
    exit('Acesso negado... <a href="/admin/login.php">Fazer Login</a>');
}
// VERIFICA SE O USUÁRIO ESTÁ LOGADO --------------------------------------------------------------

// Comando específico do script para filtrar no crontab
$comandoEspecifico = '/opt/mk-auth/dados/bkp_telegram/script.sh';

// Executa o comando para listar as entradas do cron
$output = shell_exec('crontab -l 2>&1');

// Filtra apenas as linhas que contêm o comando específico
$linhas = explode(PHP_EOL, $output);
$agendamentosFiltrados = array_filter($linhas, function ($linha) use ($comandoEspecifico) {
    return strpos($linha, $comandoEspecifico) !== false;
});

// Exibe as entradas filtradas do cron
header('Content-Type: text/plain');
if (!empty($agendamentosFiltrados)) {
    echo implode(PHP_EOL, $agendamentosFiltrados);
} else {
    echo "Nenhum agendamento configurado para o script.";
}
?>
