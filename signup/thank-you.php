<?php
	// set cookie for 1 year
	setcookie("is_signed_up", '1', time()+60*60*24*365, '/', '');
	parse_str($_SERVER['QUERY_STRING'], $query);

	if ($query['to'] === '0' && (!empty($query['redirect']) && !empty($query['url'])) ) {
		$redirect_variable = !empty($query['redirect']) ? $query['redirect'] : $query['url'];
		
		echo "<script>window.top.location.href = \"" . $redirect_variable . "\";</script>";
	} else {
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />	
<title>Banyan Hill Thank You</title>
<link rel="stylesheet" id="extra-fonts-css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800&subset=latin,latin-ext" type="text/css" media="all">
<style>
	* {
		box-sizing: border-box;
	}
	body {
		margin: 0px;
		padding: 0 20px;
		overflow: hidden;
		font-family: "Open Sans",sans-serif;
	}
	h1 {
		text-align: center;
		margin: 0px;
		line-height: 32px;
	}
	p {
		text-align: center;
		font-size: 14.5px;
	}
	.content {
		margin-bottom: 20px;
	}
	.disclaimer {
		font-size: 11px;
		font-style: italic;
		padding-bottom: .875em;
		text-align: left;
	}
	.disclaimer:before {
		content: "\2713";
		font-size: 15px;
		color: #fff;
		float: left;
		margin: -2px 10px 0 0;
		border-radius: 20px;
		padding: 10px;
		line-height: 14px;
		width: 14px;
		height: 14px;
		background: rgb(34,235,25); /* Old browsers */
		background: -moz-linear-gradient(top, rgba(34,235,25,1) 30%, rgba(21,169,15,1) 100%); /* FF3.6-15 */
		background: -webkit-linear-gradient(top, rgba(34,235,25,1) 30%,rgba(21,169,15,1) 100%); /* Chrome10-25,Safari5.1-6 */
		background: linear-gradient(to bottom, rgba(34,235,25,1) 30%,rgba(21,169,15,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#22eb19', endColorstr='#15a90f',GradientType=0 );
		box-shadow: 0 6px 12px -6px rgba(0,0,0,0.75);
	}
	.well .disclaimer {
		width: 35%;
    	margin: 0 auto;
		line-height: 30px;
	}
	.well .disclaimer:before {
		text-align: left;
	}
</style>
	
</head>

<body>
<div class="content">
	<h1><?php echo $query['message'] ? $query['message'] : 'Thank You!' ?></h1>
	<p>Be sure to check your email for updates from our experts.</p>
	<p class="disclaimer">Banyan Hill cares about your privacy and does not share your email.</p>	
</div>
	
<script>
	var parentContainer = window.frameElement.parentNode;
	var redirect = parent.getParameterByName('redirect', window.location.href) !== null ? parent.getParameterByName('redirect', window.location.href) : parent.getParameterByName('url', window.location.href);
	var redirectTimeout = parent.getParameterByName('to', window.location.href) !== null ? parent.getParameterByName('to', window.location.href) : (parent.getParameterByName('delay', window.location.href) !== null ? parent.getParameterByName('delay', window.location.href) : 5);	
	
	if (redirect !== null) {
		document.getElementsByClassName('content')[0].insertAdjacentHTML('beforeend', '<p>You will be redirected in ' + redirectTimeout + ' seconds.</p>');
	}
	
	function sendConfirmEvent() {
		var zCode;
		var eventDetail = {detail: {}};

		if (window['Storage']) {
			if (sessionStorage.getItem('zCode') !== null) {
				eventDetail.detail.zCode = sessionStorage.getItem('zCode');	
			}
		}

		var event = new CustomEvent('SUA2Confirmation', eventDetail);
		
		window.parent.document.dispatchEvent(event);
	}
	
	function getAbsoluteHeight(el) {
	  // Get the DOM Node if a string is passed
	  el = (typeof el === "string") ? document.querySelector(el) : el; 

	  var styles = window.getComputedStyle(el);
	  var margin = parseFloat(styles["marginTop"]) +
				   parseFloat(styles["marginBottom"]);	

	  return el.offsetHeight + margin;
		
	}

	function resizeFrame() {
		parentContainer.getElementsByTagName('iframe')[0].setAttribute("height", getAbsoluteHeight(".content"));
	}

	// Resize Frame to Content Size
	parentContainer.getElementsByTagName('iframe')[0].setAttribute("width", "100%");
	window.onload = function() {
		resizeFrame();
	}
	window.onresize = function() {
		resizeFrame();
	}
	window.onorientationchange = function() {
		resizeFrame();
	}
	
	var queryUrl = <?php echo $query['url'] ? "'".$query['url']."'" : "''" ?>;
	
	sendConfirmEvent();
	
	if (redirect !== null ) {		
		setTimeout(function(){
			window.top.document.location = redirect;
		}, redirectTimeout*1000);		
	} else {
		setTimeout(function() {
			var signups = window.top.document.querySelectorAll('[class^="Newsletter_new"]');

			// make sure the transition and timeout match
			for (var i=0; i < signups.length; i++) {
				var el = signups[i].parentNode.parentNode;
				el.style.transition = 'all 0.4s ease';
				el.style.visibility = 'hidden';
				el.style.opacity = '0';

				setTimeout(function(el){
					el.style.height = '0';
					el.style.margin = '0';
				}, 400, el);
			}
		}, redirectTimeout*1000);		
	}
</script>
</body>
</html>
<?php } ?>