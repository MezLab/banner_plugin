<?php

/**
 * {Risorse} Controller
 * Gestione Campagna - Banner
 * @Inserimento
 * @Modifica
 * @Distruzione
 */

wpmez_autoloader( ['model/Campaign'] );
wpmez_admin();

$_Campaign = new Logisticamente_Campaign();
//---------------------------
//---------------------------
//---------------------------
// Mostra il Tipo di Pagina
if(isset($_GET['type'])){
    switch ($_GET['type']) {
        case 'show':
            //---------------------------
            //---------------------------
            //---------------------------
            // Visualizza campagna
            $_Campaign->ppc_campaign($_GET['id']);
            wpmez_views('Campaign/view.single');
            break;
        case 'clear':
            //---------------------------
            //---------------------------
            //---------------------------
            // Pulisci i click della campagna
            $_Campaign->clear_campaign($_GET['id']);
            wpmez_views('Campaign/view.index');
            break;
        case 'add':
            //---------------------------
            //---------------------------
            //---------------------------
            // Aggiungi Risorsa
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $titolo = $_POST['campaign_name'];
                // @titolo => Nome della campagna
                
                $_Campaign->add_risorsa($titolo);

            }
            wpmez_views('Campaign/view.store');
            break;
        case 'update':
            //---------------------------
            //---------------------------
            //---------------------------
            // Modifica Campagna
            $_id = $_GET['id'];

             if($_SERVER["REQUEST_METHOD"] == "POST"){

                $titolo = $_POST['campaign_name'];
                // @titolo => Nome del Banner

                $_Campaign->update_campaign($_id, $titolo);

             }
             wpmez_views('Campaign/view.update');
            break;
        case 'destroy':
            //---------------------------
            //---------------------------
            //---------------------------
            // Elimina Risorsa
            $_Campaign->delete_campaign($_GET['id']);
            wpmez_redirect('?page=log-index-campaign');
            break;
        default:
            wpmez_text('Mi dispiace questa opzione non esiste!', 'invalid-feedback');
            break;
    }
}else{
    wpmez_views('Campaign/view.index');
}