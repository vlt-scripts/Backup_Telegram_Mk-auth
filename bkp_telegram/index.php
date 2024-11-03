<?php
include('addons.class.php');

// VERIFICA SE O USUÁRIO ESTÁ LOGADO --------------------------------------------------------------
session_name('mka');
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['mka_logado']) && !isset($_SESSION['MKA_Logado'])) exit('Acesso negado... <a href="/admin/login.php">Fazer Login</a>');
// VERIFICA SE O USUÁRIO ESTÁ LOGADO --------------------------------------------------------------

$manifestTitle = isset($Manifest->name) ? htmlspecialchars($Manifest->name) : '';
$manifestVersion = isset($Manifest->version) ? htmlspecialchars($Manifest->version) : '';
?>

<!DOCTYPE html>
<html lang="pt-BR" class="<?= isset($_SESSION['MM_Usuario']) ? '' : 'has-navbar-fixed-top' ?>">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <title>MK - AUTH :: <?= $manifestTitle . " - V " . $manifestVersion; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../estilos/mk-auth.css">
    <link rel="stylesheet" href="../../estilos/font-awesome.css">
    <link href="../../estilos/bi-icons.css" rel="stylesheet" type="text/css" />
    <script src="../../scripts/jquery.js"></script>
    <script src="../../scripts/mk-auth.js"></script>

    <!-- Incluir seus estilos CSS aqui -->
    <style>
        /* Estilos CSS personalizados */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }

        .breadcrumb {
            background: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="time"],
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .log-container {
            max-width: 100%;
            margin: 20px 0;
            text-align: center;
            border: 1px solid #ccc;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            height: 300px;
        }

        .log-container pre {
            margin: 0;
            white-space: pre-wrap;
            text-align: left;
        }

        .toggle-button {
            background: none;
            border: none;
            cursor: pointer;
            float: right;
        }

        .hidden {
            display: none;
        }

        .button-group {
            display: flex;
            gap: 10px;
        }
		.red-text {
         color: red;
        }
    </style>

    <script>
        function toggleForm() {
            var formContainer = document.getElementById('form-container');
            if (formContainer.classList.contains('hidden')) {
                formContainer.classList.remove('hidden');
            } else {
                formContainer.classList.add('hidden');
            }
        }

        // Função para exibir uma mensagem de sucesso em uma tela popup
        function showSuccessPopup(message) {
            alert(message);
        }

        // Função para enviar apenas o formulário de execução do script
        function submitExecutionForm() {
            document.getElementById('executionForm').submit();
        }

        function toggleScheduledTasks() {
            var scheduledTasksContainer = document.getElementById('scheduledTasksContainer');
            if (scheduledTasksContainer.classList.contains('hidden')) {
                loadScheduledTasks();
                scheduledTasksContainer.classList.remove('hidden');
            } else {
                scheduledTasksContainer.classList.add('hidden');
            }
        }

        function loadScheduledTasks() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById('scheduledTasksContent').textContent = this.responseText;
                }
            };
            xhttp.open("GET", "get_cron_jobs.php", true);
            xhttp.send();
        }
		function toggleHorario() {
            var horarioContainer = document.getElementById('horario-container');
            if (horarioContainer.classList.contains('hidden')) {
                horarioContainer.classList.remove('hidden');
            } else {
                horarioContainer.classList.add('hidden');
            }
        }
    </script>
</head>

<body>
    <?php include('../../topo.php'); ?>

    <div class="container">
        <nav class="breadcrumb has-bullet-separator is-centered" aria-label="breadcrumbs">
            <ul>
                <li><a href="#"> ADDON</a></li>
                <li class="is-active">
                    <a href="#" aria-current="page"> <?= $manifestTitle . " - V " . $manifestVersion; ?> </a>
                </li>
            </ul>
        </nav>

        <button id="mostrarConfiguracoes" class="toggle-button" onclick="toggleForm()">
            <img src="icon_config.png" alt="Mostrar Configurações" style="width: 30px; height: 30px;">
        </button>

        <div id="form-container" class="form-container hidden">
            <form action="" method="post">
                <label for="token">Token do Telegram:</label>
                <input type="text" id="token" name="token" required>

                <label for="chatID">Chat ID do Telegram:</label>
                <input type="text" id="chatID" name="chatID" required>

                <label for="log_table">Tabela de Logs: <span class="red-text">Ate a V23.06 usar a Tabela sis_logs / a partir 23.07 - 24.xx usar a Tabela sis_ativ</span></label>
                <select id="log_table" name="log_table" required>
                    <option value="sis_logs">sis_logs</option>
                    <option value="sis_ativ">sis_ativ</option>
                </select>

                <button type="submit" name="generate_script">Gerar Script .sh</button>
            </form>
        </div>

        <button id="mostrarHorario" class="toggle-button" onclick="toggleHorario()">
            <img src="icon_agen.png" alt="Mostrar Configurações" style="width: 30px; height: 30px;">
        </button>

<div id="horario-container" class="form-container hidden">
    <form id="scheduleForm" action="" method="post">
        <label for="schedule_time">Horário de Agendamento (HH:MM):</label>
        <input type="time" id="schedule_time" name="schedule_time" required>
        <div class="button-group">
            <button type="submit" name="schedule_script">Agendar Script</button>
        <!--<button type="button" onclick="toggleScheduledTasks()">Ver Tarefas Agendadas</button>-->
            <button type="button" onclick="confirmDelete()">Excluir Agendamento</button>
        </div>
    </form>
</div>

<script>
    function confirmDelete() {
        if (confirm("Tem certeza que deseja excluir o agendamento?")) {
            // Definir o valor do botão delete_schedule
            document.getElementById('scheduleForm').innerHTML += '<input type="hidden" name="delete_schedule" value="1">';
            // Enviar o formulário
            document.getElementById('scheduleForm').submit();
        } else {
            // Caso contrário, não fazer nada
            return false;
        }
    }
</script>


        <!--<div id="scheduledTasksContainer" class="log-container hidden">
            <h3>Tarefas Agendadas</h3>
            <pre id="scheduledTasksContent"></pre>
        </div>-->

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            function clean_input($data) {
                return htmlspecialchars(stripslashes(trim($data)));
            }

            if (isset($_POST['generate_script'])) {
                $token = isset($_POST['token']) ? clean_input($_POST['token']) : '';
                $chatID = isset($_POST['chatID']) ? clean_input($_POST['chatID']) : '';
                $logTable = isset($_POST['log_table']) ? clean_input($_POST['log_table']) : 'sis_logs';
                if (!empty($token) && !empty($chatID)) {
$scriptContent = <<<SCRIPT
#!/bin/bash
# Script para backup e envio para Telegram

# Recebe os valores do formulário PHP
Token="$token"
ChatID="$chatID"

# Diretório onde estão os arquivos de backup
DirArqBkp="/opt/mk-auth/bckp/"

# Captura a data e hora atual
DataHora=\$(date +"%Y%m%d_%H%M%S")
Data=\$(date +"%d/%m/%Y")
Hora=\$(date +"%H:%M:%S")
Notifica="Mk-Auth - Backup do dia \$Data às \$Hora"

# Formato de compactação desejado: "zip" ou "tar"
Compactacao="zip"

# Configurações do banco de dados
DBHost="localhost"
DBUser="root"
DBPassword="vertrigo"
DBName="mkradius"

# Arquivo de log para registrar as mensagens
LogFile="/opt/mk-auth/dados/bkp_telegram/backup_telegram.log"

# Função para gerar um identificador único no formato desejado
generate_id() {
    head /dev/urandom | tr -dc 'A-Z0-9' | head -c 24
}

# Função para registrar logs
log_message() {
    local mensagem="\$1"
    local id=\$(generate_id)
    local data_hora=\$(date +'%d-%m-%Y %H:%M:%S')
    echo "\$data_hora - \$mensagem" >> "\$LogFile"
    
    # Inserir log no banco de dados
    mysql -h "\$DBHost" -u "\$DBUser" -p"\$DBPassword" "\$DBName" -e "INSERT INTO $logTable (id, registro, data, login, tipo, operacao) VALUES ('\$id', '\$mensagem', NOW(), 'mk-bot', 'admin', 'OPERFALL');"
}

# Função para delay de aproximadamente 2 segundos
delay_2s() {
    end=\$((SECONDS+2))
    while [ \$SECONDS -lt \$end ]; do
        :
    done
}

# Verifica se o diretório de backup existe
if [ ! -d "\$DirArqBkp" ]; then
    echo "Diretório de backup não encontrado: \$DirArqBkp"
    log_message "Diretório de backup não encontrado: \$DirArqBkp"
    exit 1
fi

# Verifica se existem arquivos de backup no diretório
if [ ! "\$(ls -A \$DirArqBkp)" ]; then
    echo "Nenhum arquivo de backup encontrado em: \$DirArqBkp"
    log_message "Nenhum arquivo de backup encontrado em: \$DirArqBkp"
    exit 1
fi

# Seleciona o último arquivo de backup com extensão .maz criado
ArqBkp=\$(ls -t "\$DirArqBkp"/*.maz | head -n 1)

# Verifica se encontrou algum arquivo com extensão .maz
if [ -z "\$ArqBkp" ]; then
    echo "Nenhum arquivo de backup com extensão .maz encontrado em: \$DirArqBkp"
    log_message "Nenhum arquivo de backup com extensão .maz encontrado em: \$DirArqBkp"
    exit 1
fi

# Nome do arquivo sem o caminho completo
ArqBkp=\$(basename "\$ArqBkp")

# Compacta o arquivo de backup
if [ "\$Compactacao" = "zip" ]; then
    zip -j "\$DirArqBkp/\${ArqBkp}_\${DataHora}.zip" "\$DirArqBkp/\$ArqBkp"
    ArqBkp="\${ArqBkp}_\${DataHora}.zip"
elif [ "\$Compactacao" = "tar" ]; then
    tar -czvf "\$DirArqBkp/\${ArqBkp}_\${DataHora}.tar.gz" -C "\$DirArqBkp" "\$ArqBkp"
    ArqBkp="\${ArqBkp}_\${DataHora}.tar.gz"
else
    echo "Formato de compactação inválido: \$Compactacao. Use 'zip' ou 'tar'."
    log_message "Formato de compactação inválido: \$Compactacao. Use 'zip' ou 'tar'."
    exit 1
fi

# Verifica se o arquivo compactado foi criado com sucesso
if [ -f "\$DirArqBkp/\$ArqBkp" ]; then
    # Tenta enviar o arquivo compactado para o Telegram até 3 vezes
    for tentativa in {1..3}; do
        curl -s -F document=@"\$DirArqBkp/\$ArqBkp" -F chat_id="\$ChatID" -F caption="\$Notifica" \
            "https://api.telegram.org/bot\$Token/sendDocument" > /dev/null

        if [ \$? -eq 0 ]; then
            log_message "Backup enviado com sucesso para o Telegram"
            break
        else
            echo "Erro ao enviar o backup para o Telegram. Tentativa \$tentativa de 3."
            log_message "Erro ao enviar o backup para o Telegram. Tentativa \$tentativa de 3."
            delay_2s
            if [ \$tentativa -eq 3 ]; then
                echo "Falha ao enviar o backup para o Telegram após 3 tentativas."
                log_message "Falha ao enviar o backup para o Telegram após 3 tentativas."
            fi
        fi
    done

    # Remove o arquivo compactado após as tentativas de envio, independentemente do sucesso
    rm -f "\$DirArqBkp/\$ArqBkp"
else
    echo "Erro ao compactar o arquivo de backup .maz: \$ArqBkp"
    log_message "Erro ao compactar o arquivo de backup .maz: \$ArqBkp"
    exit 1
fi

exit 0
SCRIPT;
                    $scriptContent = preg_replace('/\r/', '', $scriptContent);
					
					// Caminho para o arquivo script.php
                    $caminho_config = '/opt/mk-auth/dados/bkp_telegram/script.sh';

                    // Se o diretório não existe, cria
                    $dir_path = dirname($caminho_config);
                    if (!is_dir($dir_path)) {
                    if (!mkdir($dir_path, 0755, true)) {
                    exit('Erro ao criar o diretório.');
                     }
                    }

                    $file = '/opt/mk-auth/dados/bkp_telegram/script.sh';
                    if (file_put_contents($file, $scriptContent) !== false) {
                        chmod($file, 0755);

                        $logFile = '/opt/mk-auth/dados/bkp_telegram/backup_telegram.log';
                        if (!file_exists($logFile)) {
                            file_put_contents($logFile, '');
                        }
                        chmod($logFile, 0755);

                        echo '<script>showSuccessPopup("Arquivo script.sh gerado com sucesso em /opt/mk-auth/dados/bkp_telegram.");</script>';
                    } else {
                        echo '<script>alert("Erro ao salvar o arquivo script.sh.");</script>';
                    }
                } else {
                    echo '<script>alert("Por favor, preencha todos os campos.");</script>';
                }
            }

            if (isset($_POST['execute_script'])) {
                $output = shell_exec('/opt/mk-auth/dados/bkp_telegram/script.sh 2>&1');
                if ($output !== null) {
                    echo "<pre>$output</pre>";
                } else {
                    echo '<script>alert("Erro ao executar o script.");</script>';
                }
            }

            if (isset($_POST['clear_log'])) {
                $logFile = '/opt/mk-auth/dados/bkp_telegram/backup_telegram.log';
                if (file_put_contents($logFile, '') !== false) {
                    echo '<script>alert("Arquivo de log limpo com sucesso.");</script>';
                } else {
                    echo '<script>alert("Erro ao limpar o arquivo de log.");</script>';
                }
            }

            if (isset($_POST['schedule_script'])) {
                $scheduleTime = isset($_POST['schedule_time']) ? clean_input($_POST['schedule_time']) : '';

                if (!empty($scheduleTime)) {
                    list($hour, $minute) = explode(':', $scheduleTime);

                    $cronJob = "$minute $hour * * * /opt/mk-auth/dados/bkp_telegram/script.sh >/dev/null 2>&1";
                    $output = shell_exec("echo '$cronJob' | crontab -");
                    if ($output === null) {
                        echo '<script>showSuccessPopup("Script agendado com sucesso.");</script>';
                    } else {
                        echo '<script>alert("Erro ao agendar o script.");</script>';
                    }
                } else {
                    echo '<script>alert("Por favor, preencha o horário de agendamento.");</script>';
                }
            }

            if (isset($_POST['delete_schedule'])) {
                $output = shell_exec("crontab -l | grep -v '/opt/mk-auth/dados/bkp_telegram/script.sh' | crontab -");
                if ($output === null) {
                    echo '<script>showSuccessPopup("Agendamento excluído com sucesso.");</script>';
                } else {
                    echo '<script>alert("Erro ao excluir o agendamento.");</script>';
                }
            }
        }
        ?>

<div class="form-container">
    <form id="executionForm" action="" method="post">
        <button type="submit" name="execute_script" onclick="submitExecutionForm()">Executar Script</button>
        <button type="submit" name="clear_log">Limpar Log</button>
        <button type="button" onclick="toggleScheduledTasks()">Ver Tarefas Agendadas</button>
    </form>
</div>

<div id="scheduledTasksContainer" class="log-container hidden">
    <h3>Tarefas Agendadas</h3>
    <pre id="scheduledTasksContent"></pre>
</div>


<?php
// Caminho do arquivo de log
$logFile = '/opt/mk-auth/dados/bkp_telegram/backup_telegram.log';

// Verifica se o arquivo de log existe
if (file_exists($logFile)) {
    // Lê o conteúdo do arquivo de log
    $logContent = file_get_contents($logFile);
    // Converte as mensagens de sucesso para verde e as mensagens de erro, incluindo data e hora, para vermelho
    $logContent = htmlspecialchars($logContent);
    $logContent = preg_replace('/(\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2} - Backup enviado com sucesso para o Telegram)/', '<span style="color: green; font-weight: bold;">$1</span>', $logContent);
    $logContent = preg_replace('/(\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2} - Erro ao enviar o backup para o Telegram. Tentativa \d de 3.)/', '<span style="color: red; font-weight: bold;">$1</span>', $logContent);
    $logContent = preg_replace('/(\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2} - Falha ao enviar o backup para o Telegram após 3 tentativas.)/', '<span style="color: red; font-weight: bold;">$1</span>', $logContent);
    // Exibe o conteúdo do log em uma tag <pre>, dentro de um <div> com estilo para rolagem e centralizado
    echo '<div class="log-container">';
    echo '<pre>' . $logContent . '</pre>';
    echo '</div>';
} else {
    // Se o arquivo de log não existe, exibe uma mensagem
    echo '<p style="text-align: center;">Arquivo de log não encontrado.</p>';
}
?>

</div>

<?php include('../../baixo.php'); ?>
<script src="../../menu.js.php"></script>
<?php include('../../rodape.php'); ?>
</body>

</html>
