<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar se um arquivo foi enviado
    if (isset($_FILES["conteudo"]) && $_FILES["conteudo"]["error"] == UPLOAD_ERR_OK) {
        $arquivo_temporario = $_FILES["conteudo"]["tmp_name"];
        $nome_arquivo = $_FILES["conteudo"]["name"]; // Obtém o nome do arquivo original

        // Remover os últimos 4 caracteres do nome do arquivo
        $nome_arquivo = substr($nome_arquivo, 0, -4);

        $conteudo = file_get_contents($arquivo_temporario);

        // Decodificar os caracteres UTF-8 para ISO-8859-1
        $conteudo = utf8_decode($conteudo);

        // Dividir o conteúdo em linhas
        $linhas = explode("\n", $conteudo);

        // Inicializar uma variável para contar o número de números
        $numeros_count = 0;

        // Inicializar uma string para armazenar os contatos VCF
        $vcf = "";

        // Inicializar o contador de contatos
        $contatoCount = 1;

        // Percorrer cada linha
        foreach ($linhas as $linha) {
            // Limpar espaços em branco
            $linha = trim($linha);
            
            // Verificar se a linha não está vazia
            if (!empty($linha)) {
                // Dividir os números por vírgula
                $numeros = explode(",", $linha);

                // Incrementar o contador de números
                $numeros_count += count($numeros);

                // Percorrer cada número
                foreach ($numeros as $numero) {
                    // Construir o nome do contato
                    $nomeContato = "Contato " . $contatoCount;

                    // Adicionar o contato ao VCF
                    $vcf .= "BEGIN:VCARD\r\n";
                    $vcf .= "VERSION:3.0\r\n";
                    $vcf .= "FN:" . $nomeContato . "\r\n";
                    $vcf .= "TEL:" . trim($numero) . "\r\n";
                    $vcf .= "END:VCARD\r\n";

                    // Incrementar o contador de contatos
                    $contatoCount++;
                }
            }
        }

        // Acrescentar o número de números ao nome do arquivo
        $nome_arquivo .= "_" . $numeros_count;

        // Definir cabeçalho HTTP para download do arquivo VCF
        header('Content-Type: text/x-vcard');
        header('Content-Disposition: attachment; filename="' . $nome_arquivo . '.vcf"'); // Usa o nome do arquivo original sem os últimos 4 caracteres

        // Imprimir o conteúdo do arquivo VCF
        echo $vcf;
    } else {
        echo "Erro ao fazer upload do arquivo.";
    }
}
?>
