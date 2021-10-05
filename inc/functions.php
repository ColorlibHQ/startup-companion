<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * @Packge     : Startup Companion
 * @Version    : 1.0
 * @Author     : Colorlib
 * @Author     URI : http://colorlib.com/wp/
 *
 */


/*===========================================================
	Get elementor templates
============================================================*/
function get_elementor_templates() {
	$options = [];
	$args = [
		'post_type' => 'elementor_library',
		'posts_per_page' => -1,
	];

	$page_templates = get_posts($args);

	if (!empty($page_templates) && !is_wp_error($page_templates)) {
		foreach ($page_templates as $post) {
			$options[$post->ID] = $post->post_title;
		}
	}
	return $options;
}

// Section Heading
function startup_section_heading( $title = '', $subtitle = '' ) {
	if( $title || $subtitle ) :
	?>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="section-heading text-center">
						<?php
						// Sub title
						if ( $subtitle ) {
							echo '<p>' . esc_html( $subtitle ) . '</p>';
						}
						// Title
						if ( $title ) {
							echo '<h2>' . esc_html( $title ) . '</h2>';
						}
						?>
					</div>
				</div>
			</div>
		</div>
	<?php
	endif;
}

// Enqueue scripts
add_action( 'wp_enqueue_scripts', 'startup_companion_frontend_scripts', 99 );
function startup_companion_frontend_scripts() {

	wp_enqueue_script( 'startup-companion-script', plugins_url( '../js/loadmore-ajax.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'startup-common-js', plugins_url( '../js/common.js', __FILE__ ), array( 'jquery' ), '1.0', true );

}
// 
add_action( 'wp_ajax_startup_startup_ajax', 'startup_startup_ajax' );

add_action( 'wp_ajax_nopriv_startup_startup_ajax', 'startup_startup_ajax' );
function startup_startup_ajax( ){

	ob_start();

	if( !empty( $_POST['elsettings'] ) ):


		$items = array_slice( $_POST['elsettings'], $_POST['postNumber'] );

	    $i = 0;
	    foreach( $items as $val ):

	    $tagclass = sanitize_title_with_dashes( $val['label'] );
	    $i++;
	?>
	<div class="single_gallery_item <?php echo esc_attr( $tagclass ); ?>">
	    <?php 
	    if( !empty( $val['img']['url'] ) ){
	        echo '<img src="'.esc_url( $val['img']['url'] ).'" />';
	    }
	    ?>
	    <div class="gallery-hover-overlay d-flex align-items-center justify-content-center">
	        <div class="port-hover-text text-center">
	            <?php 
	            if( !empty( $val['title'] ) ){
	                echo startup_heading_tag(
	                    array(
	                        'tag'  => 'h4',
	                        'text' => esc_html( $val['title'] )
	                    )
	                );
	            }

	            if( !empty( $val['sub-title-url'] ) &&  !empty( $val['sub-title'] ) ){
	                echo '<a href="'.esc_url( $val['sub-title-url'] ).'">'.esc_html( $val['sub-title'] ).'</a>';
	            }else{
	                echo '<p>'.esc_html( $val['sub-title'] ).'</p>';
	            }
	            ?>
	            
	        </div>
	    </div>
	</div>

	<?php 

	if( !empty( $_POST['postIncrNumber'] ) ){

	    if( $i == $_POST['postIncrNumber'] ){
	        break;
	    }
	}
	    endforeach;
	endif;
	echo ob_get_clean();
	die();
}

	// Update the post/page by your arguments
	function startup_update_the_followed_post_page_status( $title = 'Hello world!', $type = 'post', $status = 'draft', $message = false ){

		// Get the post/page by title
		$target_post_id = get_page_by_title( $title, OBJECT, $type);

		// Post/page arguments
		$target_post = array(
			'ID'    => $target_post_id->ID,
			'post_status'   => $type,
		);

		if ( $message == true ) {
			// Update the post/page
			$update_status = wp_update_post( $target_post, true );
		} else {
			// Update the post/page
			$update_status = wp_update_post( $target_post, false );
		}

		return $update_status;
	}


	
// Project - Custom Post Type
function project_custom_posts() {	
	$labels = array(
		'name'               => _x( 'Project', 'post type general name', 'startup-companion' ),
		'singular_name'      => _x( 'Project', 'post type singular name', 'startup-companion' ),
		'menu_name'          => _x( 'Projects', 'admin menu', 'startup-companion' ),
		'name_admin_bar'     => _x( 'Projects', 'add new on admin bar', 'startup-companion' ),
		'add_new'            => _x( 'Add New', 'startup', 'startup-companion' ),
		'add_new_item'       => __( 'Add New Project', 'startup-companion' ),
		'new_item'           => __( 'New Project', 'startup-companion' ),
		'edit_item'          => __( 'Edit Project', 'startup-companion' ),
		'view_item'          => __( 'View Project', 'startup-companion' ),
		'all_items'          => __( 'All Projects', 'startup-companion' ),
		'search_items'       => __( 'Search Project', 'startup-companion' ),
		'parent_item_colon'  => __( 'Parent Project:', 'startup-companion' ),
		'not_found'          => __( 'No Project found.', 'startup-companion' ),
		'not_found_in_trash' => __( 'No Project found in Trash.', 'startup-companion' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'startup-companion' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		// 'menu_icon' 		 => 'dashicons-store',
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'project' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'thumbnail' )
	);

	register_post_type( 'project', $args );

}
add_action( 'init', 'project_custom_posts' );

/*=========================================================
    Project Section
========================================================*/
function startup_get_projects( $project_order = 'DESC', $item_per_page = 9 ){ 
	$projects = new WP_Query( array(
		'post_type' => 'project',
		'posts_per_page' => $item_per_page,
		'order'		=> $project_order
	) );

	function startup_get_single_project( $project_img, $project_category, $dynamic_class ){
		?>
		<div class="single_gallery<?=esc_attr($dynamic_class)?>">
			<?php
				if( $project_img ) {
					?>
					<div class="thumb">
						<?php echo $project_img?>
					</div>
					<?php
				}
			?>
			<div class="gallery_hover">
				<div class="hover_inner">
					<?php 
						if ($project_category!='') {
							echo '<span>'.esc_html($project_category).'</span>';
						}
					?>
					<a href="<?php the_permalink()?>"><h3><?php the_title()?></h3></a>
				</div>
			</div>
		</div>
		<?php
	}

	if( $projects->have_posts() ) {
		while ( $projects->have_posts() ) {
			$projects->the_post();			
			$startup_grid = startup_meta( 'startup-grid' ) == 1 ? 'startup_project_img_681x484' : startup_meta( 'startup-grid' ) == 2 ? 'startup_project_img_558x484' : '';
			$dynamic_class = startup_meta( 'startup-grid' ) == 1 ? '' : startup_meta( 'startup-grid' ) == 2 ? ' small_width' : '';
			$project_img = get_the_post_thumbnail( get_the_ID(), $startup_grid, '', array( 'alt' => get_the_title() ) );
			$project_category = !empty( startup_meta( 'project_category' ) ) ? startup_meta( 'project_category' ) : '';
			startup_get_single_project( $project_img, $project_category, $dynamic_class );
		}
	}
}

/*=========================================================
    Blog Section
========================================================*/
function startup_get_recent_blog_posts( $item_per_page = 4, $project_order = 'DESC' ){ 
	$blogs = new WP_Query( array(
		'post_type' => 'post',
		'posts_per_page' => $item_per_page,
		'order'		=> $project_order
	) );

	function startup_get_single_blog_post( $blog_img ){
		?>
		<div class="single_blog">
			<?php
				if( $blog_img ) {
					?>
					<div class="thumb">
						<a href="<?php the_permalink()?>">
							<?php echo $blog_img?>
						</a>
					</div>
					<?php
				}
			?>
			<div class="blog_content">
				<a href="#">
					<span class="date"><?php the_date('M d, Y')?></span>
				</a>
				<a href="<?php the_permalink()?>">
					<h3><?php the_title()?></h3>
				</a>
				<div class="owner_info">
					<div class="author_thumb">
						<!-- <img src="img/creative_blog/author2.png" alt=""> -->
						<?php echo $blog_img?>
					</div>
					<div class="info">
						<a href="#"><h4>Mesica Chouhan</h4></a>
						<p>Business Owner</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	if( $blogs->have_posts() ) {
		while ( $blogs->have_posts() ) {
			$blogs->the_post();			
			$blog_img = get_the_post_thumbnail( get_the_ID(), 'startup_home_blog_558x380', '', array( 'alt' => get_the_title() ) );
			startup_get_single_blog_post( $blog_img );
		}
	}
}
