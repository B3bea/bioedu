<?php
session_start();
require_once '../database/config.php'; // Ajuste o caminho

// Proteção: Apenas usuários logados
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Pega o plano da URL e valida
$tipo_plano = isset($_GET['tipo']) ? $_GET['tipo'] : '';
if ($tipo_plano !== 'mensal' && $tipo_plano !== 'anual') {
    header('Location: planos.php'); // Se o plano for inválido, volta
    exit();
}

// Define os detalhes do plano para exibir na página
$detalhes_plano = [];
if ($tipo_plano === 'mensal') {
    $detalhes_plano['nome'] = 'Plano Mensal';
    $detalhes_plano['preco'] = 'R$ 29,90';
} else {
    $detalhes_plano['nome'] = 'Plano Anual';
    $detalhes_plano['preco'] = 'R$ 299,90';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra - BIOEDU</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Estilos gerais (pode colocar em um CSS externo) */
        body { font-family: 'Poppins', sans-serif; background-color: #0a1041; color: #cdd6f4; margin: 0; padding: 2rem; }
        .checkout-container { display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; max-width: 1000px; margin: auto; }
        .card { background-color: rgb(12, 18, 74); border: 1px solid #2a3f5a; border-radius: 12px; padding: 2rem; }
        
        /* Coluna do Resumo */
        .resumo-pedido { flex: 1; min-width: 300px; }
        .resumo-pedido h2 { color: #ffffff; border-bottom: 1px solid #2a3f5a; padding-bottom: 1rem; }
        .item-resumo { display: flex; justify-content: space-between; margin: 1.5rem 0; font-size: 1.1rem; }
        .item-resumo .total { font-weight: 700; color: #ffffff; }

        /* Coluna do Pagamento */
        .info-pagamento { flex: 2; min-width: 400px; }
        .info-pagamento h2 { color: #ffffff; }
        .metodos-pagamento { display: flex; border-bottom: 1px solid #2a3f5a; margin-bottom: 1.5rem; }
        .metodo-tab { padding: 1rem; cursor: pointer; border-bottom: 3px solid transparent; }
        .metodo-tab.active { color: #00a8ff; border-bottom-color: #00a8ff; }
        .metodo-tab i { margin-right: 0.5rem; }

        .painel-pagamento { display: none; }
        .painel-pagamento.active { display: block; }
        
        /* Formulário de Cartão */
        .form-grupo { margin-bottom: 1rem; }
        .form-grupo label { display: block; margin-bottom: 0.5rem; }
        .form-grupo input { width: 100%; padding: 0.8rem; background-color: #1e2952; border: 1px solid #2a3f5a; border-radius: 6px; color: #ffffff; font-size: 1rem; box-sizing: border-box; }
        .linha-form { display: flex; gap: 1rem; }
        .linha-form .form-grupo { flex: 1; }
        
        /* Painel do PIX */
        .pix-painel { text-align: center; }
        .pix-painel img { max-width: 250px; border: 5px solid white; border-radius: 8px; }
        .pix-painel code { display: block; background-color: #1e2952; padding: 1rem; border-radius: 6px; word-wrap: break-word; margin-top: 1rem; }

        /* Botão Final */
        .btn-finalizar { width: 100%; padding: 1rem; background-color: #28a745; color: #ffffff; font-size: 1.2rem; font-weight: 600; border-radius: 6px; border: none; cursor: pointer; margin-top: 1rem; transition: background-color 0.3s ease; }
        .btn-finalizar:hover { background-color: #218838; }
    </style>
</head>
<body>
    <form action="processar_plano.php" method="POST" class="checkout-container">
        <aside class="card resumo-pedido">
            <h2>Resumo do Pedido</h2>
            <div class="item-resumo">
                <span><?php echo htmlspecialchars($detalhes_plano['nome']); ?></span>
                <span><?php echo htmlspecialchars($detalhes_plano['preco']); ?></span>
            </div>
            <hr style="border-color: #2a3f5a;">
            <div class="item-resumo total">
                <span>Total</span>
                <span><?php echo htmlspecialchars($detalhes_plano['preco']); ?></span>
            </div>
        </aside>

        <main class="card info-pagamento">
            <h2>Pagamento</h2>
            
            <div class="metodos-pagamento">
                <div class="metodo-tab active" data-painel="cartao"><i class="fas fa-credit-card"></i> Cartão</div>
                <div class="metodo-tab" data-painel="pix"><i class="fab fa-pix"></i> Pix</div>
                <div class="metodo-tab" data-painel="boleto"><i class="fas fa-barcode"></i> Boleto</div>
            </div>

            <div id="cartao" class="painel-pagamento active">
                <div class="form-grupo">
                    <label for="num_cartao">Número do Cartão</label>
                    <input type="text" id="num_cartao" placeholder="0000 0000 0000 0000" required>
                </div>
                <div class="form-grupo">
                    <label for="nome_cartao">Nome no Cartão</label>
                    <input type="text" id="nome_cartao" placeholder="Nome como aparece no cartão" required>
                </div>
                <div class="linha-form">
                    <div class="form-grupo">
                        <label for="validade_cartao">Validade</label>
                        <input type="text" id="validade_cartao" placeholder="MM/AA" required>
                    </div>
                    <div class="form-grupo">
                        <label for="cvv_cartao">CVV</label>
                        <input type="text" id="cvv_cartao" placeholder="123" required>
                    </div>
                </div>
            </div>

            <div id="pix" class="painel-pagamento pix-painel">
                <p>Escaneie o QR Code com o app do seu banco:</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=ChavePixSimuladaParaBIOEDU" alt="QR Code Pix">
                <p>Ou use o Pix Copia e Cola:</p>
                <code>00020126330014br.gov.bcb.pix0111chave-simulada-pix-bioedu520400005303986540529.905802BR5913NOME DO ALUNO6009SAO PAULO62070503***6304E7C4</code>
            </div>

            <div id="boleto" class="painel-pagamento">
                <p>O boleto será gerado com seus dados e enviado para o seu e-mail de cadastro após a finalização da compra.</p>
                <p>A confirmação do pagamento pode levar até 3 dias úteis.</p>
            </div>
            
            <input type="hidden" name="tipo_plano" value="<?php echo htmlspecialchars($tipo_plano); ?>">

            <button type="submit" class="btn-finalizar">Finalizar Pagamento</button>
        </main>
    </form>

    <script>
        // Script simples para alternar as abas de pagamento
        const tabs = document.querySelectorAll('.metodo-tab');
        const panels = document.querySelectorAll('.painel-pagamento');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove a classe 'active' de todas as abas e painéis
                tabs.forEach(item => item.classList.remove('active'));
                panels.forEach(panel => panel.classList.remove('active'));

                // Adiciona a classe 'active' na aba e no painel clicado
                tab.classList.add('active');
                const painelId = tab.getAttribute('data-painel');
                document.getElementById(painelId).classList.add('active');
            });
        });
    </script>

</body>
</html>