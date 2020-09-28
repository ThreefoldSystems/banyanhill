<script type="text/javascript">
	var post_offset, increment, loading, page = 0, page_increment = 0;
	var term_id = '<?php echo $terms_id; ?>';
	var srh = '<?php echo $_GET['srh']; ?>';
	var archivedate = '<?php echo $archivedate; ?>';
	var ajax_action = '<?php echo $ajaxaction; ?>';

	(function ($) {
		$(document).ready(function () {
			$('.archive-list').append('<div id="last"></div>');
			
			$(window).bind('scroll', checkScroll);
			
			if (archivedate !== '') {
				$('form select[name="archivedate"]>option[value="' + archivedate + '"]').prop('selected', true);
			}			
		});

		var checkScroll = function (e) {
			var elem = $(e.currentTarget);
			var distanceTop;

			if (jQuery('#last').length > 0) {
				distanceTop = jQuery('#last').offset().top - $(window).height();
			} else {
				return;
			}

			if ($(window).scrollTop() > distanceTop) {
				if (loading) return true;
				
				if (!loading) {
					loading = 1;

						var params = {
							'offset': post_offset,
							'term_id': term_id,
					<?php if($_GET['srh'] == 'search' && $archivedate != '') { ?>							
							'srh': srh,
							'archivedate': archivedate,
					<?php } ?>							
							'action': ajax_action
						};

					document.getElementById('image-ajax').style.visibility = 'visible';

					var site_ajax_url = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';

					$.post(site_ajax_url, params, function(data) {
						if (data.trim()) {
							post_offset += increment;
							page += page_increment;
							loading = 0;

							$('.archive-list').append(data);
							$('#last').remove();
							$('.archive-list').append('<div id="last"></div>');

							document.getElementById('image-ajax').style.visibility = 'hidden';
						} else {
							$('#last').remove();
							$('#image-ajax').html('');
						}
					});
				}
			}
		}
	}(jQuery));
</script>