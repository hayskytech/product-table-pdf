<?php
/*
Plugin Name: #Product Table Download PDF
Plugin URI: https://www.haysky.com/
Description: Generate a separate page for Product Table. Print and Save as PDF.
Version: 1.0.0
Author: Sufyan
Author URI: https://www.sufyan.in/
License: GPLv2 or later
Text Domain: haysky
*/
// $wpdb->show_errors(); $wpdb->print_error();
error_reporting(E_ERROR | E_PARSE);

add_action( "init",function(){
    // Set labels for brand
    $labels = array(
        "name" => "Brands",
        "singular_name" => "Brand",
        "add_new"    => "Add Brand",
        "add_new_item" => "Add New Brand",
        "all_items" => "All Brands",
    );
    // Set Options for brand
    $args = array(    
        "labels"      => $labels,
        "hierarchical"               => true,
        "public"                     => true,
        "show_ui"                    => true,
        "show_admin_column"          => true,
        "show_in_nav_menus"          => true,
        "show_tagcloud"              => true,
        "show_in_rest"               => true,
    );
    register_taxonomy("brand", array("product"), $args);
    
});

if (isset($_GET["product_table_download_pdf"])) {
	if ($_GET['product_table_download_pdf']=='yes') {
		include 'pdf.php';
	}
}


$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin",function($links){
    $btn1 = '<a href="'.site_url().'/?product_table_download_pdf=yes" target="_blank">Download</a>';
    $btn2 = '<a href="'.site_url().'/wp-admin/options-general.php?page=product_table_header_admin" target="_blank">Header</a>';
    array_unshift($links, $btn1, $btn2);
    return $links;
});

/* -- Creating all shortcodes -- */
add_shortcode('product_table_download_pdf_button',function(){
	return '<button><a href="'.site_url().'/?product_table_download_pdf=yes">Download PDF</a></button>';
});

add_action('admin_menu' , function(){
    add_options_page('Product Table PDF Header','Product PDF Table Header','manage_options', 'product_table_header_admin', 'product_table_header_qtn', 'dashicons-admin-users','2');
});

function product_table_header_qtn(){ include 'product_table_header.php'; }