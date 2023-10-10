<?php

/**
 * Setting funzionalità varie
 */


// Controllo ruolo Administrator dell'utente
function wpmez_admin(){
    if(!is_admin()){
        require WPMEZ_PLUGIN_PATH . 'views/404.php';
        exit();
    };
}

function wpmez_controller(string $page){
    require WPMEZ_PLUGIN_PATH . 'controller/' . $page . '.php';
}

function wpmez_partials(string $page, $arg = array()){
    require WPMEZ_PLUGIN_PATH . 'views/partials/' . $page . '.php';
}

function wpmez_views(string $path){
    require WPMEZ_PLUGIN_PATH . 'views/' . $path . '.php';
}

function wpmez_requestUri(){
    // Ottieni l'URL corrente con tutti i parametri GET
    $current_url = $_SERVER['REQUEST_URI'];

    // Rimuovi i parametri GET dall'URL
    $parsed_url = parse_url($current_url);
    $new_query = '';

    if (isset($parsed_url['path'])) {
        $new_url = $parsed_url['path'];
        if (isset($parsed_url['query'])) {
            $query_parts = explode('&', $parsed_url['query']);
            $new_query_parts = array();

            foreach ($query_parts as $query_part) {
                list($param, $value) = explode('=', $query_part);
                if ($param !== 'id') {
                    $new_query_parts[] = $query_part;
                }
            }

            if (!empty($new_query_parts)) {
                $new_query = implode('&', $new_query_parts);
                $new_url .= '?' . $new_query;
            }
        }

        return $new_query;
    }

}

function wpmez_only_alfa(string $string){
    $pattern = '/^[a-zA-Z0-9 ]+$/';
    if (preg_match($pattern, $string)) {
        return true;
    } else {
        return false;
    }
}

function wpmez_remove_special_characters(string $string) {
    $clean_string = preg_replace('/[^a-zA-Z0-9 \'’]+/', '', $string);
    return $clean_string;
}

function wpmez_redirect($url){
    echo '<script type="text/javascript">window.location.href = "' . admin_url('admin.php') . $url . '";</script>';
    return;
}

function wpmez_limit_text(string $text, int $n, string $end = "..."){
    $words = '';
    for ($i = 0; $i < $n; $i++) { 
        $words .= $text[$i];
    }

    return $words . $end;
}

// Scrive un messaggio
function wpmez_text(string $text, string $class){
    echo '<div class="' . $class . '">' . $text . '</div>';
}

//Calcola periodo

function wpmez_calcolaGiorni($dataInizio, $dataFine) {
    // Converte le date in oggetti DateTime
    $dataInizio = new DateTime($dataInizio);
    $dataFine = new DateTime($dataFine);

    // Calcola il periodo in giorni
    $differenzaGiorni = $dataInizio->diff($dataFine)->format('%a giorni');

    return array(
        'giorni' => $differenzaGiorni,
    );
}


// Carica le classi
function wpmez_autoloader( $class = [] ) {
    for ($i=0; $i < count($class); $i++) { 
        require WPMEZ_PLUGIN_PATH . "{$class[$i]}.php";
    }
}

// Dump & Die
function wpmez_dd($element){
    echo '<pre>';
    echo var_dump($element);
    echo '</pre>';

    die();
 }

 // Dump
function wpmez_d($element){
    echo '<pre>';
    echo var_dump($element);
    echo '</pre>';
 }


//-----------------------------------------------

/**
 * Setting CSS & JS Admin
 */

/**
 * Enqueue a script in the WordPress admin on edit.php.
 * @param int $hook Hook suffix for the current admin page.
 */

function wpdocs_admin_script() {
    wp_enqueue_style( 'stylesheet', WPMEZ_PLUGIN_URL . 'admin/css/style.css', array(), '1.0.0');
    wp_enqueue_style('bootstrap', 'https://getbootstrap.com/docs/5.3/dist/css/bootstrap.min.css', array());
    // Register the script
    wp_register_script('my-script', WPMEZ_PLUGIN_URL . 'admin/js/script.js', array(), '1.0.2', false);
    // Enqueue the script
    wp_enqueue_script('my-script');
}
add_action( 'admin_enqueue_scripts', 'wpdocs_admin_script' );


function custom_library_scripts() {
    // Registra il file JavaScript
    wp_register_script('pay-per-click', WPMEZ_PLUGIN_URL . 'public/js/payPerClick.js', array('jquery'), '1.0', true);

    // Includi lo script solo se la pagina è in front-end
    if (!is_admin()) {
        // Aggiungi lo script alla coda
        wp_enqueue_script('pay-per-click');
    }
}

// Aggiungi la funzione all'hook 'wp_enqueue_scripts'
add_action('wp_enqueue_scripts', 'custom_library_scripts');

//-----------------------------------------------