const validarLogin = async (dadosDoFormulario) => {
    // A URL da API está ótima como uma URL completa, pois também é um caminho absoluto.
    const url = 'http://localhost/bioedu/back-end/api/usuario/login.php'; 

    try {
        const response = await fetch(url, {
            method: 'POST',
            body: dadosDoFormulario 
        });

        const data = await response.json();

        if (response.ok && data.success) {
            alert(data.message); 
            
            if (data.user) {
                localStorage.setItem('userData', JSON.stringify(data.user));
            }
            
            if (data.user_type === 'admin') {
                window.location.href = '/bioedu/back-end/api/admin/index.php'; 
            } else {
                window.location.href = '/bioedu/back-end/api/usuario/index.php'; 
            }

        } else {
            alert('Falha no login: ' + data.message);
        }

    } catch (error) {
        console.error('Falha na comunicação com a API:', error);
        alert('Não foi possível conectar ao servidor. Tente novamente mais tarde.');
    }
};

// O resto do seu código que escuta o formulário continua igual.
const formLogin = document.getElementById('formLogin');
formLogin.addEventListener('submit', async (event) => {
    event.preventDefault();
    const dadosDoFormulario = new FormData(event.target);
    await validarLogin(dadosDoFormulario);
});