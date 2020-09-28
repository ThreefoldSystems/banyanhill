<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
/**
 * Setting global variables.
 *
 */
global $pakb, $pakb_helper, $pakb_loop; ?>

<?php
$search_display = $pakb->get( 'search_display' );

if ( !empty($search_display) && in_array( 'category', $search_display ) ) {
	$pakb_helper->the_search();
}
?>

<div class="pakb-link">
    <?php $pakb_loop->the_breadcrumbs(); ?>
    <?php $pakb_loop->get_category_desc(); ?>
    <ul class="uk-margin-large-top uk-list uk-list-large pakb-list pakb-primary-color link-icon-right">
    <?php
        while( $pakb_loop->have_posts() ) : $pakb_loop->the_post();
            do_action( 'pakb_archive_loop' ); // action inside the loop for archive page
    ?>
        <li id="<?php echo esc_attr( 'kb-' . $pakb_loop->get_the_ID() ); ?>"><a href="<?php echo esc_url( $pakb_loop->get_the_permalink() ); ?>"><?php $pakb_loop->the_title(); ?><?php //$pakb_helper->vote_ui(); ?></a></li>
    <?php
        endwhile;
    ?>
    </ul>
</div>
