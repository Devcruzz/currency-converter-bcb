<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumindo API</title>

    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }
        h1 {
            color: #333;
        }
        form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, button {
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #28a745;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background: #218838;
        }
        p {
            font-size: 18px;
            color: #333;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-top: 15px;
        }
    </style>

</head>
<body>
    
    <div>
        <h1>Conversor</h1>
        <form method="get">
            <label>Quanto R$ você tem na carteira?
                <input type="number" name="qtd" required>
            </label>
            <button type="submit">Calcular</button>
        </form>
    </div>


    <?php 

        // Definindo o fuso horário para São Paulo
        date_default_timezone_set('America/Sao_Paulo');

        // Definindo o intervalo de datas para a requisição da API
        $inicio = date('m-d-Y', strtotime('-7 days')); 
        $fim = date('m-d-Y');

        // URL para consumir a API do Banco Central para pegar a cotação do dólar
        $url = 'https://olinda.bcb.gov.br/olinda/servico/PTAX/versao/v1/odata/CotacaoDolarPeriodo(dataInicial=@dataInicial,dataFinalCotacao=@dataFinalCotacao)?@dataInicial=\'' . $inicio . '\'&@dataFinalCotacao=\''. $fim . '\'&$top=1&$format=json&$select=cotacaoCompra,dataHoraCotacao';

        // Requisição à API e decodificação da resposta JSON
        $dados = json_decode(file_get_contents($url), true);

        // Pegando o valor da cotação de compra do dólar
        $cotacao = $dados["value"][0]["cotacaoCompra"];
        
        // Verificando se o formulário foi enviado e calculando o valor convertido
        if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['qtd'])){
            $real = $_GET['qtd'];
            // Convertendo o valor em reais para dólares com a cotação obtida
            $valorConvertido = $real / $cotacao;
            // Exibindo o valor convertido
            echo "<p>O valor de R$ ". number_format($real, 2, ',', '.') ." equivalem US$ ". number_format($valorConvertido, 2, ',','.') ."</p>";
        } else{
            // Mensagem caso nenhum valor tenha sido informado
            echo "<p>Nenhum valor passado</p>";
        }

    ?>


</body>
</html>

