<?php

require 'phpexcel/vendor/autoload.php';

// Cria uma nova instância do PHPExcel
$objPHPExcel = new PHPExcel();

// Cria uma folha de trabalho
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();

// Define os títulos
$sheet->setCellValue('A1', 'Name');
$sheet->setCellValue('B1', 'Mobile Phone');

// Define o contador de linha inicial
$linha_excel = 2;

// Lê o conteúdo do arquivo de texto
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $arquivo_temporario = $_FILES["arquivo"]["tmp_name"];
    $nome_arquivo = $_FILES["arquivo"]["name"];

    if ($arquivo_temporario) {
        $conteudo = file_get_contents($arquivo_temporario);
        $linhas = explode(",", $conteudo);

        // Insere as linhas na coluna B do Excel
        foreach ($linhas as $linha) {
            // Insere o conteúdo da linha na célula correspondente na coluna B
            $sheet->setCellValue('B' . $linha_excel, $linha);
            // Define o título na coluna A com base na sequência
            $sheet->setCellValue('A' . $linha_excel, 'Lead ' . ($linha_excel - 1));
            // Incrementa o contador de linha
            $linha_excel++;
        }

        // Define o tipo de cabeçalho para o Excel
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="Grupo.csv"');
        header('Cache-Control: max-age=0');

        // Cria um Writer para o Excel
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

        // Salva a planilha no arquivo de saída
        $objWriter->save('php://output');

        exit;
    } else {
        echo "Nenhum arquivo selecionado.";
    }
}
?>
