<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.10.2018
 * Time: 11:32
 */

if ( is_front_page() ) :
    get_header( 'home' );
elseif ( is_page( 'About' ) ) :
    get_header( 'about' );
else:
    get_header();
endif;