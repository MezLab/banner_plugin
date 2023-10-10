<?php
// Creazione Tabelle dell'applicativo
// Risorse - Gestione Logisticamente
function risorse_plugin_create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'zeus_risorse'; // Aggiunge il prefisso del database di WordPress

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        ID bigint(20) NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        path_url varchar(255) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function risorse_plugin_drop_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zeus_risorse';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}


// Creazione Tabelle dell'applicativo
// Banner - Gestione Logisticamente
function banner_activation() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Nome delle tabelle
    $campaign_table = $wpdb->prefix . 'zeus_campaign';
    $banner_table = $wpdb->prefix . 'zeus_banner';
    $file_table = $wpdb->prefix . 'zeus_banner_file';
    $misure_table = $wpdb->prefix . 'zeus_banner_device';
    $tipologia_table = $wpdb->prefix . 'zeus_banner_type';
    $assoc_banner_table = $wpdb->prefix . 'zeus_banner_organization';
    $assoc_campaign_table = $wpdb->prefix . 'zeus_campaign_organization';
    $assoc_section_banner = $wpdb->prefix . 'zeus_section_banner';

    // SQL per creare le tabelle
    $sql_campaign = "CREATE TABLE $campaign_table (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
        Nome varchar(255) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql_banners = "CREATE TABLE $banner_table (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
        Titolo varchar(255) NOT NULL,
        Link varchar(255) NOT NULL,
        Date_On date NOT NULL,
        Date_Off date NOT NULL,
        Publish int NOT NULL,
        Pay_Click mediumint(9) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql_files = "CREATE TABLE $file_table (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
        NomeFile varchar(255) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql_misure = "CREATE TABLE $misure_table (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
        Device varchar(255) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql_tipologie = "CREATE TABLE $tipologia_table (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
        NomeTipologia varchar(255) NOT NULL,
        PRIMARY KEY (ID)
    ) $charset_collate;";

    $sql_assoc_banner = "CREATE TABLE $assoc_banner_table (
        Banner_ID mediumint(9) NOT NULL,
        Device_ID mediumint(9) NOT NULL,
        Tipologia_ID mediumint(9) NOT NULL,
        File_ID mediumint(9) NOT NULL,
        FOREIGN KEY (Banner_ID) REFERENCES $banner_table(ID) ON DELETE CASCADE,
        FOREIGN KEY (File_ID) REFERENCES $file_table(ID) ON DELETE CASCADE,
        FOREIGN KEY (Device_ID) REFERENCES $misure_table(ID) ON DELETE CASCADE,
        FOREIGN KEY (Tipologia_ID) REFERENCES $tipologia_table(ID) ON DELETE CASCADE
    ) $charset_collate;";

    $sql_assoc_campaign = "CREATE TABLE $assoc_campaign_table (
        Campaign_ID mediumint(9) NOT NULL,
        Banner_ID mediumint(9) NOT NULL,
        FOREIGN KEY (Campaign_ID) REFERENCES $campaign_table(ID) ON DELETE CASCADE,
        FOREIGN KEY (Banner_ID) REFERENCES $banner_table(ID) ON DELETE CASCADE
    ) $charset_collate;";

    $sql_assoc_section = "CREATE TABLE $assoc_section_banner (
        Banner_ID mediumint(9) NOT NULL,
        Sections_ID varchar(255) NOT NULL,
        FOREIGN KEY (Banner_ID) REFERENCES $banner_table(ID) ON DELETE CASCADE
    ) $charset_collate;";

    // Esegui le query di creazione delle tabelle
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_banners );
    dbDelta( $sql_campaign );
    dbDelta( $sql_files );
    dbDelta( $sql_misure );
    dbDelta( $sql_tipologie );
    dbDelta( $sql_assoc_banner );
    dbDelta( $sql_assoc_campaign );
    dbDelta( $sql_assoc_section );


    // Inserisci i valori in my_tipologie
    $tipologie_values = array(
        'Manchettes sx',
        'Manchettes dx',
        'Billboard',
        'Leaderboard',
        'Pushdown',
        'Leaderboard basso',
        'Rectangle',
        'Filmstrip'
    );

    foreach ($tipologie_values as $tipologia) {
        $wpdb->insert($tipologia_table, array('NomeTipologia' => $tipologia));
    }

    // Inserisci i valori in my_misure
    $misure_values = array(
        'mobile',
        'tablet',
        'notebook',
        'desktop'
    );

    foreach ($misure_values as $misura) {
        $wpdb->insert($misure_table, array('Device' => $misura));
    }
}





