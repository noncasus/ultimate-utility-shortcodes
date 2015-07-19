<?php
/*
Plugin Name: Ultimate Utility Shortcodes
Plugin URI: http://www.datamaw.com
Description: The most popular tasks in a shortcode bundle
Version: 1.0
Author: Mario Silva
Author URI: http://www.datamaw.com
*/

include('includes/dependencies.php');
include('includes/portfolio_metabox.php');

function uus_owlslider( $shortcode, $loop, $id, $numcols, $img_attr, $text, $arrows ){
	$output = "<div id='$id' class='owl-carousel'>";
    while ( $loop->have_posts() ) : $loop->the_post();
    	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $loop->post->ID ));
    	$output .= "
    				<div class='post'>
    					<a id='id-" . $loop->post->ID . "' href='" . get_permalink($loop->post->ID) . "' title='" . get_the_title($loop->post->ID) ."'>" .
    						get_the_post_thumbnail( $loop->post->ID, 'large', $img_attr )
    					."</a>";
    					if ($text){
    						$output .= 
    						"<h3>". get_the_title($loop->post->ID) ."</h3>
	    					<p>". get_the_content($loop->post->ID) ."</p>";
    					}
    	$output .= "
    				</div>";
    endwhile;
    wp_reset_query();

    $output .= "</div>";

    $output .= "<script>
					jQuery(document).ready(function() {
						var owl = jQuery('#$id');

						owl.owlCarousel({

							autoPlay: 3000, //Set AutoPlay to 3 seconds

							items : $numcols,
							itemsDesktop : [1199,3],
							itemsDesktopSmall : [979,3],
							navigation: true,
							navigationText: [
						    	\"<span class='icon-chevron-left icon-white'><</span>\",
						    	\"<span class='icon-chevron-right icon-white'>></span>\"
						    ]

						});";
				if ($arrows) {
					$output .= "
						$('.next').click(function(){
							owl.trigger('owl-next');
						})
						$('.prev').click(function(){
							owl.trigger('owl-prev');
						})";

				}
	$output .=		"});
				</script>";
	return $output;
}

function uus_gridtype( $grid_info ){

	//extract all from grid_info
	extract($grid_info);

	$imgurl = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), $img_size);

	if( $lazyload ){
		$src = "data-src='" . $imgurl[0] . " ' ";
	}else{
		$src = "src='" . $imgurl[0] . " ' ";
	}

	if( ($i-1) % $numcols == 0  ){
		$output .= "<div class='row'>";
	}

	switch ($gridtype) {
		case 'default':
			$output .= "
					<li class='col-sm-$maxcols'>
						<div class='uus-default'>
	    					<a id='uus-post-" . $post->ID . "' href='" . get_permalink($post->ID) . "' title='" . get_the_title($post->ID) ."'>
	    						<img " . $src . "class='" . $img_attr['class'] . "' alt='" . $post->post_name . "' width='" . $imgurl[1] . "' height='". $imgurl[2] ."' /> 
	    					</a>
	    				</div>
	    				<div class='uus-default-text-wrapper'>
	    					<h3>". get_the_title($post->ID) ."</h3>";
	    					if ($showexcerpt) {
	    						$output .= "<p>". $excerpt ."</p>";
	    					}
	    	$output .= "
	    				</div>
					</li>
				  ";
			break;
		case 'ladder':
			$output .= "
    				<li class='clearfix'>";
    				if($i % 2 != 0){
    					$output .= "
    					<div class='uus-ladder-img-wrapper col-sm-$maxcols'>
        					<a id='uus-post-" . $post->ID . "' href='" . get_permalink($post->ID) . "' title='" . get_the_title($post->ID) ."'>
        						<img " . $src . "class='" . $img_attr['class'] . "' alt='" . $post->post_name . "' width='" . $imgurl[1] . "' height='". $imgurl[2] ."' /> 
        					</a>
        				</div>
        				<div class='uus-ladder-text-wrapper col-sm-$maxcols'>
        					<h3 class='uus-post-title'>". get_the_title($post->ID) ."</h3>";
        					if ($showexcerpt) {
        						$output .= "<p class='uus-post-excerpt'>". $excerpt ."</p>";
        					}
        	$output .= "
        				</div>";
        			}else{
        				$output .= "
        				<div class='uus-ladder-text-wrapper col-sm-$maxcols'>
        					<h3 class='uus-post-title'>". get_the_title($post->ID) ."</h3>
        					<p class='uus-post-excerpt'>". $excerpt ."</p>
        				</div>
        				<div class='uus-ladder-img-wrapper col-sm-$maxcols'>
        					<a id='uus-post-" . $post->ID . "' href='" . get_permalink($post->ID) . "' title='" . get_the_title($post->ID) ."'>
        						<img " . $src . "class='" . $img_attr['class'] . "' alt='" . $post->post_name . "' width='" . $imgurl[1] . "' height='". $imgurl[2] ."' /> 
        					</a>
        				</div>";
        			}
        	$output .= "
        			</li>";
        	break;
        case 'flat':
        	$output .= "
        				<li class='col-sm-7 col-md-7 col-lg-$maxcols nogutter'>
        					<div class='uus-flat-img-wrapper'>
	        					<a id='uus-post-" . $post->ID . "' href='" . get_permalink($post->ID) . "' title='" . get_the_title($post->ID) ."'>
	        						<img " . $src . "class='" . $img_attr['class'] . "' alt='" . $post->post_name . "' width='" . $imgurl[1] . "' height='". $imgurl[2] ."' /> 
	        					</a>
	        				</div>
	        				<div class='uus-flat-text-wrapper'>
		        				<h3 class='uus-post-title'>". get_the_title($post->ID) ."</h3>";
		        				if ($showexcerpt) {
		        					$output .= "<p class='uus-post-excerpt'>". $excerpt ."</p>";
		        				}
		    $output .= "
		        				<a class='uus-post-button' href='" . get_permalink($post->ID) . "' title='" . get_the_title($post->ID) ."'>$buttontext</a>
	        				</div>
        				</li>
        			  ";
        	break;
        	    
        case 'portfolio':

	        $grid_item_size = mt_rand(1,4);
	        $isotope_stored_meta = get_post_meta( $post->ID );

	        switch ($grid_item_size) {
	        	case 1:
	        		// the grid item in a normal square
	        		// $imgurl_isotope = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'isotope_normal');
	        		if ( isset ( $isotope_stored_meta['meta-normal-image'] ) ) $imgurl_isotope = $isotope_stored_meta['meta-normal-image'];
	        		$grid_class = 'grid-item';
	        		break;        	
	        	case 2:
	        		// the grid item spanning two columns
	        		// $imgurl_isotope = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'isotope_width_2');
	        		if ( isset ( $isotope_stored_meta['meta-horizontal-image'] ) ) $imgurl_isotope = $isotope_stored_meta['meta-horizontal-image'];
	        		$grid_class = 'grid-item grid-item--width2';
	        		break;
	        	case 3:
	        		// the grid item spanning two rows
	        		// $imgurl_isotope = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'isotope_height_2');
	        		if ( isset ( $isotope_stored_meta['meta-vertical-image'] ) ) $imgurl_isotope = $isotope_stored_meta['meta-vertical-image'];
	        		$grid_class = 'grid-item grid-item--height2';
	        		break;
	        	case 4:
	        		// large grid item spanning two columns and two rows
	        		// $imgurl_isotope = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'isotope_double');
	        		if ( isset ( $isotope_stored_meta['meta-large-image'] ) ) $imgurl_isotope = $isotope_stored_meta['meta-large-image'];
	        		$grid_class = 'grid-item grid-item--width2 grid-item--height2';
	        		break;
	        }
	        if( $lazyload){
				$src = "data-src='" . @$imgurl_isotope[0] . " ' ";
			}else{
				$src = "src='" . @$imgurl_isotope[0] . " ' ";
			}

        	$output .= "
								<div class='" . $grid_class . "'>
							    	<div class='uus-portfolio-img-wrapper'>
		        						<img " . $src . "class='" . $img_attr['class'] . "' alt='" . $post->post_name . "' /> 
			        				</div>
			        				<div class='uus-portfolio-border'></div>
			        				<div class='uus-portfolio-text-overlay'></div>
			        				<a id='uus-post-" . $post->ID . "' class='uus-portfolio-link' href='" . get_permalink($post->ID) . "' title='" . get_the_title($post->ID) ."'>
				        				<div class='uus-portfolio-text-wrapper'>
				        					<h3 class='uus-post-title'>". get_the_title($post->ID) ."</h3>
				        					<div class='uus-portfolio-overlay'>";
				        					if ($showexcerpt) {
				        						$output .= "<p class='uus-post-excerpt'>". $excerpt ."</p>";
				        					}
			$output .= "						        				
						        			</div>
				        				</div>
				        			</a>


								</div>
        			  ";
        	break;
	}
	if(( $i % ($numcols) == 0 ) ){
		$output .= "</div>";
	}
	
	return $output;
}
add_shortcode('posts', 'uus_posts_listing');

function uus_posts_listing($atts, $content){
	$shortcode = 'posts';
	
	//start the shortcode
	$atts = shortcode_atts(
		array(
			'id'			=>	!empty($atts['id']) ? $atts['id'] : '',
			'numcols'		=>	!empty($atts['numcols']) ? $atts['numcols'] : '4',
			'numposts'		=>	!empty($atts['numposts']) ? $atts['numposts'] : '4',
			'cptname'		=>	!empty($atts['cptname']) ? $atts['cptname'] : 'post',
			'showexcerpt'	=>	!empty($atts['showexcerpt']) ? $atts['showexcerpt'] : 'true',
			'gridtype'		=>	!empty($atts['gridtype']) ? $atts['gridtype'] : 'default',
			'imgsize'		=>	!empty($atts['imgsize']) ? $atts['imgsize'] : 'large',
			'buttontext'	=>	!empty($atts['buttontext']) ? $atts['buttontext'] : 'View Post',
			'type'			=>	!empty($atts['type']) ? $atts['type'] : '',
			'orderby'		=>	!empty($atts['orderby']) ? $atts['orderby'] : 'date',
			'order'			=>	!empty($atts['order']) ? $atts['order'] : 'DESC',
			'owlslider'		=>	!empty($atts['owlslider']) ? $atts['owlslider'] : false,
			'text'			=>	!empty($atts['text']) ? $atts['text'] : false,
			'arrows'		=>	!empty($atts['arrows']) ? $atts['arrows'] : false,
			'lazyload'		=>	!empty($atts['lazyload']) ? $atts['lazyload'] : false
			), $atts
		);

	//extract all from attributes array
	extract($atts);

	//convert string booleans from owlslider and lazyload to real booleans
	$owlslider = filter_var($owlslider, FILTER_VALIDATE_BOOLEAN);
	$lazyload = filter_var($lazyload, FILTER_VALIDATE_BOOLEAN); // true
	$text = filter_var($text, FILTER_VALIDATE_BOOLEAN); // false
	$arrows = filter_var($arrows, FILTER_VALIDATE_BOOLEAN); // false

	$dependency['owlslider'] = $owlslider;
	$dependency['lazyload'] = $lazyload;

	if( $gridtype == 'portfolio'){
		$dependency['isotope'] = true;
	}else{
		$dependency['isotope'] = false;
	}

	//extract all from params array
	extract(uus_load_dependencies($dependency));

	//initialize variables
	$meta_key = '';
	$meta_value = '';

	switch ($type) {
		case '':
			$meta_query = array();
			break;
		case 'mostviews':
			$meta_query = array(
                    'key'     => 'post_views_count'
                        );
			break;
		case 'featured':
			$meta_query = array(
                    'key'     => '_featured',
                    'value'   => 'yes'
                        );
			break;
	}
	$args = array(
            'post_type'			=> $cptname,
            'post_status'  		=> 'publish',
            'posts_per_page'	=> $numposts,
            'meta_query'		=> $meta_query,
            'orderby'			=> $orderby,
            'order'				=> $order
            );
	
    $img_attr = array(
        'class' => "img-responsive",
    );

    $loop = new WP_Query( $args );
    global $post;

    $grid_info = array(
    	'showexcerpt'	=>	filter_var($showexcerpt, FILTER_VALIDATE_BOOLEAN),
    	'gridtype'		=>	$gridtype,
    	'numcols'		=>	$numcols,
    	'img_attr'		=>	$img_attr,
    	'img_size'		=>	$imgsize,
    	'buttontext'	=>	$buttontext,
    	'i'				=>	1,
    	'lazyload'		=>	$lazyload
    );

    
    if( $owlslider ){
    	$id = 'uus-owl-slider-id';
    	$output = uus_owlslider( $shortcode, $loop, $id, $numcols, $img_attr, $text, $arrows );
    }elseif( $gridtype == 'portfolio' ){
    	$grid_info['output'] = "<div class='grid'>";
    	
    	while ($loop->have_posts() ) : $loop->the_post();
			// $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ));
			$excerpt = apply_filters('the_excerpt', get_post_field('post_excerpt', $post->ID));
			$grid_info['excerpt'] = $excerpt;
			$grid_info['post'] = $post;
			$grid_info['output'] = uus_gridtype($grid_info);
			$grid_info['i']++;
		endwhile;
		wp_reset_query();

		$output = $grid_info['output'];
        $output .= "</div>";
    }else{
    	$output = "<ul class='uus-posts-wrapper'>";
    	$grid_info['output'] = $output;
    	$maxcols = $bootstrap_default_cols / $numcols;
    	$grid_info['maxcols'] = $maxcols;
		while ($loop->have_posts() ) : $loop->the_post();
			// $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ));
			if ($showexcerpt) {
				$grid_info['excerpt'] = apply_filters('the_excerpt', get_post_field('post_excerpt', $post->ID));
				
			}
			$grid_info['post'] = $post;
			$grid_info['output'] = uus_gridtype($grid_info);
			$grid_info['i']++;
		endwhile;
        
        wp_reset_query();
        $output = $grid_info['output'];
        $output .= "</ul>";
    }

    if( $gridtype == 'portfolio' ){

		$output .= "<script>
						jQuery(document).ready( function() {
			
							$('.grid').isotope({
								itemSelector: '.grid-item',
								masonry: {
									columnWidth: 470,
									isFitWidth: true
								}
							});
				
						});
					</script>";
    }
    return $output;
}

add_shortcode('products-type', 'uus_products_type_listing');

function uus_products_type_listing($atts, $content){

	$shortcode = 'products-type';


	//start the shortcode
	$atts = shortcode_atts(
		array(
			'id'			=>	!empty($atts['id']) ? $atts['id'] : '',
			'numcols'		=>	!empty($atts['numcols']) ? $atts['numcols'] : '4',
			'numproducts'	=>	!empty($atts['numproducts']) ? $atts['numproducts'] : '4',
			'text'			=>	!empty($atts['text']) ? $atts['text'] : true,
			'type'			=>	!empty($atts['type']) ? $atts['type'] : '',
			'orderby'		=>	!empty($atts['orderby']) ? $atts['orderby'] : 'date',
			'order'			=>	!empty($atts['order']) ? $atts['order'] : 'DESC',
			'owlslider'		=>	!empty($atts['owlslider']) ? $atts['owlslider'] : false,
			'arrows'		=>	!empty($atts['arrows']) ? $atts['arrows'] : false
			), $atts
		);

	//extract all from array
	extract($atts);
	
	//convert string booleans from owlslider and lazyload to real booleans
	$owlslider	= filter_var($owlslider, FILTER_VALIDATE_BOOLEAN);
	$lazyload	= filter_var($lazyload, FILTER_VALIDATE_BOOLEAN); // true
	$text		= filter_var($text, FILTER_VALIDATE_BOOLEAN); // true
	$arrows		= filter_var($arrows, FILTER_VALIDATE_BOOLEAN); // false

	$dependency['owlslider'] = $owlslider;
	$dependency['lazyload'] = $lazyload;

	//extract all from params array
	extract(uus_load_dependencies($dependency));

	//initialize variables
	$meta_key = '';
	$meta_value = '';

	switch ($type) {
		case '':
			$meta_query = array();
			break;
		case 'best':
			$meta_query = array(
                    'key'     => 'total_sales'
                        );
			break;
		case 'featured':
			$meta_query = array(
                    'key'     => '_featured',
                    'value'   => 'yes'
                        );
			break;
		case 'sale':
			$meta_query = array(
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'NUMERIC'
                        );
			break;
	}
	$args = array(
            'post_type'			=> 'product',
            'stock'				=> 1,
            'posts_per_page'	=> $numproducts,
            'meta_query'		=> $meta_query,
            'orderby'			=> $orderby,
            'order'				=> $order
            );

	
    $img_attr = array(
        'class' => "img-responsive",
    );

    $loop = new WP_Query( $args );
    if($owlslider){
    	$id = 'uus-owl-slider-id';
        $output = uus_owlslider( $shortcode, $loop, $id, $numcols, $img_attr, $text, $arrows );
    }else{
    	$id = 'uus-products-id';
    	$output = "<ul id='$id' class='uus-products-wrapper'>";
        $maxcols = $bootstrap_default_cols / $numcols;
        while ( $loop->have_posts() ) : $loop->the_post(); global $product;
        	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->post->ID ));
        	$output .= "
        				<li class='col-sm-$maxcols'>
        					<a id='uus-products" . $product->post->ID . "' href='" . get_permalink($product->post->ID) . "' title='" . get_the_title($product->post->ID) ."'>" .
        						get_the_post_thumbnail( $product->post->ID, 'large', $img_attr )
        					."</a>
        				</li>
        			  ";
        endwhile;
        wp_reset_query();
    
        $output .= "</ul>";
    }
    return $output;
}

add_shortcode('banner', 'uus_banner_element');
function uus_banner_element($atts, $content){
	//start the shortcode
	$atts = shortcode_atts(
		array(
			'id'			=>	!empty($atts['id']) ? $atts['id'] : 'uus-banner',
			'class'			=>	!empty($atts['class']) ? $atts['class'] : 'uus-banner-class',
			'text'			=>	!empty($atts['text']) ? $atts['text'] : 'My banner message',
			'img'			=>	!empty($atts['img']) ? $atts['img'] : '',
			), $atts
		);

	//extract all from array
	extract($atts);

	$output = "
				<div id='$id' class='$class' style='background-image:url($img);'>
					$text" .
					do_shortcode($content) .
				"</div>";
	return $output;
}
add_shortcode('button', 'uus_button_element');
function uus_button_element($atts, $content){
	//start the shortcode
	$atts = shortcode_atts(
		array(
			'id'		=>	!empty($atts['id']) ? $atts['id'] : 'uus-button',
			'class'		=>	!empty($atts['class']) ? $atts['class'] : 'uus-button-class',
			'text'		=>	!empty($atts['text']) ? $atts['text'] : 'Click Me',
			'link'		=>	!empty($atts['link']) ? $atts['link'] : get_home_url(),
			), $atts
		);

	//extract all from array
	extract($atts);
	$output = "
				<a id='$id' class='$class' href='$link'>$text</a>";
	return $output;
}

add_shortcode('categories', 'uus_get_all_categories');
function uus_get_all_categories($atts){
	//start the shortcode
	$atts = shortcode_atts(
		array(
			'id'		=>	!empty($atts['id']) ? $atts['id'] : 'uus-products-category',
			'class'		=>	!empty($atts['class']) ? $atts['class'] : 'uus-products-category-class',
			'numcols'	=>	!empty($atts['numcols']) ? $atts['numcols'] : '4',
			'orderby'	=>	!empty($atts['orderby']) ? $atts['orderby'] : 'count',
			'order'		=>	!empty($atts['order']) ? $atts['order'] : 'DESC',
			), $atts
		);

	//extract all from array
	extract($atts);

	$categories = get_terms( 'product_cat', array(
	 	'orderby'		=>	$orderby,
	 	'order'			=>	$order,
	 	'hide_empty'	=>	0,
	) );

	$output = "<ul id='$id' class='$class'>";

	foreach ($categories as $category) {
		$url = get_term_link($category);
		$category_thumbnail = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);
		$image = wp_get_attachment_url($category_thumbnail);
		$output .= "<li class='col-sm-$numcols'><a href='$url'><img class='img-responsive' src='$image' /></a></li>";
	}

	$output .= "</ul>";
	// var_dump($category_thumbnail);
	return $output;
}

add_shortcode('portfolio', 'uus_get_portfolio');
function uus_get_portfolio($atts){
	//start the shortcode
	$atts = shortcode_atts(
		array(
			'id'		=>	!empty($atts['id']) ? $atts['id'] : 'uus-portfolio',
			'class'		=>	!empty($atts['class']) ? $atts['class'] : 'uus-portfolio-class',
			'numcols'	=>	!empty($atts['numcols']) ? $atts['numcols'] : '4',
			'numposts'	=>	!empty($atts['numposts']) ? $atts['numposts'] : '4',
			'orderby'	=>	!empty($atts['orderby']) ? $atts['orderby'] : 'date',
			'order'		=>	!empty($atts['order']) ? $atts['order'] : 'DESC',
			'per_page'	=>	!empty($atts['per_page']) ? $atts['per_page'] : '4',
			), $atts
		);

	//extract all from array
	extract($atts);

	$args = array(
            'post_type'			=> 'portfolio',
            'posts_per_page'	=> $per_page,
            'orderby'			=> $orderby,
            'order'				=> $order
            );

	$output = "<ul id='$id' class='portfolio'>";
	$img_attr = array(
        'class' => "img-responsive",
    );
    $loop = new WP_Query( $args );
    global $portfolio;
    while ( $loop->have_posts() ) : $loop->the_post();
    	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $portfolio->post->ID ));
    	$output .= "
    				<li class='col-sm-$numcols'>
    					<a id='id-" . $portfolio->post->ID . "' href='" . get_permalink($portfolio->post->ID) . "' title='" . get_the_title($portfolio->post->ID) ."'>" .
    						get_the_post_thumbnail( $portfolio->post->ID, 'large', $img_attr )
    					."</a>
    				</li>
    			  ";
    endwhile;
    wp_reset_query();

    $output .= "</ul>";
    
    return $output;
}



// EXPERIMENTAL

//include('includes/experimental.php');

