<?php
/*
Plugin Name: GBS Portfolio
Plugin URI: https://wordpress.org/plugins/gbs-portfolio/
Description: GBS Portfolio is an awesome portfolio plugin for you to display your work portfolio, event portfolio, or Business Portfolio in a filterable way. Its easy to use and easily customizable with various settings.
Author: GBS Developer
Author URI: https://globalbizsol.com/
Version: 1.4
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define('GBSP_VERSION', '1.4');
define('GBSP_FILE', basename(__FILE__));
define('GBSP_NAME', str_replace('.php', '', GBSP_FILE));
define('GBSP_URL', plugin_dir_url(__FILE__));

function gbs_portfolio_ajaxurl() { ?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php }
add_action('wp_head','gbs_portfolio_ajaxurl');

function gbs_portfolio_scripts() {
    wp_enqueue_style( 'style', GBSP_URL . 'css/style.css'  );
    wp_enqueue_script('jquery');
    wp_enqueue_script( 'jquery-mixitup', GBSP_URL . 'js/jquery.mixitup.min.js', array(), GBSP_VERSION , true );
    wp_enqueue_script( 'jquery-filter', GBSP_URL . 'js/filter.js',array(), GBSP_VERSION , true );
    
}
add_action( 'wp_enqueue_scripts', 'gbs_portfolio_scripts' );

function gbs_portfolio_custom_css() {
  echo '<style>
.portfolio_option_table {
    width: 500px;
    padding: 10px;
}
.portfolio_option_table tbody tr {
    line-height: 4;
    padding: 10px;
}
.portfolio_option_table table td{
    padding-right: 20px;
}
.portfolio_option_table tbody tr td input[type="text"]{
    width: 250px;
}
.portfolio_option_table tbody tr td select{
    width: 250px;
}
.portfolio_option_table .button{
    background: #f29b09 none repeat scroll 0 0;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    height: 100%;
    margin: 15px 0;
    padding: 4px 12px;
    text-transform: capitalize;
}
.portfolio_option_table h1 {
    color:#F29B09;
    margin-top:30px;
} 
</style>';
}
add_action('admin_head', 'gbs_portfolio_custom_css');
 
function gbs_portfolio_install() {
     // Trigger our function that registers the custom post type
    gbs_portfolio_setup_post_type();
 
    // Clear the permalinks after the post type has been registered
    flush_rewrite_rules();
 
}
register_activation_hook( __FILE__, 'gbs_portfolio_install' );

function gbs_portfolio_deactivate() {
     // Clear the permalinks after the post type has been registered
    flush_rewrite_rules();
 
}
register_deactivation_hook( __FILE__, 'gbs_portfolio_deactivate' );

function gbs_portfolio_setup_post_type() {
    $labels = array(
		'name' => __('GBS Portfolio'),
        'singular_name' => __('GBS Portfolio'),
        'add_new' => __('Add Portfolio Item'),
        'add_new_item' => __('Add New Portfolio Item'),
        'edit_item' => __('Edit Portfolio Item'),
        'new_item' => __('New Portfolio Item'),
        'view_item' => __('View Portfolio Item'),
        'search_items' => __('Search Portfolio Item'),
        'not_found' => __('No Portfolio Items found'),
        'not_found_in_trash' => __('No Portfolio Items found in Trash'),
        'parent_item_colon' => '',
        'menu_name' => __('GBS Portfolio')
	);

	$args = array(
		'labels'             => $labels,
    	'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'gbs_portfolio' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
		'menu_position' => 40,
	    'menu_icon' => 'dashicons-portfolio',
      );
	register_post_type( 'gbs_portfolio', $args );
 	
}
add_action( 'init', 'gbs_portfolio_setup_post_type' );
 
function gbs_portfolio_taxonomies() {
    register_taxonomy(
        'gbs_portfolio_categories',
        'gbs_portfolio',
        array(
            'labels' => array(
                'name' => 'Portfolio Categories',
                'add_new_item' => 'Add New Category',
                'new_item_name' => "New Category"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => true,
            'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'gbs_portfolio_category' ),
        )
    );
}
add_action( 'init', 'gbs_portfolio_taxonomies', 0 );


function gbs_portfolio_menu() {
add_submenu_page( 'edit.php?post_type=gbs_portfolio', 'GBS Portfolio Setting', 'Portfolio Setting','manage_options', 'gbs_portfolio_setting','gbs_setting_func');
}
add_action('admin_menu','gbs_portfolio_menu');


function gbs_setting_func(){
    if(isset($_POST['setting_submit'])) {
        $max_items = sanitize_text_field($_POST['max_items']);
        $total_cols = sanitize_text_field($_POST['no_of_cols']);
        $cat_order = sanitize_text_field($_POST['cat_order']);
       
        update_option("max_items",$max_items);
        update_option("no_of_cols",$total_cols);
        update_option("cat_order",$cat_order);
    }   
        $max_items = get_option('max_items');
        $total_cols = get_option('no_of_cols');
        $cat_order = get_option('cat_order');
       
echo'<div class="portfolio_option_table">
<form method="post" id="portfolio_opt"> 
<h1>GBS Portfolio Settings</h1>
    <table>
    <tbody>
    <tr>
        <td>Max no. of items per page</td>
        <td><input type="text" name="max_items" value="'.$max_items.'"/></td>
    </tr>
    <tr>
        <td>No. of items in a Row</td>
        <td><select name="no_of_cols" id="cols">
            <option value="three" '. (($total_cols=='three')?'selected=selected':"").'>3</option>
            <option value="four" '. (($total_cols=='four')?'selected=selected':"").'>4</option>
            </select>
        </td>
    </tr>
    <tr>
        <td>Category display order</td>
        <td><select name="cat_order" id="cat_order">
            <option value="asc" '. (($cat_order=='asc')?'selected=selected':"").'>Ascending</option>
            <option value="desc" '. (($cat_order=='desc')?'selected=selected':"").'>Descending</option>
            </select>
        </td>
    </tr>
    <tr><td colspan="2"><input class="button" type="submit" value="Save Changes" name="setting_submit"/></td></tr>
    </tbody>
    </table>
</form></div>';
}
$shortcode_file = ABSPATH."wp-content/plugins/gbs-portfolio/shortcodes.php";   
require( $shortcode_file ); // use include if you want.
?>