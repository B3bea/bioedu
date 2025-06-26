// --- PARTE 1: LÓGICA PARA LISTAR E EXCLUIR USUÁRIOS ---

const ul = document.querySelector('[data-js="usuario"]');

// Função que busca os dados da API de listagem (index.php)
const getData = async () => {
    try {
        // CORRETO: Aponta para o index.php, que sabe listar todos.
        const response = await fetch(`http://localhost/bioedu/back-end/api/usuario/usuario_api.php`);
        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }
        return response.json();
    } catch (error) {
        console.error('Falha ao buscar usuários:', error);
        ul.innerHTML = '<li><p style="color: red;">Erro ao carregar a lista de usuários.</p></li>';
    }
};

// Função para excluir um registro
const deletePerson = async (id) => {
    // Confirmação antes de excluir, uma boa prática de UX.
    if (!confirm('Tem certeza de que deseja excluir este usuário?')) {
        return;
    }

    try {
        // CORRETO: Aponta para o index.php com o ID para deleção.
        const response = await fetch(`http://localhost/bioedu/back-end/api/usuario/usuario_api.php/${id}`, {
            method: 'DELETE',
        });
        
        const result = await response.json(); // Tenta ler a resposta JSON
        
        if (!response.ok) {
            // Usa a mensagem de erro do servidor se disponível
            throw new Error(result.error || 'Erro desconhecido ao excluir.');
        }
        
        alert(result.message || 'Usuário excluído com sucesso!');
        cargaDados(); // Atualiza a lista na tela.
    } catch (error) {
        console.error('Falha ao excluir usuário:', error);
        alert(`Erro: ${error.message}`);
    }
};

// Função para renderizar os dados na tela
const renderUsers = (users) => {
    ul.innerHTML = ''; 
    if (!users || users.length === 0) {
        ul.innerHTML = '<li>Nenhum usuário cadastrado.</li>';
        return;
    }
    // Melhoria: Usando map e join, que é um pouco mais performático e limpo.
    const userList = users.map(user => `
        <li>
            ${user.nome || user.usuario} (${user.email || 'sem e-mail'}) 
            <button class="delete-btn" onclick="deletePerson(${user.id_usuario})">Excluir</button>
        </li>
    `).join('');
    ul.innerHTML = userList;
};

// Função principal para carregar e exibir os dados
const cargaDados = async () => {
    const users = await getData();
    renderUsers(users);
};


// --- PARTE 2: LÓGICA PARA CADASTRAR UM NOVO USUÁRIO ---

document.getElementById('novoCadastro').addEventListener('submit', async (event) => {
    event.preventDefault();

    const form = event.target;
    // Pega os dados do formulário. Funciona perfeitamente se os 'name's no HTML estiverem corretos.
    const dadosDoFormulario = new FormData(form);

    // Validação simples no lado do cliente antes de enviar
    if (!dadosDoFormulario.get('usuario') || !dadosDoFormulario.get('senha')) {
        alert('Por favor, preencha os campos de usuário e senha.');
        return;
    }

    // URL está correta, apontando para o script de cadastro.
    const url = 'http://localhost/bioedu/back-end/api/usuario/cadastro.php';

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: dadosDoFormulario,
        });

        const data = await response.json();

        if (response.ok && data.success) { // Verifica o status HTTP e a flag de sucesso
            alert(data.message);
            form.reset();      // Limpa o formulário
            cargaDados();      // Atualiza a lista de usuários na tela
            window.location.href = 'tela_login.php'
        } else {
            // Usa a mensagem de erro vinda do servidor
            throw new Error(data.message || 'Ocorreu um erro desconhecido.');
        }

    } catch (error) {
        console.error('Falha na API de cadastro:', error);
        alert(`Erro ao cadastrar: ${error.message}`);
    }
});


// --- PARTE 3: INICIALIZAÇÃO ---

// Garante que o DOM está carregado antes de executar o script. Ótima prática.
document.addEventListener('DOMContentLoaded', cargaDados);
