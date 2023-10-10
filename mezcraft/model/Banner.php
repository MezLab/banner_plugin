<?php

class Logisticamente_Banner
{
    private $table_banner;
    private $table_banner_file;
    private $table_banner_type;
    private $table_banner_device;
    private $table_banner_organization;
    private $table_campaign_organization;
    private $table_campaign;
    private $table_section_banner;

    public function __construct() 
    {
        global $wpdb;
        $this->table_banner = $wpdb->prefix . 'zeus_banner';
        $this->table_campaign = $wpdb->prefix . 'zeus_campaign';
        $this->table_banner_file = $wpdb->prefix . 'zeus_banner_file';
        $this->table_banner_type = $wpdb->prefix . 'zeus_banner_type';
        $this->table_banner_device = $wpdb->prefix . 'zeus_banner_device';
        $this->table_banner_organization = $wpdb->prefix . 'zeus_banner_organization';
        $this->table_campaign_organization = $wpdb->prefix . 'zeus_campaign_organization';
        $this->table_section_banner = $wpdb->prefix . 'zeus_section_banner';
    }

    // Stampa a video tutte i banner inseriti.
    //
    // Controlla che i banner siano presenti e non
    // siano stati eliminati per sbaglio dal server
    // altrimenti restituisce opportuno messaggio!

    public function display_banner_table() 
    {
        global $wpdb;
        
        $all_banner = $wpdb->get_results("SELECT * FROM {$this->table_banner}");

        foreach ($all_banner as $key => $value) {

            $query = $wpdb->prepare("
                SELECT {$this->table_banner}.ID, {$this->table_banner}.Titolo, {$this->table_banner}.Date_On, {$this->table_banner}.Date_Off, {$this->table_banner}.Publish, {$this->table_banner_file}.NomeFile, {$this->table_banner_type}.NomeTipologia, {$this->table_banner_device}.Device
                FROM {$this->table_banner_organization}
                JOIN {$this->table_banner} ON {$this->table_banner_organization}.Banner_ID = {$this->table_banner}.ID
                JOIN {$this->table_banner_type} ON {$this->table_banner_organization}.Tipologia_ID = {$this->table_banner_type}.ID
                JOIN {$this->table_banner_file} ON {$this->table_banner_organization}.File_ID = {$this->table_banner_file}.ID
                JOIN {$this->table_banner_device} ON {$this->table_banner_organization}.Device_ID = {$this->table_banner_device}.ID
                WHERE {$this->table_banner_organization}.Banner_ID = %d", intval($value->ID));

            $results = $wpdb->get_results($query);

            if (empty($results)) {
                echo "Nessuna risorsa trovata.";
                return;
            }

            $gg = wpmez_calcolaGiorni($results[0]->Date_On, $results[0]->Date_Off);

            echo '<tr>';
            echo '<td rowspan="' . count($results)+5 . '" style="vertical-align: middle;>';
            echo '<div class="btn-group">';
            if($results[0]->Publish == 1){
                echo '<a class="btn btn-secondary" href="' . admin_url('admin.php') . '?page=log-index-banner&type=publish&id=' . esc_attr($results[0]->ID) . '">Disattiva</a>';
            }else{
                echo '<a class="btn btn-secondary" href="' . admin_url('admin.php') . '?page=log-index-banner&type=publish&id=' . esc_attr($results[0]->ID) . '">Attiva</a>';
            }
            echo '<a class="btn btn-warning" href="' . admin_url('admin.php') . '?page=log-index-banner&type=update&id=' . esc_attr($results[0]->ID) . '">Modifica</a>';
            echo '<a class="btn btn-danger" href="' . admin_url('admin.php') . '?page=log-index-banner&type=destroy&id=' . esc_attr($results[0]->ID) . '">Elimina</a>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td rowspan="' . count($results)+4 . '" style="vertical-align: middle;"><b>' . esc_html($results[0]->Titolo) . '</b></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td rowspan="' . count($results)+3 . '" style="vertical-align: middle;"><em>' . esc_html($results[0]->NomeTipologia) . '</em></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td rowspan="' . count($results)+2 . '" style="vertical-align: middle;"><em>' . esc_html($gg["giorni"]) . '</em></td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td rowspan="' . count($results)+1 . '" style="vertical-align: middle;"><em>' . esc_html($results[0]->Publish) . '</em></td>';
            echo '</tr>';

            foreach ($results as $row) {
                echo '<tr>';
                // Controllo sul banner
                $file_path = wp_upload_dir()['basedir'] . $row->NomeFile;
                if (file_exists($file_path)) {
                    echo '<td style="vertical-align: middle;"><img src="' . wp_upload_dir()['baseurl'] . $row->NomeFile .'" alt="" width="200px"></td>';
                } else {
                    $error = "Ops! Forse la risorsa è stata eliminata dal server!?";
                    echo '<td style="vertical-align: middle;background-color: #ff7575;color: #fff;">' . $error . '</td>';

                }
                echo '<td style="vertical-align: middle;"><b><em>' . esc_html($row->Device) . '</em></b></td>';
                echo '</tr>';
            }

        }

        
    }

    // Restituisce il banner richiesto
    // il parametro fornito per la ricerca è @ID
    public function display_banner($id) {
        global $wpdb;
        $query = "
            SELECT {$this->table_banner}.ID, {$this->table_banner}.Titolo, {$this->table_banner}.Link, {$this->table_banner}.Date_On, {$this->table_banner}.Date_Off, {$this->table_banner}.Publish, {$this->table_banner_file}.NomeFile, {$this->table_banner_type}.NomeTipologia, {$this->table_banner_device}.Device
            FROM {$this->table_banner_organization}
            JOIN {$this->table_banner} ON {$this->table_banner_organization}.Banner_ID = {$this->table_banner}.ID
            JOIN {$this->table_banner_type} ON {$this->table_banner_organization}.Tipologia_ID = {$this->table_banner_type}.ID
            JOIN {$this->table_banner_file} ON {$this->table_banner_organization}.File_ID = {$this->table_banner_file}.ID
            JOIN {$this->table_banner_device} ON {$this->table_banner_organization}.Device_ID = {$this->table_banner_device}.ID
            WHERE {$this->table_banner}.ID = %d
        ";
    
        $resource = $wpdb->get_results($wpdb->prepare($query, $id));
        return $resource;
    }

    // Stampa a video tutte le tipologie banner.
    //
    public function display_banner_type()
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$this->table_banner_type}");
        return $results;
    }

    // Stampa a video tutte le misure banner.
    //
    public function display_banner_size()
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$this->table_banner_device}");
        return $results;
    }

    // Restituisce il Nome della Tipologia 
    // fornendo come parametro ID.
    //
    public function banner_type_name(int $id)
    {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT NomeTipologia FROM {$this->table_banner_type} WHERE ID = %d", $id));
        return $resource;
    }

    // Restituisce ID della Misura 
    // fornendo come parametro il Nome.
    //
    public function banner_size_id(string $name)
    {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT ID FROM {$this->table_banner_device} WHERE Device = %s", $name));
        return $resource;
    }

    
    public function banner_file_id(string $name)
    {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT ID FROM {$this->table_banner_file} WHERE NomeFile = %s", $name));
        return $resource;
    }

    // Restituisce ID del Banner 
    // fornendo come parametro il Nome.
    //
    public function banner_id(string $name)
    {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT ID FROM {$this->table_banner} WHERE Titolo = %s", $name));
        return $resource;
    }

    // Attiva o Disattiva il banner
    public function publish(int $id)
    {
        global $wpdb;
        $publi = $wpdb->get_row($wpdb->prepare("SELECT Publish FROM {$this->table_banner} WHERE ID = %d", $id));

        if($publi->Publish == 1){
            $switch = 2;
        }else{
            $switch = 1;
        }
        
        // Modifica la pubblicazione
        $wpdb->update(
            $this->table_banner,
            array(
                'Publish' => $switch,
            ),
            array('ID' => $id)
        );
    }

    // Aggiunge il banner al Database
    // Verifica che il titolo non sia presente più volte
    public function add_risorsa($title, $link, $date_on, $date_off, $publish) {

        global $wpdb;

        // Verifica se il titolo esiste già nel database
        $existing_resource = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_banner} WHERE title = %s", $title));
        if ($existing_resource) {
            throw new Exception('Questo nome esiste già nel database.');
        }else{

            $wpdb->insert(
                $this->table_banner,
                array(
                    'Titolo' => $title,
                    'Link' => $link,
                    'Date_On' => $date_on,
                    'Date_Off' => $date_off,
                    'Publish' => $publish,
                    'Pay_Click' => 0
                )
            );

        }
        
    }

    // Aggiunge il File al Database
    // Verifica che il file non sia presente più volte
    public function add_file($path_file, string $select, $id = null){
        
        global $wpdb;

        switch ($select) {
            case 'add':

                // Verifica se il file non esiste nel database
                $existing_resource = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_banner_file} WHERE NomeFile = %s", $path_file));
                if ($existing_resource) {
                    throw new Exception('Questo file esiste già!');
                }else{
                    // Inserisci la risorsa se il file non esiste già
                    $wpdb->insert(
                        $this->table_banner_file,
                        array(
                            'NomeFile' => $path_file,
                        )
                    );
                }

                break;
            case 'update':
                // Modifica la risorsa
                $wpdb->update(
                    $this->table_banner_file,
                    array(
                        'NomeFile' => $path_file,
                    ),
                    array('ID' => $id)
                );
                break;
            default:
                break;
        }
    }

    // Modifica il Titolo del banner
    // Modifica il Link del banner 
    public function update_banner($id, $title, $link, $date_on, $date_off, $publish) {

        global $wpdb;
        
        // Modifica Banner
        $wpdb->update(
            $this->table_banner,
            array(
                'Titolo' => $title,
                'Link' => $link,
                'Date_On' => $date_on,
                'Date_Off' => $date_off,
                'Publish' => $publish,
            ),
            array('ID' => $id)
        );
    }

    // Modifica la tipologia del banner
    public function update_banner_type($id, $type) {
        global $wpdb;
    
        // Inizia la transazione
        $wpdb->query('START TRANSACTION');
    
        try {
            $query = $wpdb->prepare("SELECT * FROM {$this->table_banner_organization} WHERE Banner_ID = %d", $id);
            $t = $wpdb->get_results($query);
    
            foreach ($t as $item) {
                // Modifica Tipologia_ID   
                $wpdb->update(
                    $this->table_banner_organization,
                    array(
                        'Tipologia_ID' => $type,
                    ),
                    array('Banner_ID' => $item->Banner_ID) // Assuming 'ID' is the correct column name
                );
            }
    
            // Esegui il commit della transazione
            $wpdb->query('COMMIT');
        } catch (Exception $e) {
            // In caso di errore, esegui il rollback della transazione
            $wpdb->query('ROLLBACK');
            // Puoi gestire l'errore qui, ad esempio stampandolo o lanciando un'eccezione personalizzata
        }
    }

    // Modifica l'associazione della campagna
    public function update_banner_campaign($id, $campaign) {
        global $wpdb;

        $query = $wpdb->prepare("SELECT * FROM {$this->table_campaign_organization} WHERE Banner_ID = %d", $id);

        if(empty($wpdb->get_results($query))){

            $wpdb->insert(
                $this->table_campaign_organization,
                    array(
                        'Campaign_ID' => intval($campaign),
                        'Banner_ID' => intval($id),
                    )
                );

        } else {

            $wpdb->update(
                $this->table_campaign_organization,
                array(
                    'Campaign_ID' => intval($campaign),
                ),
                array('Banner_ID' => intval($id) // Assuming 'ID' is the correct column name
                )
            );

        }
        
    }


    public function update_json_campaign($banner, $id_campaign){

        global $wpdb;
        $query = $wpdb->get_row($wpdb->prepare("SELECT Nome FROM {$this->table_campaign} WHERE ID = %d", $id_campaign));

        $new_banner = array(
            'banner' => $banner,
            'click' => array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
        );

        $path = WPMEZ_PLUGIN_URL . 'Media/Chart/';
        $file = str_replace(' ', '_', $query->Nome) . '.json';
        // Legge il contenuto del file JSON
        $jsonFile = file_get_contents($path . $file);

        // Decodifica la stringa JSON in un oggetto PHP
        $decodedData = json_decode($jsonFile);

        foreach ($decodedData->data as $data) {
            if(date('Y') == $data->year){
                array_push($data->activity, $new_banner);
            }
        }

        //Scrivo il nuovo contenuto nel file Json
        $codeData = json_encode($decodedData);
        file_put_contents($file, $codeData);

    }
    

    // Crea l'associazione del banner nel Database
    public function add_organization($array = []) {
        if (empty($array)) {
            throw new Exception('L\'associazione è obbligatoria.');
        }
    
        global $wpdb;    
        $wpdb->insert(
            $this->table_banner_organization,
            $array
        );
    }

    // Aggiunge le sezioni di visualizzazione
    public function add_sections(int $id, string $json) {
        if (empty($json) && empty($id)) {
            throw new Exception('L\'associazione è obbligatoria.');
        }
    
        global $wpdb;    

        $wpdb->insert(
            $this->table_section_banner,
            array(
                'Banner_ID' => $id,
                'Sections_ID' => $json
            )
        );
    }

    // Aggiunge le sezioni di visualizzazione
    public function update_sections(int $id, string $json) {
        if (empty($json) && empty($id)) {
            throw new Exception('L\'associazione è obbligatoria.');
        }
    
        global $wpdb;
        
        $wpdb->update(
            $this->table_section_banner,
            array(
                'Sections_ID' => $json
            ),
            array('Banner_ID' => $id)
        );
    }

    // Cancella il banner dal server e dal database
    // Richiamata tramite il pulsante delete
    public function delete_banner($id) {

        global $wpdb;
        // Inizia la transazione
        $wpdb->query('START TRANSACTION');

        try {
            // Trova tutti i campi associati al Banner_ID
            // nella tabella zeus_banner_organization
            $query = $wpdb->prepare("SELECT * FROM {$this->table_banner_organization} WHERE Banner_ID = %d", $id);
            $fields_banner = $wpdb->get_results($query);


            foreach ($fields_banner as $item) {

                // Elimina il file in questione
                $name_file = $wpdb->get_row($wpdb->prepare("SELECT NomeFile FROM {$this->table_banner_file} WHERE ID = %d", $item->File_ID));
                $this->delete_file($name_file->NomeFile);

                // Elimina la righa dalla tabella
                // zeus_banner_file
                $wpdb->delete(
                    $this->table_banner_file,
                    array('ID' => $item->File_ID)
                );
            }

            foreach ($fields_banner as $item) {

                // Elimina la righa dalla tabella
                // zeus_banner_organization
                $wpdb->delete(
                    $this->table_banner_organization,
                    array('ID' => $item->ID)
                );
            }

            // Esegui il commit della transazione
            $wpdb->query('COMMIT');
            
        } catch (Exception $e) {
            // In caso di errore, esegui il rollback della transazione
            $wpdb->query('ROLLBACK');
            // Puoi gestire l'errore qui, ad esempio stampandolo o lanciando un'eccezione personalizzata
        }

        // Elimina il Banner
        // dalla tabella zeus_banner
        $wpdb->delete(
            $this->table_banner,
            array('ID' => $id)
        );

        $wpdb->delete(
            $this->table_section_banner,
            array('ID' => $id)
        );

    }


    public function search_file(string $key, int $id)
    {
        global $wpdb;
        $search_misura = " SELECT {$this->table_banner_device}.ID FROM {$this->table_banner_device} WHERE {$this->table_banner_device}.Device = %s";
        $resource = $wpdb->get_row($wpdb->prepare($search_misura, $key));

        // wpmez_dd(intval($resource->ID));

        $search_file = "SELECT File_ID FROM {$this->table_banner_organization}
        JOIN {$this->table_banner} ON {$this->table_banner_organization}.Banner_ID = {$this->table_banner}.ID 
        JOIN {$this->table_banner_device} ON {$this->table_banner_organization}.Device_ID = %d
        WHERE {$this->table_banner_device}.ID = %d";

        $result = $wpdb->get_row($wpdb->prepare($search_file, intval($resource->ID), intval($resource->ID)));

        $name_file = "SELECT ID, NomeFile FROM {$this->table_banner_file} WHERE {$this->table_banner_file}.ID = %d";

        $file = $wpdb->get_row($wpdb->prepare($name_file, intval($result->File_ID)));
    
        return $file;

    }


    // Elimina il banner dal server
    // questa funzione e richiamata solamente se si desidera
    // modificare il banner in upload
    // (Errore nell'inserimento da parte dell'utente)
    public function delete_file(string $path_url) {

        $file_path = wp_upload_dir()['basedir'] . $path_url;
        if (file_exists($file_path)) {
            unlink($file_path); // Rimuovi il file dal percorso
        } else {
            throw new Exception("Il banner non è stato trovato nel database.");
        }
    }


    // Carica il banner nel server
    // nella Directory uploads di wordpress
    // in @banner_logisticamente
    public function upload_banner($file, int $ID_type, string $size)
    {
        $type = strtolower($this->banner_type_name($ID_type)->NomeTipologia);

        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['basedir'] . "/banner_logisticamente/{$type}/{$size}/";
        $upload_url = $upload_dir['baseurl'] . "/banner_logisticamente/{$type}/{$size}/";
    
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