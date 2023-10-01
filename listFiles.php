<?php

function listarPastasArquivos($diretorio, $indentacao = "")
{
    $pastasPermitidas = ['app', 'database', 'resources']; // Lista de pastas permitidas
    try {
        $pastas = new RecursiveDirectoryIterator($diretorio, FilesystemIterator::SKIP_DOTS);
        $arquivos = new RecursiveIteratorIterator($pastas, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($arquivos as $arquivo) {
            $caminhoRelativo = str_replace($diretorio, "", $arquivo->getPathname());
            if (strpos($caminhoRelativo, 'vendor') === false) {
                if ($arquivo->isDir()) {
                    if (in_array($arquivo->getFilename(), $pastasPermitidas) || $indentacao !== "") {
                        file_put_contents('resultado.txt', $indentacao . $arquivo->getFilename() . PHP_EOL, FILE_APPEND);
                        listarPastasArquivos($arquivo->getPathname(), $indentacao . "--");
                    }
                } else {
                    if ($indentacao !== "") {
                        file_put_contents('resultado.txt', $indentacao . "--" . $arquivo->getFilename() . PHP_EOL, FILE_APPEND);
                    }
                }
            }
        }
    } catch (UnexpectedValueException $e) {
        // Ignora arquivos e diretórios inacessíveis e continua
        return;
    }
}

file_put_contents('resultado.txt', '');
listarPastasArquivos(__DIR__);
echo "Listagem concluída. Verifique o arquivo resultado.txt";
