<?php
function loadEnv(string $filePath): void
{
    if (!file_exists($filePath)) {
        throw new Exception("El archivo de entorno (.env) no existe: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        // Ignorar líneas de comentarios
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        // Dividir las líneas en clave=valor
        [$name, $value] = explode('=', $line, 2);

        // Cargar como variable de entorno
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}
