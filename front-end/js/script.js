function rolar(conteudo1) {
    var conteudo1 = document.querySelector(conteudo1);
    if (conteudo1) {
        window.scrollTo({
            behavior: 'smooth',
            top: conteudo1.offsetTop
        });
    }
}