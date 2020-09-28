<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $pakb, $pakb_helper;

$cat = get_queried_object()->term_id;;

if ( $pakb->get( 'reorder' ) !== 'default' ) {
	$orderby = $pakb_helper->reorder_option();

	$args = array(
		'post_type' => 'knowledgebase',
		'orderby'   => $orderby,
		'order'     => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'knowledgebase_category',
				'field'            => 'id',
				'terms'            => $cat,
				'include_children' => false
			)
		),
	);
} else {
	$args = array(
		'post_type'   => 'knowledgebase',
		'numberposts' => - 1,
		'tax_query'   => array(
			array(
				'taxonomy'         => 'knowledgebase_category',
				'field'            => 'id',
				'terms'            => $cat,
				'include_children' => false
			)
		),
	);
}

$query_children = new WP_Query( $args );

while( $query_children->have_posts() ) : $query_children->the_post(); ?>
  <li id="<?php echo esc_attr( 'kb-' . get_the_ID() ); ?>"><a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?><?php //$pakb_helper->vote_ui(); ?></a></li>
<?php
endwhile;
