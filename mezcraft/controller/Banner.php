<?php

/**
 * {Risorse} Controller
 * Gestione Risorse
 * @Inserimento
 * @Modifica
 * @Distruzione
 */

wpmez_autoloader( ['model/Banner', 'model/Campaign'] );
wpmez_admin();

$_Banner = new Logisticamente_Banner();
//---------------------------
//---------------------------
//---------------------------
// Mostra il Tipo di Pagina
if(isset($_GET['type'])){
    switch ($_GET['type']) {
        case 'add':
            //---------------------------
            //---------------------------
            //---------------------------
            // Aggiungi Risorsa
            if($_SERVER["REQUEST_METHOD"] == "POST"){

                // variabile percorso univoco per il file
                $path_url = ''; 
                
                // Controllo che il nome sia privo di caratteri alfanumerici
                if(wpmez_only_alfa($_POST['banner_name'])){
                    $NomeBanner = wpmez_remove_special_characters($_POST['banner_name']);
                }else{
                    // @Nome => Nome Banner   
                    $NomeBanner = $_POST['banner_name']; 
                }

                // Controllo sulla voce link
                if (filter_var($_POST['banner_link'], FILTER_VALIDATE_URL)) {
                    // @link => Link Banner 
                    $link = $_POST['banner_link'];
                } else {
                    wpmez_text('Il link inserito non è un link', 'error');
                    wpmez_views('Banner/view.store');
                    break;
                }
                
                // 1 - Aggiunge il banner al Database
                $_Banner->add_risorsa($NomeBanner, $link, $_POST['date_on'], $_POST['date_off'], $_POST['publish']);

                foreach ($_FILES as $key => $value) {
                    if(!empty($value['name'])){
                        foreach ($value as $result) {
                            // @key => Nome Device;
                            // @value => File associato al campo Device
                            $path_url = $_Banner->upload_banner($value, intval($_POST['banner_type']), $key);
                        }

                        // 2 - Aggiunge il file al Database e in Uploads
                        $_Banner->add_file($path_url, 'add');

                        $args = [
                            // @Banner
                            'Banner_ID' => intval($_Banner->banner_id($NomeBanner)->ID),
                            // @Device
                            'Device_ID' => intval($_Banner->banner_size_id($key)->ID),
                            // @Tipologia
                            'Tipologia_ID' => intval($_POST['banner_type']),
                            // @File
                            'File_ID' => intval($_Banner->banner_file_id($path_url)->ID)
                        ];

                        // 3 - Associa l'organizzazione del banner
                        $_Banner->add_organization($args);
                    }
                }

                // 4 - Aggiunge le sezioni in cui deve essere visibile
                $sections = [
                    $_POST["section_page"],
                    $_POST["section_ElencoAziende"],
                    $_POST["section_eventi"],
                    $_POST["section_risorse"],
                    $_POST["section_post"],
                    $_POST["section_esperto"],
                    $_POST["section_direttamenteaziende"],
                ];

                $ids_section = array();
                
                foreach ($sections as $a => $b) {
                    foreach ($b as $x => $y) {
                        array_push($ids_section, $y);
                    }
                }
                $json_transform = json_encode(array("ids" => $ids_section));

                $_Banner->add_sections(intval($_Banner->banner_id($NomeBanner)->ID), $json_transform);

            }
            wpmez_views('Banner/view.store');
            break;

        case 'update':
            //---------------------------
            //---------------------------
            //---------------------------
             // Modifca Risorsa
             $id = $_GET['id'];

             if($_SERVER["REQUEST_METHOD"] == "POST"){

                $path_url = '';
                $file = array();

                // Controllo che il nome sia privo di caratteri alfanumerici
                if(wpmez_only_alfa($_POST['banner_name'])){
                    $NomeBanner = wpmez_remove_special_characters($_POST['banner_name']);
                }else{
                    // @Nome => Nome Banner   
                    $NomeBanner = $_POST['banner_name']; 
                }

                // Controllo sulla voce link
                if (filter_var($_POST['banner_link'], FILTER_VALIDATE_URL)) {
                    // @link => Link Banner 
                    $link = $_POST['banner_link'];
                } else {
                    wpmez_text('Il link inserito non è un link', 'error');
                    wpmez_views('Banner/view.store');
                    break;
                }

                // $_Banner->update_json_campaign($titolo, $_POST['campaign']);

                // 1 - Modifica il banner
                $_Banner->update_banner($id, $NomeBanner, $link, $_POST['date_on'], $_POST['date_off'], $_POST['publish']);

                // 2 - Elimina i file esistenti 
                // e aggiunge i nuovi nella tipologia indicata
                foreach ($_FILES as $key => $value) {
                    if(!empty($value['name'])){
                        $file = $_Banner->search_file($key, $id);
                        $_Banner->delete_file($file->NomeFile);

                        foreach ($value as $result) {
                            // @key => Nome Misura;
                            // @value => File associato al campo Misura
                            $path_url = $_Banner->upload_banner($value, intval($_POST['banner_type']), $key);
                        }
                        // Aggiungo il file nuovo
                        $_Banner->add_file($path_url, 'update', $file->ID);
                    }
                }

                // 3 - Modifica il tipo di banner nell'organizzazione del banner
                $_Banner->update_banner_type($id, intval($_POST['banner_type']));

                // 4 - Modifica le sezioni in cui deve essere visibile
                $sections = [
                    $_POST["section_page"],
                    $_POST["section_ElencoAziende"],
                    $_POST["section_eventi"],
                    $_POST["section_risorse"],
                    $_POST["section_post"],
                    $_POST["section_esperto"],
                    $_POST["section_direttamenteaziende"],
                ];

                $ids_section = array();
                
                foreach ($sections as $a => $b) {
                    foreach ($b as $x => $y) {
                        array_push($ids_section, $y);
                    }
                }
                $json_transform = json_encode(array("ids" => $ids_section));

                $_Banner->update_sections(intval($_Banner->banner_id($NomeBanner)->ID), $json_transform);

                // Controllo che la campagna sia stata selezionata
                // potrebbe non essere ancora selezionata
                if(isset($_POST['campaign'])){
                    $_Banner->update_banner_campaign(intval($id), intval($_POST['campaign']));
                }
             }
             wpmez_views('Banner/view.update');
            break;
        case 'destroy':
            //---------------------------
            //---------------------------
            //---------------------------
            // Elimina Risorsa
            $_Banner->delete_banner(intval($_GET['id']));
            wpmez_redirect('?page=log-index-banner');
            break;
        case 'publish':
            //---------------------------
            //---------------------------
            //---------------------------
            // Elimina Risorsa
            $_Banner->publish($_GET['id']);
            wpmez_redirect('?page=log-index-banner');
            break;
        default:
            wpmez_text('Mi dispiace questa opzione non esiste!', 'invalid-feedback');
            break;
    }
}else{
    wpmez_views('Banner/view.index');
}








