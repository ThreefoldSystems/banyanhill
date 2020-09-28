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
$meta = 			$pakb->get( 'meta' );
$meta_display = 	$pakb->get( 'meta_display' );
?>

<?php
$search_display = $pakb->get( 'search_display' );

if ( !empty($search_display) && in_array( 'single', $search_display ) ) {
	$pakb_helper->the_search();
}
?>

<?php
while ( $pakb_loop->have_posts() ) : $pakb_loop->the_post();
	do_action( 'pakb_single_loop' ); // action inside the loop for single page ?>
	<article class="uk-article pakb-link">
		<?php $pakb_loop->the_breadcrumbs(); ?>

		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail();
		}
		?>

		<?php if ( $pakb->get( 'toc' ) ) { ?>
			<div class="pakb-toc-wrap uk-margin-large-bottom">
				<?php if ( $pakb->get( 'toc_title' ) ) { ?>
					<h3 class="uk-margin-medium-bottom"><?php echo $pakb->get( 'toc_title' ); ?></h3>
				<?php } ?>
				<ul class="pakb-toc uk-nav pakb-accent-color" data-toc=".pakb-article-content" data-toc-headings="<?php echo $pakb->get( 'toc_selectors' ); ?>"></ul></div>
		<?php } ?>
				<?php
		$article_meta = get_post_meta( get_the_ID(), '_pakb_article', true );
		if ( !empty( $article_meta['styled_ol'] ) && $article_meta['styled_ol'] ) {
			$style = ' styled-ol';
		} else {
			$style = '';
		}
		?>
		<div class="pakb-article-content<?php echo $style; ?>">
			<?php $pakb_loop->the_content(); ?>
		</div>
		<?php if ( $meta_display ) { ?>
			<div class="pakb-secondary-color uk-margin-medium-top">
				<?php if ( !empty($meta) && in_array( 'updated', $meta ) ) { ?>
					<time class="updated published" datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php printf( __( 'Last Updated: %s ago', 'pressapps-knowledge-base' ), human_time_diff( get_the_modified_date( 'U' ), current_time( 'timestamp' ) ) ); ?></time> <?php } ?><?php if ( !empty($meta) && in_array( 'category', $meta ) ) { ?>
					<?php $pakb_loop->the_category(); ?>
				<?php } ?>
				<?php if ( !empty($meta) && in_array( 'tags', $meta ) ) { ?>
					<?php $pakb_loop->the_tags(); ?>
				<?php } ?>
			</div>
		<?php } ?>
		<?php
		if ( $pakb->get( 'voting' ) != 0 && ! $pakb_loop->post_password_required() ) {
			echo '<div id="pakb-vote">';
			$this->the_votes();
			echo '</div>';
		} ?>
		<?php
		if ( $pakb->get( 'related_articles' ) ) {
			$pakb_helper->display_related_articles( $pakb_loop->get_the_ID() );
		}
		?>
		<?php
		if ( $pakb->get( 'comments' ) ) {
			$theme = wp_get_theme();
			if ( 'PressApps Helpdesk' == $theme->name || 'PressApps Helpdesk' == $theme->parent_theme ) {
				comments_template( '/templates/comments.php' );
			} else {
				comments_template();
			}
		}
		?>
	</article>
	<?php
endwhile;
?>
