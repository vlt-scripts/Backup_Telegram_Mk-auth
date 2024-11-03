<?php
include('addons.class.php');
// VERIFICA SE O USUÁRIO ESTÁ LOGADO --------------------------------------------------------------
session_name('mka');
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['mka_logado']) && !isset($_SESSION['MKA_Logado'])) exit('Acesso negado... <a href="/admin/login.php">Fazer Login</a>');
// VERIFICA SE O USUÁRIO ESTÁ LOGADO --------------------------------------------------------------

// Executa o comando para listar as entradas do cron
$output = shell_exec('crontab -l 2>&1');

// Exibe as entradas do cron
header('Content-Type: text/plain');
echo $output;
?>
