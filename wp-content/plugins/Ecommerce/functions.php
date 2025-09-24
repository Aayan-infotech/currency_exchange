<?php
/*
Plugin Name: Ecommerce plugin
Description: This is my custom ecommerce plugin
Version: 2.0
Author: Mukesh
*/

function wpdocs_register_my_custom_menu_page()
{
    add_menu_page(
        'Orders',
        'Orders',
        'manage_options',
        'orders',
        'orders_page_callback',
        'dashicons-money-alt',
        26
    );

    add_submenu_page(
        'orders',
        __('Settings', 'textdomain'),
        __('Settings', 'textdomain'),
        'manage_options',
        'currency_settings',
        'currency_settings_callback'
    );
}
add_action('admin_menu', 'wpdocs_register_my_custom_menu_page');

function orders_page_callback()
{
    require_once plugin_dir_path(__FILE__) . '/pages/custom_orders_list.php';
}
