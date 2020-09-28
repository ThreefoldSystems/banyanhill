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
$i         = 0;
//$skip      = true;
$page_link = get_page_link();

if ( $pakb->get( 'columns' ) ) {
	$columns = $pakb->get( 'columns' );
} else {
	$columns = 2;
}

if ( $pakb->get( 'kb_page_layout' ) == 2 ) {
	$class = 'pakb-boxes';
	$gridmatch = ' uk-grid-match';
} else {
	$class = 'pakb-lists';
	$gridmatch = '';
}

?>

<?php
$search_display = $pakb->get( 'search_display' );

if ( is_pakb_main() && ( !empty($search_display) && in_array( 'main', $search_display ) ) ) {
	$pakb_helper->the_search();
} elseif ( is_pakb_category() && ( !empty($search_display) && in_array( 'category', $search_display ) ) ) {
	$pakb_helper->the_search();
}


?>
<?php $pakb_loop->the_breadcrumbs(); ?>
<?php $pakb_loop->get_category_desc(); ?>

<?php
$layout_main = $pakb->get('layout_main');
$layout = $layout_main['enabled'];

if ($layout): foreach ($layout as $key=>$value) {

    switch($key) {

        case 'content':
			if ( $pakb_loop->is_kbpage() ) {
				while ( have_posts() ) : the_post();
					echo '<div class="pakb-section">';
					the_content();
					echo '</div>';
				endwhile;
			}
	        break;

        case 'main':
        	?>
			<div class="pakb-section pakb-link <?php echo $class; ?>">
				<div class="<?php echo esc_attr( 'uk-child-width-1-' . $columns . '@m' . $gridmatch ); ?>" data-uk-grid>
					<?php
					foreach ( $pakb_loop->get_cats() as $cat ){
						$pakb_loop->setup_cat( $cat );
						if ( ! $pakb_loop->subcat_have_posts() ) {
							continue;
						}
						$pakb_loop->print_the_cat();
					} ?>
				</div><?php if ( is_pakb_category() ) { ?>
					<ul class="uk-margin-large-top uk-list uk-list-large pakb-list pakb-primary-color pakb-link link-icon-right">
						<?php include( plugin_dir_path( __FILE__ ) . 'knowledgebase-children.php' ); ?>
					</ul>
				<?php } ?></div>
        	<?php
	        break;

        case 'sidebar':
        	if ( $pakb_loop->is_kbpage() ) {
        	?><div class="pakb-section pakb-sidebar-main"><div class="uk-child-width-expand@m" data-uk-grid><?php dynamic_sidebar('pakb-main'); ?></div></div><?php
			}
	        break;

    }

}
endif;

?>
