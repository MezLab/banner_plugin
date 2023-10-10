<?php

class Logisticamente_Campaign
{
    private $table_banner;
    private $campaign_banner;
    private $table_banner_type;
    private $table_campaign_organization;

    public function __construct() 
    {
        global $wpdb;
        $this->table_banner = $wpdb->prefix . 'zeus_banner';
        $this->campaign_banner = $wpdb->prefix . 'zeus_campaign';
        $this->table_banner_type = $wpdb->prefix . 'zeus_banner_type';
        $this->table_campaign_organization = $wpdb->prefix . 'zeus_campaign_organization';
    }

    public function display_campaign_table() 
    {
        global $wpdb;
        
        $all_campaign = $wpdb->get_results("SELECT * FROM {$this->campaign_banner}");

        foreach ($all_campaign as $key => $value) {

            $query = $wpdb->prepare("
                SELECT {$this->table_banner}.Titolo
                FROM {$this->table_campaign_organization}
                JOIN {$this->table_banner} ON {$this->table_campaign_organization}.Banner_ID = {$this->table_banner}.ID
                WHERE {$this->table_campaign_organization}.Campaign_ID = %d", intval($value->ID));

            $results = $wpdb->get_results($query);

            echo '<tr>';
            echo '<td style="vertical-align: middle;>';
            echo '<div class="btn-group">';
            echo '<a class="btn btn-primary" href="' . admin_url('admin.php') . '?page=log-index-campaign&type=show&id=' . esc_attr($value->ID) . '">Visualizza</a>';
            echo '<a class="btn btn-secondary" href="' . admin_url('admin.php') . '?page=log-index-campaign&type=clear&id=' . esc_attr($value->ID) . '">Pulisci</a>';
            echo '<a class="btn btn-warning" href="' . admin_url('admin.php') . '?page=log-index-campaign&type=update&id=' . esc_attr($value->ID) . '">Modifica</a>';
            echo '<a class="btn btn-danger" href="' . admin_url('admin.php') . '?page=log-index-campaign&type=destroy&id=' . esc_attr($value->ID) . '">Elimina</a>';
            echo '</div>';
            echo '</td>';
            echo '<td style="vertical-align: middle;"><b>' . esc_html($value->Nome) . '</b></td>';
            echo '<td style="vertical-align: middle;">';
            foreach ($results as $key => $value) {
                echo '<p><em>' . esc_html($value->Titolo) . '</em></p>';
            }
            echo '</td>';
            echo '</tr>';
        }
        
    }

    // Aggiunge la campagna al Database
    public function add_risorsa($title) {
        if (empty($title)) {
            throw new Exception('Il nome è obbligatorio.');
        }
    
        global $wpdb;
    
        // Verifica se il titolo esiste già nel database
        $existing_campaign = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->campaign_banner} WHERE Nome = %s", $title));
    
        if ($existing_campaign) {
            throw new Exception('Questo titolo esiste già nel database della campagna.');
        }

        // $this->create_json_campaign($title);
    
        // Inserisci la risorsa se il titolo non esiste già
        $insert_campaign = $wpdb->insert(
            $this->campaign_banner,
            array(
                'Nome' => $title,
            )
        );

        if($insert_campaign){
            wpmez_text('Campagna Inserita', 'veryGood');
        }

    }

    // Crea il file Json per il monitoraggio
    // del banner
    public function create_json_campaign($file){
        
        if(!file_exists($file)){

            $nameFile = str_replace(' ', '_', $file);

            $file_path = WPMEZ_PLUGIN_PATH . 'Media/Chart/' . $nameFile . '.json';

            $data = array(
                'data' => array(
                    array(
                        "year" => date('Y'),
                        "month" => array("Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"),
                        'activity' => array(
                        ),
                    )
                ),
            );
            
            // Converti l'array in una stringa JSON
            $jsonString = json_encode($data);

            if (file_put_contents($file_path, $jsonString) !== false) {
                wpmez_text('Il file è stato creato con successo', 'veryGood');
            }

        }else{
            throw new Exception('Questo file esiste già!');
        }
    }

    // Restituisce il Nome
    // fornendo come parametro ID.
    //
    public function campaign_list()
    {
        global $wpdb;
        $resource = $wpdb->get_results("SELECT * FROM {$this->campaign_banner}");
        return $resource;
    }

    // Restituisce il Nome
    // fornendo come parametro ID.
    //
    public function campaign_name(int $id)
    {
        global $wpdb;
        $resource = $wpdb->get_row($wpdb->prepare("SELECT Nome FROM {$this->campaign_banner} WHERE ID = %d", $id));
        return $resource;
    }

    // Modifica il nome della campagna
    public function update_campaign($id, $title) {

        global $wpdb;
        
        // Modifca Banner
        $wpdb->update(
            $this->campaign_banner,
            array(
                'Nome' => $title,
            ),
            array('ID' => $id)
        );
    }



    // Cancella la campagna dal database
    // Richiamata tramite il pulsante delete
    public function delete_campaign($id) {

        global $wpdb;

        $results_banner_id = $wpdb->get_results($wpdb->prepare("SELECT Banner_ID FROM {$this->table_campaign_organization} WHERE Campaign_ID = %d", $id));
    

        foreach ($results_banner_id as $key => $value) {
            // Elimina associazione 
            // nella tabella zeus_campaign_organization
            $wpdb->delete(
                $this->table_campaign_organization,
                array('Banner_ID' => intval($value->Banner_ID))
            );
        }

        // Elimina il Banner
        // dalla tabella zeus_banner
        $wpdb->delete(
            $this->campaign_banner,
            array('ID' => $id)
        );

    }

    // Ripulisce i banner dai click
    public function clear_campaign($id){

        // Seleziona i Banner_ID
        // dalla tabella zeus_campaign_organization
        global $wpdb;
        $results_banner_id = $wpdb->get_results($wpdb->prepare("SELECT Banner_ID FROM {$this->table_campaign_organization} WHERE Campaign_ID = %d", $id));

        foreach ($results_banner_id as $key => $value) {
            $wpdb->update(
                $this->table_banner,
                array(
                    'Pay_Click' => 0,
                ),
                array('ID' => intval($value->Banner_ID))
            );
        }
    }


    // Visualizza i click effettuato nei banner
    // per la singola campagna
    public function ppc_campaign($id){

        // Seleziona i Banner_ID
        // dalla tabella zeus_campaign_organization
        global $wpdb;
        $results_banner_id = $wpdb->get_results($wpdb->prepare("SELECT Banner_ID FROM {$this->table_campaign_organization} WHERE Campaign_ID = %d", $id));
        $arrayClick = array();

        foreach ($results_banner_id as $key => $value) {

            $dataBanner = $wpdb->prepare("
                SELECT {$this->table_banner}.Titolo, {$this->table_banner}.Pay_Click
                FROM {$this->table_banner}
                WHERE ID = %d", $value->Banner_ID
            );

            array_push($arrayClick, $wpdb->get_results($dataBanner));

        }
        return $arrayClick;
    }

    public function totalClick(){

        // Seleziona i Banner_ID
        // dalla tabella zeus_campaign_organization
        global $wpdb;
        $total = $wpdb->get_row($wpdb->prepare("SELECT SUM(Pay_Click) FROM $this->table_banner"));
        return $total;
    }
    
}