<?php
require 'Core/Options.php';
register_deactivation_hook( __FILE__, 'risorse_plugin_drop_table' );