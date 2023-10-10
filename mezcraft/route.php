<?php

/**
 * Menu
 * ROUTE: File
 */

//Aggiunta Voce nel menu
add_action( 'admin_menu', 'page_index' );

function page_index() {
    add_menu_page(
        'Zeus',
        'Zeus',
        'manage_options',
        'log-index-zeus',
        'view_page_index',
        WPMEZ_PLUGIN_URL . 'images/fulmine.png',
        100
    );
}

function view_page_index(){
    wpmez_controller('index');
}

// ---------------------------------------
// -----------SEZIONE RISORSE-------------
// ---------------------------------------
// 
//Aggiunta Voce sottomenu Categoria
add_action( 'admin_menu', 'page_risorse_index' );

function page_risorse_index() {
    add_submenu_page(
        'log-index-zeus',
        'Risorse',
        'Risorse',
        'manage_options',
        'log-index-risorse',
        'view_page_risorse',
    );
}

function view_page_risorse(){
    wpmez_controller('Risorse');
}

// ---------------------------------------
// -----------SEZIONE BANNER-------------
// ---------------------------------------
// 
//Aggiunta Voce sottomenu Categoria
add_action( 'admin_menu', 'page_banner_index' );

function page_banner_index() {
    add_submenu_page(
        'log-index-zeus',
        'Banner',
        'Banner',
        'manage_options',
        'log-index-banner',
        'view_page_banner',
    );
}

function view_page_banner(){
    wpmez_controller('Banner');
}

// ---------------------------------------
// -----------SEZIONE CAMPAGNA-------------
// ---------------------------------------
// 
//Aggiunta Voce sottomenu Categoria
add_action( 'admin_menu', 'page_campaign_index' );

function page_campaign_index() {
    add_submenu_page(
        'log-index-zeus',
        'Campaign',
        'Campaign',
        'manage_options',
        'log-index-campaign',
        'view_page_campaign',
    );
}

function view_page_campaign(){
    wpmez_controller('Campaign');
}