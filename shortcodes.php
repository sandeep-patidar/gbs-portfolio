<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function gbs_categorized_portfolio() {
    global $post;
$max_items = get_option('max_items');
$cols = get_option('no_of_cols');
$cat_order = get_option('cat_order');

if($cols=="three"){
  $class_cols = "three-column";
}elseif($cols=="four"){
  $class_cols = "four-column";
}else{
  $class_cols = "three-column";
}
$portfolio_layout = '';
$portfolio_layout.= '<div class="portfolio_section"><ul id="filters" class="clearfix">';
if($cat_order == "asc"){
        $terms = get_terms('gbs_portfolio_categories',array("order"=>"ASC"));
       }else{
        $terms = get_terms('gbs_portfolio_categories',array("order"=>"DESC"));
       }
   
       $data_filter = '';
       $dt = '';
       foreach($terms as $key=>$term){
            $data_filter .= ".".$term->slug.", ";
            $dt=rtrim($data_filter,", ");
       }
        $count = count($terms);
        $portfolio_layout.= '<li><span data-filter="'.$dt.'" class="filter">All</span></li>';
        if ( $count > 0 ){
            foreach ( $terms as $term ) {
                $termname = strtolower($term->name);
                $termname = str_replace(' ', '-', $termname);
                $portfolio_layout.= '<li><span class="filter" data-filter=".'.$termname.'">'.$term->name.'</span></li>';
            }
        }
        $portfolio_layout.= '</ul><div id="portfoliolist">';
        $args = array( 'post_type' => 'gbs_portfolio','posts_per_page' => $max_items );
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post(); 
        $terms = get_the_terms( $post->ID, 'gbs_portfolio_categories' );         
            if ( $terms && ! is_wp_error( $terms ) ) : 
                $links = array();
                foreach ( $terms as $term ) {
                    $links[] = $term->name;
                }
                $tax_links = join( " ", str_replace(' ', '-', $links));          
                $tax = strtolower($tax_links);
            else :  
            $tax = '';                  
            endif; 

        $portfolio_layout.= '<div class="portfolio item '.$class_cols.' '.$tax.'" data-cat="'.$tax.'">';
                 $portfolio_layout.= '<div class="hovereffect"><img class="img-responsive" src="'.get_the_post_thumbnail_url().'" alt=""><div class="zoom_btn"></div></div><div class="p_title"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div></div>';
        endwhile;
     $portfolio_layout.= '</div></div>';
return $portfolio_layout;
}
add_shortcode( 'gbsportfolio', 'gbs_categorized_portfolio' );
?>