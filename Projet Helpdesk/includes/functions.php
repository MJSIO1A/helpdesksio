<?php
function ajouter_log($message) {
    $date = date('Y-m-d H:i:s');
    $log_message = "[$date] $message" . PHP_EOL;
    file_put_contents('logs/log.txt', $log_message, FILE_APPEND);
}
?>
