function payPerClick(id_banner) {
    // Fai una richiesta AJAX per chiamare la funzione PHP addClick
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost:8888/logisticamente/wp-admin/admin-ajax.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                // Puoi gestire la risposta qui, se necessario
                console.log('Risposta dal server:', xhr.responseText);
            } else {
                console.error('Si è verificato un errore:', xhr.status, xhr.statusText);
            }
        }
    };
    xhr.send('action=add_banner_click&id=' + id_banner);
}