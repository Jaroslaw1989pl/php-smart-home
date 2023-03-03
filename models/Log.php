<?php

class Log
{
    // Wymaga ulepszenia: tworzenie katalogu na logi, jeśli nie istnieje.
    // Wymaga ulepszenia: zmiana uprawnień katalogu na logi.

    public static function saveLog(string $name, string $content): void
    {
        $file = fopen(ROOT_DIR."/../logs/{$name}_".date("d-m-Y").'.txt', "a+");
        fwrite($file, "[".date("H:i:s")."] {$content}\n");
        fclose($file);
    }
}