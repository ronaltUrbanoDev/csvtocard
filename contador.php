<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["numeros"])) {
        $numeros = $_POST["numeros"];
        // Separar os números pela vírgula
        $numeros_array = explode(",", $numeros);
        // Contar o número de elementos no array
        $num_numeros = count($numeros_array);
        echo "Total de números inseridos: $num_numeros";
    } else {
        echo "Nenhum número foi inserido.";
    }
} else {
    echo "Acesso inválido.";
}
?>