<?php
/**
 * Template Name: subscription service header
 *
 */

wp_enqueue_style( 'premium', get_stylesheet_directory_uri() . '/css/premium.css' );

$pubcode = get_the_terms( get_the_ID(), 'pubcode' );

// Bauman
if ( is_array($pubcode) && count( $pubcode ) > 1 ) {
	$is_allowed = [];

	for ( $i = 0; $i < count( $pubcode ); $i++ ) {
		if ( $auth_container->is_allowed() ) $is_allowed[$i] = strtolower( $pubcode[$i]->name );
	}
	// https://stackoverflow.com/a/11836780
	if (false !== $key = array_search( 'svc', $is_allowed ) ) {
		$pubcode = $is_allowed[$key];
	} else {
		// only check for Bauman (Light)
		if (false !== $key = array_search( 'sce_cf', $is_allowed ) ) {
			$pubcode = $is_allowed[$key];
		} else {
			//force Elite
			//TODO: Fix 
			$pubcode = 'svc';
		}
	}
} else {
	$pubcode = strtolower( $pubcode[0]->name );	
}

$subscriptions_query = new WP_Query(
	array(
		'cat'	=> 209, // Subscription Category
		'post_type' => 'page',
		'post_status' => 'publish',
		'posts_per_page' => 1,
		'tax_query' => array(
			array(
				'taxonomy' => 'pubcode',
				'field' => 'name',
				'terms' => $pubcode,
				'include_children' => false
			)
		)					
	)
);

if ( $subscriptions_query->have_posts() ) {
	// For each subscription
	while ($subscriptions_query->have_posts()) {
		$subscriptions_query->the_post();			

		if ($subscriptions_query->post->post_parent) {
			$ancestors = get_post_ancestors($subscriptions_query->post->ID);
			$root = count( $ancestors ) - 1;
			$parent = $ancestors[$root];
		} else {
			$parent = $subscriptions_query->post->ID;
		}

	}

	wp_reset_postdata();
}

$bauman_services = array ( 'bof', 'sfa', 'sce_cf' );
$bauman_services_elite = array ( 'svc' );

if ( $pubcode === 'svc' ) {
	$sidebar = '[ca-sidebar id="348527"]';	
} else if ( in_array( $pubcode, $bauman_services) ) {
	$sidebar = '[ca-sidebar id="348526"]';
}	

$bas64_loading_image = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDBweCIgIGhlaWdodD0iNDBweCIgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIiBjbGFzcz0ibGRzLWVjbGlwc2UiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsgYmFja2dyb3VuZDogbm9uZTsiPjxwYXRoIG5nLWF0dHItZD0ie3tjb25maWcucGF0aENtZH19IiBuZy1hdHRyLWZpbGw9Int7Y29uZmlnLmNvbG9yfX0iIHN0cm9rZT0ibm9uZSIgZD0iTTEwIDUwQTQwIDQwIDAgMCAwIDkwIDUwQTQwIDQzIDAgMCAxIDEwIDUwIiBmaWxsPSJyZ2JhKDAlLDAlLDAlLDAuNikiIHRyYW5zZm9ybT0icm90YXRlKDM2MCAtOC4xMDg3OGUtOCAtOC4xMDg3OGUtOCkiIGNsYXNzPSIiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0icm90YXRlIiBjYWxjTW9kZT0ibGluZWFyIiB2YWx1ZXM9IjAgNTAgNTEuNTszNjAgNTAgNTEuNSIga2V5VGltZXM9IjA7MSIgZHVyPSIwLjVzIiBiZWdpbj0iMHMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiBjbGFzcz0iIiBzdHlsZT0iYW5pbWF0aW9uLXBsYXktc3RhdGU6IHJ1bm5pbmc7IGFuaW1hdGlvbi1kZWxheTogMHM7Ij48L2FuaW1hdGVUcmFuc2Zvcm0+PC9wYXRoPjwvc3ZnPg==';

$page_title = isset($is_archive) ? $breadcrumb_loop->post->post_title : ( get_post_meta(get_the_ID(), 'page_title', true) ? get_post_meta(get_the_ID(), 'page_title', true) : get_the_title() );

$title_class = isset($has_archive_filter) ? 'col-lg-6 col-md-6 col-12' : 'col-lg-9 col-md-9 col-12';
							
// Get Expert image and subscription logo
if ( get_post_meta($parent, 'expert_id', true) ) {
	$expert_attached_id = get_post_meta($parent, 'expert_id', true);

	// Check if expert has thumbnail
	if ( has_post_thumbnail( $expert_attached_id ) ) {
		$expert_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $expert_attached_id ), array( 68, 90 ) );
	}
}

if ( has_post_thumbnail( $parent ) ) {
	$subscription_image_url = wp_get_attachment_url( get_post_thumbnail_id( $parent ) );
}

if ($expert_image_url && $subscription_image_url) {
?>
<div class="row serviceHeader"><!-- Start top row with guru -->
	<div class="col-lg-1 col-md-1 col-12 guruImgWrp srvHdGuruImg">
		<a href="<?php echo get_permalink( $expert_attached_id ) ?>"><img class="loading" src="<?php echo $bas64_loading_image ?>" data-src="<?php echo $expert_image_url[0] ?>" alt="<?php echo get_the_title( $expert_attached_id ) ?>"></a>
	</div>
	<div class="col-lg-5 col-md-5 col-12 logoPad">
		<a href="<?php echo get_permalink( $parent ) ?>"><img class="loading" src="<?php echo $bas64_loading_image ?>" data-src="<?php echo $subscription_image_url ?>" alt="<?php echo get_the_title( $parent ) ?>"></a>
	</div>
	<div class="col-lg-6 col-md-6 col-12"></div>
</div><!-- End top row with guru -->
<?php
}
?>
<!-- Header and Sidebar -->
<div class="row titleRow">
	<div class="<?php echo $title_class ?>">
		<h1 class="pageTitle"><?php echo $page_title ?></h1>
	</div>
<?php if ( isset($has_archive_filter) ) { ?>
	<div class="archive_search col-lg-3 col-md-3 col-sm-4 col-12">
		<div class="row">
			<div class="col-lg-2 col-md-2 col-sm-6 col-12"></div>
			<div class="col-lg-3 col-md-3 col-sm-6 col-12 srvHdFilterTxt">Filter:</div>
			<div class="col-lg-7 col-md-7 col-sm-6 col-12 srvHdArchiveBtn">
				<form role="search" method="get" id="searchform_new" action="">
					<input type="hidden" name="srh" id="srh" value="search"/>
					<select name="archivedate" id="view-all" tabindex="1" onchange="search_data();">
						<option value=""><?php echo $archivedate && $archivedate !== '' ? 'CLEAR FILTER' : 'SELECT YEAR'; ?></option>
						<option value="2020">2020</option>
						<option value="2019">2019</option>
						<option value="2018">2018</option>
						<option value="2017">2017</option>
						<option value="2016">2016</option>
						<option value="2015">2015</option>						
						<?php
						if ( $is_meeting_of_the_minds_archive ) {
							?>
							<option value="2014">2014</option>
							<option value="2013">2013</option>
							<?php
						}
						?>
					</select>
				</form>
			</div>
		</div>	
	</div>	
<?php } ?>
	<div class="col-lg-3 col-md-3 col-12 portfolio-menu">
		<div class="et_pb_extra_column_sidebar">
			<?php
				if ( is_user_logged_in() ) {
					echo do_shortcode( '[bh_submenu parent="' . $parent . '"]' ); 
				}
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('#menu .menu-item-has-children').on('hover', function() {
			jQuery(this).toggleClass('active');
		});
	});
</script>