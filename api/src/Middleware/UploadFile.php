<?php

namespace GabineteDigital\Middleware;

class UploadFile {
    public function salvarArquivo($pasta, $arquivo) {
        if (!file_exists($pasta)) {
            mkdir($pasta, 0755, true);
        }

        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);

        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoArquivo = $pasta . DIRECTORY_SEPARATOR . $nomeArquivo;

        if (move_uploaded_file($arquivo['tmp_name'], $caminhoArquivo)) {
            return ['status' => 'upload_ok', 'filename' => $nomeArquivo];
        } else {
            return ['status' => 'error'];
        }
    }
}
