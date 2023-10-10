<?php

class Logisticamente_Risorsa
{
    private $table_name;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'zeus_risorse';
    }

    // Stampa a video tutte le risorse inserite.
    //
    // Controlla che le risorse siano presenti e non
    // siano state eliminate per sbaglio dal server
    // altrimenti restituisce opportuno messaggio!

    public function display_risorse_table() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$this->table_name}");
    
        if (empty($results)) {
            echo "Nessuna risorsa trovata.";
            return;
        }
    
        foreach ($results as $row) {
            echo '<tr>';
            echo '<td>';
            echo '<div class="btn-group">';
            echo '<a class="btn btn-warning" href="' . admin_url('admin.php') . '?page=log-index-risorse&type=update&id=' . esc_attr($row->ID) . '">Modifica</a>';
            echo '<a class="btn btn-danger" href="' . admin_url('admin.php') . '?page=log-index-risorse&type=destroy&id=' . esc_attr($row->ID) . '">Elimina</a>';
            echo '</div>';
            echo '</td>';
            echo '<td style="vertical-align: middle;"><b>' . esc_html($row->title) . '</b></td>';
            $file_path = wp_upload_dir()['basedir'] . $row->path_url;
            if (file_exists($file_path)) {
                echo '<td tag_id="' . esc_attr($row->ID) . '" style="vertical-align: middle;background-color: #eee;color: #000;">' . esc_html($row->path_url) . '</td>';
            } else {
                $error = "Ops! Forse la risorsa è stata eliminata dal server!?";
                echo '<td style="vertical-align: middle;background-color: #ff7575;color: #fff;">' . $error . '</td>';

            }
            echo '<td><button class="btn btn-primary" onclick="copy(' . esc_attr($row->ID) . ');">Copia URL</button></td>';
            echo '</tr>';
        }

    }

    // Restituisce la risorsa richiesta
    // il parametro fornito per la ricerca è @ID
    public function display_risorsa($id) {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE ID = %d", $id));
    
        return $resource;
    }
    
    
    // Aggiunge la risorsa al Database
    // Verifica che il titolo non sia presente più volte
    public function add_risorsa($title, $path_url) {
        if (empty($title) || empty($path_url)) {
            throw new Exception('Titolo e URL risorsa sono obbligatori.');
        }
    
        global $wpdb;
    
        // Verifica se il titolo esiste già nel database
        $existing_resource = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE title = %s", $title));
    
        if ($existing_resource) {
            throw new Exception('Questo titolo esiste già nel database.');
        }
    
        // Inserisci la risorsa se il titolo non esiste già
        $wpdb->insert(
            $this->table_name,
            array(
                'title' => $title,
                'path_url' => $path_url
            )
        );
    }
    

    // Modifica il titolo della risorsa 
    // o entrambi (solo se il file e stato allegato)
    public function update_risorsa($id, $title, $path_url) {
        global $wpdb;
        if($path_url == null){
            $wpdb->update(
                $this->table_name,
                array(
                    'title' => $title,
                ),
                array('ID' => $id)
            );
        }else{
            $wpdb->update(
                $this->table_name,
                array(
                    'title' => $title,
                    'path_url' => $path_url
                ),
                array('ID' => $id)
            );
        }
    }


    // Elimina la risorsa  dal server
    // questa funzione e richiamata solamente se si desidera
    // modificare la risorsa in upload
    // (Errore nell'inserimento da parte dell'utente)
    public function delete_file($id) {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE ID = %d", $id));

        if ($resource) {
            $file_path = wp_upload_dir()['basedir'] . $resource->path_url;
            if (file_exists($file_path)) {
                unlink($file_path); // Rimuovi il file dal percorso
            }
        } else {
            throw new Exception("La risorsa con ID $id non è stata trovata nel database.");
        }
    }


    // Cancella la Risorsa dal server e dal Database
    // Richiamata tramite il pulsante delete
    public function delete_risorsa($id) {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE ID = %d", $id));

        if ($resource) {
            $file_path = wp_upload_dir()['basedir'] . $resource->path_url;
            if (file_exists($file_path)) {
                unlink($file_path); // Rimuovi il file dal percorso
            } else {
                throw new Exception("Il file associato non è stato trovato.");
                exit();
            }

            $wpdb->delete(
                $this->table_name,
                array('ID' => $id)
            );
        } else {
            throw new Exception("La risorsa con ID $id non è stata trovata nel database.");
        }
    }


    // Carica la risorsa nel server
    // nella Directory uploads di wordpress
    // in @risorse_logisticamente
    public function upload_risorsa($file) {
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['basedir'] . '/risorse_logisticamente/';
        $upload_url = $upload_dir['baseurl'] . '/risorse_logisticamente/';
    
        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0755, true);
        }
    
        if (is_dir($upload_path)) {
            $file_name = basename($file['name']);
            $upload_file = $upload_path . $file_name;
            $upload_url .= $file_name;
    
            move_uploaded_file($file['tmp_name'], $upload_file);

            $_url = str_replace($upload_dir['baseurl'], "", $upload_url);
            return $_url;
        } else {
            // La cartella non è stata creata correttamente, gestisci l'errore qui
            return false;
        }
    }

    
}