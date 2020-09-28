<?php get_header( 'poll' ); ?>
<?php
if ( have_posts() ) :
	while ( have_posts() ) : the_post();
		the_content();
	endwhile;
else :
	?>
	<h2><?php esc_html_e( 'Post not found', 'extra' ); ?></h2>
	<?php
endif;
wp_reset_query();
?>