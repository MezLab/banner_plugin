function copy(tagId) {
    var element = document.querySelector('td[tag_id="' + tagId + '"]');
    var content = 'uploads' + element.textContent; // Aggiungi "uploads" davanti al contenuto
    var dummyInput = document.createElement('input');
    
    // Aggiungi il testo al campo di input temporaneo
    dummyInput.value = content;
    
    document.body.appendChild(dummyInput);
    dummyInput.select();
    document.execCommand('copy');
    document.body.removeChild(dummyInput);

    // Aggiungi un feedback visivo per indicare che il testo è stato copiato
    element.style.backgroundColor = '#5cb85c'; // Cambia il colore dello sfondo
    setTimeout(function() {
        element.style.backgroundColor = '#eee'; // Ripristina il colore dello sfondo dopo qualche secondo
    }, 1000); // Ripristina dopo 1 secondo (puoi regolare il valore)
}