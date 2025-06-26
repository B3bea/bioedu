<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../../favicon_io/favicon-16x16.png"> 
    <link rel="stylesheet" href="../../../front-end/css/styleCamera.css">
    <title>Reconhecimento Facial de Alunos</title>
    <style>
        /* Estilos adicionais para a visualização */
        #results-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        #canvas-container {
            position: relative;
            color: white;
        }
        #canvas {
            display: block; /* Alterado de none para block para quando for exibido */
            border: 1px solid black;
        }
        #attendance-list {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        #attendance-list h2 {
            margin-top: 0;
        }
        #attendance-list ul {
            padding-left: 20px;
        }
    </style>
</head>
<body id="camera">

  <div class="voltar">
    <a href="index.php"><img src="../../../front-end/imagens/arrow.png" alt="Seta para voltar à página inicial do site" class="imgSeta"></a>
  </div>
  
  <div class="camera">
    <h1>Abra a câmera e tire uma foto da turma</h1>
    <video id="video" width="640" height="480" autoplay></video>
  </div>
  
  <br>
  <div class="btnFoto">
    <button id="photo-button" onclick="takeAndProcessPhoto()">Tirar Foto e Processar</button>
  </div>
  
  <div id="results-container" style="display:none;">
      <div id="canvas-container">
          <h2>Resultados na Imagem</h2>
          <canvas id="canvas" width="640" height="480"></canvas>
      </div>
      <div id="attendance-list">
          </div>
  </div>

<script>
  const video = document.getElementById('video');
  const canvas = document.getElementById('canvas');
  const context = canvas.getContext('2d');
  const resultsContainer = document.getElementById('results-container');
  const attendanceListDiv = document.getElementById('attendance-list');
  const photoButton = document.getElementById('photo-button');


  // Acessa a câmera do usuário
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
      video.onloadedmetadata = () => video.play();
    })
    .catch(err => {
      console.error("Erro ao acessar a câmera:", err);
      alert("Não foi possível acessar a câmera. Verifique as permissões do navegador.");
    });

  async function takeAndProcessPhoto() {
    // 1. Desenha a imagem do vídeo no canvas "invisível"
    if (video.readyState < 2) {
      alert("Aguarde o carregamento da câmera.");
      return;
    }
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    photoButton.disabled = true;
    photoButton.textContent = 'Processando...';

    // 2. Converte a imagem do canvas para Blob e envia ao backend
    canvas.toBlob(async function(blob) {
      if (!blob) {
        console.error("Erro: blob é nulo.");
        photoButton.disabled = false;
        photoButton.textContent = 'Tirar Foto e Processar';
        return;
      }

      const formData = new FormData();
      formData.append('image', blob, 'photo.jpg');

      try {
        // Envia para a nova rota no backend
        const response = await fetch('http://127.0.0.1:5000/recognize_faces', {
          method: 'POST',
          body: formData
        });

        if (!response.ok) {
          throw new Error(`Erro do servidor: ${response.status}`);
        }

        const result = await response.json();
        
        // 3. Processa e exibe os resultados
        displayResults(result);

      } catch (error) {
        console.error("Erro na requisição:", error);
        alert("Ocorreu um erro ao processar a imagem. Verifique o console para mais detalhes.");
      } finally {
        photoButton.disabled = false;
        photoButton.textContent = 'Tirar Foto e Processar';
      }
    }, 'image/jpeg');
  }

  function displayResults(data) {
    // Limpa o canvas e desenha a imagem original novamente
    context.clearRect(0, 0, canvas.width, canvas.height);
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Itera sobre cada rosto reconhecido para desenhar na tela
    data.recognized_faces.forEach(face => {
      const { name, location } = face;
      const { top, right, bottom, left } = location;

      // Define a cor da caixa e do texto
      const color = name === 'Desconhecido' ? 'red' : 'lime';
      context.strokeStyle = color;
      context.lineWidth = 2;

      // Desenha o retângulo
      context.strokeRect(left, top, right - left, bottom - top);

      // Prepara e desenha o nome
      context.fillStyle = color;
      context.font = '16px Arial';
      const text = name;
      const textWidth = context.measureText(text).width;
      context.fillRect(left, top - 20, textWidth + 10, 20); // Fundo para o texto
      context.fillStyle = 'black';
      context.fillText(text, left + 5, top - 5);
    });
    
    // Mostra o container de resultados
    resultsContainer.style.display = 'flex';
    
    // Constrói e exibe a lista de presença e ausência
    let html = '<h2>Lista de Chamada</h2>';
    html += '<h3>Presentes</h3><ul>';
    data.present_students.forEach(student => {
        html += `<li>${student}</li>`;
    });
    if (data.present_students.length === 0) {
        html += '<li>Nenhum aluno presente reconhecido.</li>';
    }
    html += '</ul>';

    html += '<h3>Ausentes</h3><ul>';
    data.absent_students.forEach(student => {
        html += `<li>${student}</li>`;
    });
    if (data.absent_students.length === 0) {
        html += '<li>Todos os alunos cadastrados estão presentes!</li>';
    }
    html += '</ul>';

    attendanceListDiv.innerHTML = html;
  }

</script>

</body>
</html>