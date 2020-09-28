<?php
/**
 *
 * Search Widget
 *
 */
if( ! class_exists( 'Pressapps_Knowledge_Base_Widget_Search' ) ) {
  class Pressapps_Knowledge_Base_Widget_Search extends WP_Widget {

    function __construct() {

      $widget_ops     = array(
        'classname'   => 'knowledge_base_search',
        'description' => 'Search knowledge base articles.'
      );

      parent::__construct( 'knowledge_base_search', 'Knowledge Base Search', $widget_ops );

    }

    function widget( $args, $instance ) {

      extract( $args );

      echo $before_widget;

      if ( ! empty( $instance['title'] ) ) {
        echo $before_title . $instance['title'] . $after_title;
      }

      global $pakb, $pakb_helper;
      $search_ptxt = trim( strip_tags( $pakb->get( 'searchbox_placeholder' ) ) );

			if ( $pakb->get( 'kb_page' ) ) {
				$page_link = get_permalink( $pakb->get( 'kb_page' ) );
			} else {
				echo '<p>' . __( 'Knowledge Base page not set under PressApps > Knowledge Base', 'pressapps-knowledge-base' ) . '</p>';
			}

            $pakb_helper->the_search();

      echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {

      $instance            = $old_instance;
      $instance['title']   = $new_instance['title'];

      return $instance;

    }

    function form( $instance ) {

      // Set defaults
      $instance   = wp_parse_args( $instance, array(
        'title'   => 'Search Articles',
      ));

      // Title
      $text_value = esc_attr( $instance['title'] );
      $text_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => 'Title',
      );

      echo sk_add_element( $text_field, $text_value );

    }
  }
}

/**
 *
 * Categories Widget
 *
 */
if( ! class_exists( 'Pressapps_Knowledge_Base_Widget_Categories' ) ) {
  class Pressapps_Knowledge_Base_Widget_Categories extends WP_Widget {

    function __construct() {

      $widget_ops     = array(
        'classname'   => 'knowledge_base_categories',
        'description' => 'Display list of knowledge base categories.'
      );

      parent::__construct( 'knowledge_base_categories', 'Knowledge Base Categories', $widget_ops );

    }

    function widget( $args, $instance ) {

      extract( $args );

      echo $before_widget;

      if ( ! empty( $instance['title'] ) ) {
        echo $before_title . $instance['title'] . $after_title;
      }

      global $pakb;

			$display_count 			= $instance['count'];

			$terms_args['hide_empty']	= 1;
			$terms_args['order'] 			= $instance['order'];
			$terms_args['orderby'] 		= $instance['orderby'];
			$terms_args['number'] 		= $instance['number'];
			$terms_args['parent'] 		= 0;

			$terms = get_terms( 'knowledgebase_category', $terms_args );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

			    echo '<ul class="uk-list uk-list-large pakb-primary-color pakb-link pakb-widget-categories">';

			    foreach ( $terms as $term ) {
                    $icon = '';
					if ( $pakb->get( 'icon_cat' ) ) {
						$term_meta = $pakb->get_taxonomy('knowledgebase_category',$term->term_id);
                        if ( !empty( $term_meta ) ) {
                            $icon = '<i class="' . $term_meta['icon'] . ' uk-position-absolute"></i> ';
                        }
					}
					if ( $display_count ) {
						$count = ' (' . $term->count . ')';
					} else {
						$count = '';
					}
			    	echo '<li class="uk-position-relative">' . $icon . '<a href="' . get_term_link( $term ) . '" title="' . sprintf( $term->name ) . '">' . $term->name . $count . '</a></li>';
			    }

			    echo '</ul>';
			}

      echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {

      $instance            = $old_instance;
      $instance['title']   = $new_instance['title'];
      $instance['number']  = $new_instance['number'];
      $instance['orderby'] = $new_instance['orderby'];
      $instance['order']   = $new_instance['order'];
      $instance['count']   = $new_instance['count'];

      return $instance;

    }

    function form( $instance ) {

      // Set defaults
      $instance   = wp_parse_args( $instance, array(
        'title'   => 'Knowledge Base Categories',
        'number'   => '10',
        'orderby'   => 'name',
        'order'   => 'ASC',
        'count'   => '0',
      ));

      // Title
      $title_value = esc_attr( $instance['title'] );
      $title_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => 'Title',
      );

      echo sk_add_element( $title_field, $title_value );

      // Number of Categories
      $number_value = esc_attr( $instance['number'] );
      $number_field = array(
        'id'    => $this->get_field_name('number'),
        'name'  => $this->get_field_name('number'),
        'type'  => 'number',
        'title' => 'Number of Categories',
      );

      echo sk_add_element( $number_field, $number_value );

      // Order By
      $orderby_value = esc_attr( $instance['orderby'] );
      $orderby_field = array(
        'id'    => $this->get_field_name('orderby'),
        'name'  => $this->get_field_name('orderby'),
        'type'  => 'radio',
        'title' => 'Order By',
        'options' => array(
          'name' => 'Title',
          'term_group' => 'Reorder',
        ),
      );

      echo sk_add_element( $orderby_field, $orderby_value );

      // Order
      $order_value = esc_attr( $instance['order'] );
      $order_field = array(
        'id'    => $this->get_field_name('order'),
        'name'  => $this->get_field_name('order'),
        'type'  => 'radio',
        'title' => 'Order',
        'options' => array(
          'ASC' => 'Ascending',
          'DESC' => 'Descending',
        ),
      );

      echo sk_add_element( $order_field, $order_value );

      // Count
      $count_value = esc_attr( $instance['count'] );
      $count_field = array(
        'id'    => $this->get_field_name('count'),
        'name'  => $this->get_field_name('count'),
        'type'  => 'radio',
        'title' => 'Display Article Count',
        'options' => array(
          '1' => 'Yes',
          '0' => 'No',
        ),
      );

      echo sk_add_element( $count_field, $count_value );

    }
  }
}


/**
 *
 * Articles Widget
 *
 */
if( ! class_exists( 'Pressapps_Knowledge_Base_Widget_Articles' ) ) {
  class Pressapps_Knowledge_Base_Widget_Articles extends WP_Widget {

    function __construct() {

      $widget_ops     = array(
        'classname'   => 'knowledge_base_articles',
        'description' => 'Display list of knowledge base articles.'
      );

      parent::__construct( 'knowledge_base_articles', 'Knowledge Base Articles', $widget_ops );

    }

    function widget( $args, $instance ) {

      extract( $args );

      echo $before_widget;

      if ( ! empty( $instance['title'] ) ) {
        echo $before_title . $instance['title'] . $after_title;
      }

      global $post, $pakb_helper;

			$wpq_args['post_type'] 		= 'knowledgebase';
			$wpq_args['post_status'] 	= 'publish';
			$wpq_args['orderby'] 		= $instance['orderby'];
			$wpq_args['order'] 			= $instance['order'];
			$wpq_args['posts_per_page'] = $instance['posts_per_page'];

			if ( $wpq_args['orderby'] == 'meta_value_num') {
				$wpq_args['meta_key']   = '_votes_likes';
			}

			if ( $instance['filter'] == 'category' ) {
				$wpq_args['tax_query'] 		= array(
					array(
						'taxonomy'         => 'knowledgebase_category',
						'field'            => 'ID',
						'terms'            => $instance['id'],
						'include_children' => true
					)
				);
			} elseif ( $instance['filter'] == 'tag' ) {
				$wpq_args['tax_query'] 		= array(
					array(
						'taxonomy'         => 'knowledgebase_tags',
						'field'            => 'ID',
						'terms'            => array( $instance['id'] ),
						'include_children' => true
					)
				);
			}

			$items = new WP_Query( $wpq_args );

			if ( 0 == $items->found_posts ) {

				_e( 'There are no knowledge base articles.', 'pressapps-knowledge-base' );

			} else { ?>

                <ul class="uk-list uk-list-large pakb-primary-color pakb-link">
                	<?php foreach ( $items->posts as $item ) { ?>
                		<li><a href="<?php echo get_permalink( $item->ID ); ?>"><?php echo esc_attr( $item->post_title ); ?></a></li>
                	<?php } ?>
                </ul>
			<?php }

			wp_reset_postdata();

      echo $after_widget;

    }

    function update( $new_instance, $old_instance ) {

      $instance                   = $old_instance;
      $instance['title']          = $new_instance['title'];
      $instance['posts_per_page'] = $new_instance['posts_per_page'];
      $instance['orderby']        = $new_instance['orderby'];
      $instance['order']          = $new_instance['order'];
      $instance['filter']         = $new_instance['filter'];
      $instance['id']             = $new_instance['id'];

      return $instance;

    }

    function form( $instance ) {

      // Set defaults
      $instance   = wp_parse_args( $instance, array(
        'title'             => 'Knowledge Base Articles',
        'posts_per_page'    => '10',
        'orderby'           => 'date',
        'order'             => 'DESC',
        'filter'            => '',
        'id'                => '',
      ));

      // Title
      $title_value = esc_attr( $instance['title'] );
      $title_field = array(
        'id'    => $this->get_field_name('title'),
        'name'  => $this->get_field_name('title'),
        'type'  => 'text',
        'title' => 'Title',
      );

      echo sk_add_element( $title_field, $title_value );

      // Number of Categories
      $number_value = esc_attr( $instance['posts_per_page'] );
      $number_field = array(
        'id'    => $this->get_field_name('posts_per_page'),
        'name'  => $this->get_field_name('posts_per_page'),
        'type'  => 'number',
        'title' => 'Number of Articles',
      );

      echo sk_add_element( $number_field, $number_value );

      // Order By
      $orderby_value = esc_attr( $instance['orderby'] );
      $orderby_field = array(
        'id'    => $this->get_field_name('orderby'),
        'name'  => $this->get_field_name('orderby'),
        'type'  => 'radio',
        'title' => 'Order By',
        'options' => array(
          'date' => 'Date',
          'title' => 'Title',
          'menu_order' => 'Reorder',
          'meta_value_num' => 'Likes'
        ),
      );

      echo sk_add_element( $orderby_field, $orderby_value );

      // Order
      $order_value = esc_attr( $instance['order'] );
      $order_field = array(
        'id'    => $this->get_field_name('order'),
        'name'  => $this->get_field_name('order'),
        'type'  => 'radio',
        'title' => 'Order',
        'options' => array(
          'ASC' => 'Ascending',
          'DESC' => 'Descending',
        ),
      );

      echo sk_add_element( $order_field, $order_value );

      // Filter
      $filter_value = esc_attr( $instance['filter'] );
      $filter_field = array(
        'id'    => $this->get_field_name('filter'),
        'name'  => $this->get_field_name('filter'),
        'type'  => 'select',
        'title' => 'Filter',
        'options' => array(
          '' => 'None',
          'category' => 'Filter by Category',
          'tag' => 'Filter by Tag',
        ),
      );

      echo sk_add_element( $filter_field, $filter_value );

      // ID
      $id_value = esc_attr( $instance['id'] );
      $id_field = array(
        'id'    => $this->get_field_name('id'),
        'name'  => $this->get_field_name('id'),
        'type'  => 'text',
        'title' => 'Category or Tag ID',
      );

      echo sk_add_element( $id_field, $id_value );

    }
  }
}

if ( ! function_exists( 'pressapps_knowledge_base_widget_init' ) ) {
  function pressapps_knowledge_base_widget_init() {
    register_widget( 'Pressapps_Knowledge_Base_Widget_Search' );
		register_widget( 'Pressapps_Knowledge_Base_Widget_Categories' );
    register_widget( 'Pressapps_Knowledge_Base_Widget_Articles' );
  }
  add_action( 'widgets_init', 'pressapps_knowledge_base_widget_init', 2 );
}
