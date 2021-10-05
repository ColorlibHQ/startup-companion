<?php
function startup_page_metabox( $meta_boxes ) {

	$startup_prefix = '_startup_';
	$meta_boxes[] = array(
		'id'        => 'startup_metaboxes',
		'title'     => esc_html__( 'Project Options', 'startup-companion' ),
		'post_types'=> array( 'project' ),
		'priority'  => 'high',
		'context'  => 'side',
		'autosave'  => 'false',
		'fields'    => array(
			array(
				'name'    => esc_html__( 'Gird Image Size', 'startup-companion' ),
				'id'      => $startup_prefix . 'startup-grid',
				'type'    => 'select',
				'options' => array(
					'0' => 'Select Size',
					'1' => 'Gird Size [681x484]',
					'2' => 'Grid Size [558x484]',
				),
				'inline' => true,
			),			
			array(
				'id'    => $startup_prefix . 'client_name',
				'type'  => 'text',
				'name'  => esc_html__( 'Client Name', 'startup-companion' ),
				'placeholder' => esc_html__( 'Client Name', 'startup-companion' ),
			),			
			array(
				'id'    => $startup_prefix . 'project_category',
				'type'  => 'text',
				'name'  => esc_html__( 'Project Category', 'startup-companion' ),
				'placeholder' => esc_html__( 'Project Category', 'startup-companion' ),
			),			
			array(
				'id'    => $startup_prefix . 'project_date',
				'type'  => 'date',
				'js_options' => [
					'dateFormat' => 'M dd, yy'
				],
				'name'  => esc_html__( 'Project Date', 'startup-companion' ),
				'placeholder' => esc_html__( 'Project Date', 'startup-companion' ),
			),			
			array(
				'id'    => $startup_prefix . 'project_url',
				'type'  => 'text',
				'name'  => esc_html__( 'Project URL', 'startup-companion' ),
				'placeholder' => esc_html__( 'Project URL', 'startup-companion' ),
			),
		),
	);


	return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'startup_page_metabox' );
