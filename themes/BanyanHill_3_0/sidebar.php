<?php if ( extra_get_sidebar_class() ) { ?>
<div class="et_pb_extra_column_sidebar">
	<?php 
		dynamic_sidebar( extra_sidebar() );	
	
		if ( is_home() ) { 
		echo '<div id="azk464531" style="margin: 20px auto; display: flex;"></div>
			<script type="text/javascript">
			  var protocol = document.location.protocol == "https:" ? "https" : "http";
			  var z = document.createElement("script");
			  z.type = "text/javascript";
			  z.src = protocol + "://static.adzerk.net/ados.js";
			  z.async = true;
			  var s = document.getElementsByTagName("script")[0];
			  s.parentNode.insertBefore(z,s);
			</script>
			<script type="text/javascript">
			  var ados = ados || {};
			  ados.run = ados.run || [];
			  ados.run.push(function() {
				/* load placement for account: 14west,
									  site: Banyan Hill - Banyan Hill,
									  zone: Top Right Pane,
									  size: 300x250 - Medium Rectangle */
				ados_add_placement(10203, 1087804, "azk464531", 5)
				  .setZone(207087);
				ados_setKeywords(\'finance\');
				ados_load();
			  });
			</script>';
		} else {
		echo '<div id="azk636362" style="margin: 20px auto; display: flex;"></div>
			<script type="text/javascript">
			  var protocol = document.location.protocol == "https:" ? "https" : "http";
			  var z = document.createElement("script");
			  z.type = "text/javascript";
			  z.src = protocol + "://static.adzerk.net/ados.js";
			  z.async = true;
			  var s = document.getElementsByTagName("script")[0];
			  s.parentNode.insertBefore(z,s);
			</script>
			<script type="text/javascript">
			  var ados = ados || {};
			  ados.run = ados.run || [];
			  ados.run.push(function() {
				/* load placement for account: 14west,
									  site: Banyan Hill - Banyan Hill,
									  zone: Top Right Pane,
									  size: 300x250 - Medium Rectangle */
				ados_add_placement(10203, 1087804, "azk636362", 5)
				  .setZone(207087);
				/* load placement for account: 14west,
									  site: Banyan Hill - Banyan Hill,
									  zone: In Article/Bottom of Page,
									  size: 300x250 - Medium Rectangle */
				ados_add_placement(10203, 1087804, "azk580314", 5)
				  .setZone(207088);
				ados_setKeywords(\'finance\');
				ados_load();
			  });
			</script>';
		}
	?>
</div>
<?php }
