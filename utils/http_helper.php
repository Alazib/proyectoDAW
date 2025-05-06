<?php
//Esta función pretende evitar errores al consumir la API de Open Library, reintentando automáticamente varias veces si falla y 
//registrando los fallos en un archivo de log para diagnóstico.
//Evita errores justo en el momento en que file_get_contents() podría fallar. En ese instante, en lugar de que PHP muestre un warning o corte la ejecución, 
//la función suprime el error: reintenta varias veces y registra el fallo si no lo logra



function safe_file_get_contents($url, $maxRetries = 3, $waitSeconds = 1, $logPath = 'logs/api_errors.log')
{
    $attempts = 0;

    while ($attempts < $maxRetries) {
        $context = stream_context_create([
            'http' => ['timeout' => 5]
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response !== false) {
            return $response;
        }

        $attempts++;
        sleep($waitSeconds);
    }

    // Logging del error si todos los intentos fallaron
    log_api_error($url, $logPath);
    return false;
}

function log_api_error($url, $logPath)
{
    $timestamp = date('Y-m-d H:i:s');
    $message = "[$timestamp] Error accediendo a: $url\n";

    // Crea el directorio de logs si no existe
    $dir = dirname($logPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    file_put_contents($logPath, $message, FILE_APPEND);
}
