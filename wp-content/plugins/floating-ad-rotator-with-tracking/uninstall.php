<?php

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
exit();

delete_option('h5ab_banner_advert_type');
delete_option('h5ab_banner_customhtml');
delete_option('h5ab_banner_array');
delete_option('h5ab_banner_settings');
delete_option('h5ab_banner_session');

?>


