<?php 

function display_banner_shortcode($atts) {
    $a = shortcode_atts(array(
        'tipologia' => '',
    ), $atts);

    $Banner_array_sequence = array();

    // --------------------------
    // --------------------------
    // --------------------------
    // Inizio Query
    global $wpdb;
    $table_banner = $wpdb->prefix . 'zeus_banner';
    $table_banner_file = $wpdb->prefix . 'zeus_banner_file';
    $table_banner_type = $wpdb->prefix . 'zeus_banner_type';
    $table_banner_size = $wpdb->prefix . 'zeus_banner_device';
    $table_banner_organization = $wpdb->prefix . 'zeus_banner_organization';
    
    $ID_type = $wpdb->get_row($wpdb->prepare("SELECT ID FROM $table_banner_type WHERE NomeTipologia = %s", $a['tipologia'])); // ID Tipologia

    // Effettua la query per ottenere il file del banner in base alla tipologia
    $query = $wpdb->prepare("
        SELECT $table_banner.ID, $table_banner_file.NomeFile, $table_banner_size.Device
        FROM $table_banner_organization
        JOIN $table_banner ON $table_banner_organization.Banner_ID = $table_banner.ID
        JOIN $table_banner_file ON $table_banner_organization.File_ID = $table_banner_file.ID
        JOIN $table_banner_size ON $table_banner_organization.Device_ID = $table_banner_size.ID
        WHERE $table_banner_organization.Tipologia_ID = %d ", intval($ID_type->ID));

    $fileDevice = $wpdb->get_results($query); //NomeFile e Device

    $banner = $wpdb->prepare("SELECT ID, Link FROM $table_banner WHERE ID = %d", intval($fileDevice[0]->ID));
    $banner_result = $wpdb->get_results($banner); // ID e Link

    // Fine query
    // --------------------------
    // --------------------------
    // --------------------------

    $devices = [
        'mobile' => '',
        'tablet' => '',
        'notebook' => '',
        'desktop' => ''
    ];

    $single_banner = array(
        'ID' => '',
        'Link' => '',
        'File' => array()
    );

    foreach ($fileDevice as $a => $b) {
        foreach ($devices as $key => $value) {
            if($b->Device == $key)
                $devices[$key] = $b->NomeFile;
        }
    }

    $single_banner['ID'] = $banner_result[0]->ID;
    $single_banner['Link'] = $banner_result[0]->Link;
    $single_banner['File'] = $devices;

    $path_file = wp_upload_dir()['baseurl'];

    // Restituisci l'HTML per visualizzare l'immagine del banner
    if ($fileDevice) {
        $output = '
        <a href="' . esc_url($single_banner['Link']) . '" target="_blank">
            <img onclick="payPerClick(' . $single_banner['ID'] . ');" style="padding: 10px 0px;display:block;margin:0 auto;" 
                srcset="' . esc_url($path_file . $single_banner['File']['mobile']) . ' 460w, 
                ' . esc_url($path_file . $single_banner['File']['tablet']) . ' 980w, 
                ' . esc_url($path_file . $single_banner['File']['notebook']) . ' 1240w, 
                ' . esc_url($path_file . $single_banner['File']['desktop']) . ' 1920w" 
                sizes="100%" 
                src="' . esc_url($path_file . $single_banner['File']['desktop']) . '" 
                alt="Responsive Image">
        </a>';
    }else{
        $output = '';
    }

    return $output;
}
add_shortcode('banner', 'display_banner_shortcode');



function banner_section($atts) {

    $x = shortcode_atts(array(
        'post' => '',
        'title' => '',
        'id' => ''  
    ), $atts);

    global $wpdb;
    $table_section = $wpdb->prefix . 'zeus_section_banner';
    $query = $wpdb->get_row($wpdb->prepare("SELECT Sections_ID FROM {$table_section} WHERE Banner_ID = %d", $x['id']));

    $object = json_decode($query->Sections_ID);
    // wpmez_dd($object->ids);

    //Estensione che visualizza tutti gli elementi del sito
    $query_args = array(
        'post_type' => $x['post'],
        'posts_per_page' => -1,
    );

    $loop = new WP_Query($query_args);

    echo '<div class="col-lg-6 col-md-6 col-sm-12">';
    echo '<label for="" class="px-3 m-2 fs-4 bg-secondary text-white form-label">' . $x['title'] . '</label>';
    echo '<select name="section_' . $x['post'] . '[]" class="form-select" multiple aria-label="multiple select example">';

        while ($loop->have_posts()) {
            $loop->the_post();
                $bool = false;
                foreach ($object->ids as $value) {
                    if(get_the_ID() == $value){
                        echo '<option value="'. get_the_ID(). '" selected>'. get_the_title(). '</option>';
                        $bool = true;
                    }
                }
                if(!$bool){
                    echo '<option value="'. get_the_ID(). '">'. get_the_title(). '</option>';
                }
        }

        echo '</select>';
        echo '</div>';

}

add_shortcode('setting_section', 'banner_section');

