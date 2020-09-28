<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

$options      = array();

// -----------------------------------------
// Taxonomy Options                    -
// -----------------------------------------
$options[]    = array(
	'id'      => '_pakb',
	'taxonomy' => 'knowledgebase_category',
	'fields' => array(

		array(
			'id'         => 'icon',
			'type'       => 'icon',
			'title'      => __( 'Icon', 'pressapps-knowledge-base' ),
			'default'    => 'si-folder4',
		),

 	)
);

SkeletFramework_Taxonomy::instance($options);