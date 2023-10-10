<?php

/**
 * {Risorse} Controller
 * Gestione Risorse
 * @Inserimento
 * @Modifica
 * @Distruzione
 */

wpmez_autoloader( ['model/Risorsa'] );
wpmez_admin();

$_Risorse = new Logisticamente_Risorsa();
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
                if(!wpmez_only_alfa($_POST['risorsa_name'])){
                    $_POST['risorsa_name'] = wpmez_remove_special_characters($_POST['risorsa_name']);
                }
                $file_load = $_Risorse->upload_risorsa($_FILES['file_input']);
                try {
                    $_Risorse->add_risorsa($_POST['risorsa_name'], $file_load);
                } catch (Exception $e) {
                    echo 'Errore: ' . $e->getMessage();
                }
            }
            wpmez_views('Risorse/view.store');
            break;
        case 'update':
            //---------------------------
            //---------------------------
            //---------------------------
             // Modifca Risorsa
             if($_SERVER["REQUEST_METHOD"] == "POST"){

                if(!wpmez_only_alfa($_POST['risorsa_name'])){
                    $_POST['risorsa_name'] = wpmez_remove_special_characters($_POST['risorsa_name']);
                }

                if(empty($_FILES['file_input']['name'])){
                    $_Risorse->update_risorsa($_GET['id'], $_POST['risorsa_name'], null);
                }else{
                    try {
                        $_Risorse->delete_file($_GET['id']);
                    } catch (Exception $e) {
                        echo 'Errore: ' . $e->getMessage();
                    }
                    
                    $file_load = $_Risorse->upload_risorsa($_FILES['file_input']);
                    
                    try {
                        $_Risorse->update_risorsa($_GET['id'], $_POST['risorsa_name'], $file_load);
                    } catch (Exception $e) {
                        echo 'Errore: ' . $e->getMessage();
                    }
                }
             }
             wpmez_views('Risorse/view.update');
            break;
        case 'destroy':
            //---------------------------
            //---------------------------
            //---------------------------
            // Elimina Risorsa
            if(isset($_GET['id'])){
                $_Risorse->delete_risorsa($_GET['id']);
                wpmez_redirect('?' . wpmez_requestUri());    
            }else{
                wpmez_views('Risorse/view.index');
            }
            break;
        default:
            wpmez_text('Mi dispiace questa opzione non esiste!', 'invalid-feedback');
            break;
    }
}else{
    wpmez_views('Risorse/view.index');
}





