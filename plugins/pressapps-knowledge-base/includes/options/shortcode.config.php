<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Global skelet shortcodes variable
 */
  global $skelet_shortcodes;

/**
 * Fullscreen navigation     Shortcode options and settings
 */
$skelet_shortcodes[]     = sk_shortcode_apply_prefix( array(
    'title'      => __( 'KNOWLEDGE BASE', 'pressapps-knowledge-base' ),
    'shortcodes' => array(
        array(
          'name'      => 'articles',
          'title'     => __( 'Articles', 'pressapps-knowledge-base' ),
          'fields'    => array(
              array(
                  'id'       => 'posts_per_page',
                  'type'     => 'number',
                  'title'    => __( 'Number of Articles', 'pressapps-knowledge-base' ),
                  'default'  => '10'
              ),
              array(
                  'id'       => 'orderby',
                  'type'     => 'radio',
                  'title'    => __( 'Order By', 'pressapps-knowledge-base' ),
                  'default'  => 'date',
                  'options'     => array(
                    'date'        => __( 'Date', 'pressapps-knowledge-base' ),
                    'title'       => __( 'Title', 'pressapps-knowledge-base' ),
                    'menu_order'  => __( 'Reorder', 'pressapps-knowledge-base' ),
                    'meta_value_num' => __( 'Likes', 'pressapps-knowledge-base' ),
                  )
              ),
              array(
                  'id'       => 'order',
                  'type'     => 'radio',
                  'title'    => __( 'Order', 'pressapps-knowledge-base' ),
                  'default'  => 'DESC',
                  'options'     => array(
                    'ASC'       => __( 'Ascending', 'pressapps-knowledge-base' ),
                    'DESC'      => __( 'Descending', 'pressapps-knowledge-base' ),
                  )
              ),
              array(
                  'id'          => 'filter',
                  'title'       => 'Filter',
                  'type'        => 'select',
                  'options'     => array(
                    ''          => __( 'None', 'pressapps-knowledge-base' ),
                    'category'      => __( 'Filter by Category', 'pressapps-knowledge-base' ),
                    'tag'           => __( 'Filter by Tag', 'pressapps-knowledge-base' ),
                  )
                
              ),
                array(
                    'id'             => 'category',
                    'type'           => 'select',
                    'title'          => __( 'Category', 'pressapps-knowledge-base' ),
                    'options'        => 'categories',
                    'query_args'     => array(
                        'type'         => 'knowledgebase',
                        'taxonomy'     => 'knowledgebase_category',
                    ),
                    'dependency'     => array( 'filter', '==', 'category' ),
                ),
                array(
                    'id'             => 'tag',
                    'type'           => 'select',
                    'title'          => __( 'Category', 'pressapps-knowledge-base' ),
                    'options'        => 'categories',
                    'query_args'     => array(
                        'type'         => 'knowledgebase',
                        'taxonomy'     => 'knowledgebase_tags',
                    ),
                    'dependency'     => array( 'filter', '==', 'tag' ),
                ),
                array(
                    'id'         => 'title',
                    'type'       => 'switcher',
                    'title'      => __( 'Display Title', 'pressapps-knowledge-base' ),
                    'default'    => false,
                    'dependency'     => array( 'filter', '!=', '' ),
                ),

          ),
        ),
      
    ),
    

));

