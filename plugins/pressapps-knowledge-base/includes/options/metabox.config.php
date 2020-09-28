<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

$options      = array();

// -----------------------------------------
// Page Metabox Options                    -
// -----------------------------------------
$options[]    = array(
  'id'        => '_pakb_article',
  'title'     => 'Article Options',
  'post_type' => 'knowledgebase',
  'context'   => 'normal',
  'priority'  => 'default',
  'sections'  => array(

    // begin: a section
    array(
      'name'  => 'section_1',
      'fields' => array(

        array(
          'id'    => 'styled_ol',
          'type'  => 'switcher',
          'title' => 'Style Ordered Lists',
		  'default' => false,
        ),

      ), // end: fields
    ), // end: a section


  ),
);



SkeletFramework_Metabox::instance($options);
