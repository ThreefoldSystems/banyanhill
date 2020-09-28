<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Setting global variables.
 *
 */
global $pakb, $pakb_helper, $pakb_loop;

// if ( $pakb->get( 'columns' ) ) {
// 	$columns = $pakb->get( 'columns' );
// } else {
// 	$columns = 2;
// }

$description = $pakb_loop->get_cat_description();

if ( $pakb->get( 'icon_cat' ) ) {
	$tax_meta = $pakb->get_taxonomy('knowledgebase_category',$pakb_loop->get_cat_id());
	$icon = $tax_meta['icon'];
	//$icon = '<i class="si-folder4"></i> ';
} else {
	$icon = '';
}
?>

<?php if ( $pakb->get( 'kb_page_layout' ) == 2 ) { ?>
	<div>
		<div class="uk-card uk-card-small uk-card-body uk-border-rounded uk-inline uk-text-center">
			<div><a class="card-link uk-position-cover" href="<?php echo esc_url( $pakb_loop->get_cat_link() ); ?>"></a></div>
			<?php if ( !empty($icon) ) { ?><div class="pakb-box-icon"><i class="pakb-accent-color <?php echo $icon; ?>"></i></div><?php } ?>
			<h2 class="uk-card-title pakb-primary-color"><?php $pakb_loop->the_cat_name(); ?></h2>
			<?php if ( !empty( $description ) ) { ?><p class="pakb-secondary-color"><?php $pakb_loop->the_cat_description(); ?></p><?php } ?>
			<?php if ( $pakb->get( 'view_all' ) ) { ?><p class="pakb-secondary-color"><?php _e( 'View All', 'pressapps-knowledge-base'); ?><?php echo ( $pakb_loop->is_view_all_count_enabled() ? $pakb_loop->get_the_cat_count(' ','') : '' ); ?></p><?php } ?></div>
	</div>
<?php } else { ?>
	<div>
		<h2 class="pakb-accent-color"><?php echo '<i class="' . $icon . '"></i> '; ?><a href="<?php echo esc_url( $pakb_loop->get_cat_link() ); ?>"><?php $pakb_loop->the_cat_name(); ?> <?php echo ( $pakb_loop->is_cat_count_enabled() ? $pakb_loop->get_the_cat_count('(',')') : '' ); ?></a></h2>
		<ul class="uk-list uk-list-large">
			<?php
			while ($pakb_loop->subcat_have_posts() ) {
				$pakb_loop->subcat_the_post();
				do_action( 'pakb_category_loop' ); // action inside the loop for category page
				?>
				<li><a class="pakb-primary-color" href="<?php echo esc_url( $pakb_loop->subcat_get_the_permalink() ); ?>"><?php $pakb_loop->subcat_the_title(); ?></a></li>
				<?php
			}
			?>
		</ul>
		<?php if ( $pakb->get( 'view_all' ) ) { ?><a class="pakb-secondary-color uk-margin-small-top uk-margin-remove-bottom" href="<?php $pakb_loop->the_cat_link(); ?>"><?php _e( 'View All', 'pressapps-knowledge-base'); ?><?php echo ( $pakb_loop->is_view_all_count_enabled() ? $pakb_loop->get_the_cat_count(' ','') : '' ); ?></a><?php } ?>
	</div>
<?php } ?>
