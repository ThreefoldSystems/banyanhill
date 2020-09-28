<?php
parse_str( $_SERVER[ 'QUERY_STRING' ], $query );
foreach($query as $key => $val) {
	$query[$key] = preg_replace( '/[^a-z0-9 ]/i', '', $val);
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex">
	<title>BanyanHill Signup</title>

	<style>
		* {
			box-sizing: border-box;
		}
		body {
			margin: 0;
			overflow: hidden;
			font-family: "Open Sans",sans-serif;
		}
		form {
			margin: 0;
			padding: 0;
		}
		table {
			border-collapse: collapse;
			border-spacing: 0;
			width: 100%;
		}
		table td, table th, table tr {
			text-align: left;
			max-width: 100%;
			padding: .857em;
		}
		#text-21 .widget-title {
			text-align: center;
			background: #ebebeb;
			border: none;
			color: #222;
			font-weight: bold;
			font-size: 15px;
			border-bottom: 2px #fff solid;
			padding: 15px 0;
			text-transform: inherit;
		}

		.Newsletter_new.sidebar p {
			font-size: 14.5px;
			padding: 13px;
			margin: 0;
		}

		.Newsletter_new label {
			display: none;
		}

		.Newsletter_new.sidebar h2 {
			font-weight: bold;
			font-size: 1.8em;
			text-align: center;
		}

		.Newsletter_new.sidebar .blueBG {
			color: #777777;
			padding: 4px;
			margin: 10px;
			text-align: center;
			font-size: 15px;
			font-weight: bold;
		}

		.Newsletter_new.sidebar .footerTxt {
			color: #939598;
			text-align: center;
			padding: 15px 8px;
			line-height: 17px;
		}

		.Newsletter_new input[type="text"] {
			width: 100%;
			height: 40px;
			text-align: center;
			padding: 0;
			font-size: 1.05em;
			color: #222;
			border: 1px solid #ccc;
		}

		.Newsletter_new input[type="submit"] {
			background: rgb(255,102,0);
			background: -moz-linear-gradient(top, rgba(255,102,0,1) 0%, rgba(229,91,0,1) 100%);
			background: -webkit-linear-gradient(top, rgba(255,102,0,1) 0%,rgba(229,91,0,1) 100%);
			background: linear-gradient(to bottom, rgba(255,102,0,1) 0%,rgba(229,91,0,1) 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff6600', endColorstr='#e55b00',GradientType=0 );
			color: #fff;
			font-size: 18px;
			font-weight: 500;
			border: none;
			padding: 10px 7px;
			text-align: center;
			border-radius: 0;
			box-shadow: 0 2px 6px -2px rgba(0,0,0,0.5);
			text-transform: uppercase;
			cursor: pointer;
			width: 100%;
			letter-spacing: 1px;
			margin: 0;
		}
		.Newsletter_new.well .form-field-container, .Newsletter_new.footer .form-field-container {
			display: flex;
			flex-flow: row wrap;
			width: 100%;
			margin-bottom: 10px;
			justify-content: center;
			align-items: center;
		}
		.Newsletter_new.well .form-field-table, .Newsletter_new.footer .form-field-table {
			border: none;
			width: 65%;
			margin: auto;
		}
		.Newsletter_new.footer table td {
			padding: .857em .857em .857em 0;
		}
		.errorContainer {
			font-size: 14px;
			font-style: italic;
			color: #FF0000;
			transition: all 0.5s ease;
			padding: 0 15px;
		}
		.Newsletter_new.well .errorContainer, .Newsletter_new.footer .errorContainer {
			flex: 0 0 100%;
		}
		.Newsletter-signup-submit {
			padding: 0 .875em .875em .875em;
			flex: 1;
		}
		.Newsletter_new.well .Newsletter-signup-submit, .Newsletter_new.footer .Newsletter-signup-submit {
			padding: .875em;
			flex: 1;
		}
		.errorContainer > div {
			display: none;
		}
		.footer-signup-form-email {
			padding: 5px;
			margin: 0 5px;
		}
		.well-signup-form-email {
			border: 1px solid rgba(0,0,0,0.2);
			background: rgba(255,255,255,.9);
			font-size: 15px;
		}
		.Newsletter_new.WID {
			background: #fff !important;
		}
		.Newsletter_new.WID input#email {
			width: 335px;
			height: 60px;
			border-style: solid;
			border-color: #dddddd;
			border-width: 1px;
			border-radius: 4px;
			padding: 15px;
			font-size: 16px;
		}
		.Newsletter_new.WID .Newsletter-signup-submit input {
			width: 335px !important;
			height: 60px;
			background-color: #3dbcc4;
			border-style: solid;
			border-color: #dddddd;
			border-width: 1px;
			border-radius: 4px;
			font-family: helvetica;
			text-transform: uppercase;
			letter-spacing: 5px;
			font-size: 20px;
			font-weight: normal;
			cursor: pointer;
			color: #fff;
			margin-bottom: 20px;
		}
		.Newsletter_new.WID .Newsletter-signup-submit input:hover {
			background-color: #2fa5ac;
			border-color: #bdbdbd;
		}
		.Newsletter_new.WID .Newsletter-signup-submit::after {
			content: "\A 100% FREE - Sign Me Up!";
			white-space: pre;
			font-size: 16px;
			font-style: italic;
			text-align: center;
		}

		@media (max-width: 576px) {
			.Newsletter_new.WID input#email {
				width: 300px !important;
			}
			.Newsletter_new.WID .Newsletter-signup-submit input {
				width: 100% !important;
				margin-bottom: 0px;
			}
			.Newsletter_new.well .form-field-table, .Newsletter_new.footer .form-field-table {
				width: 100%;
			}
			.Newsletter_new.well .Newsletter-signup-submit, .Newsletter_new.footer .Newsletter-signup-submit {
				padding: 0 .875em;
			}
		}
	</style>
</head>

<body>
	<form id="LeadGen" class="LeadGen Newsletter_new <?php echo $query['position'] ?>" action="https://research.banyanhill.com/Content/SaveFreeSignups" method="post">
		<input name="source" type="hidden" value="<?php echo $query['xcode'] ? $query['xcode'] : 'X190T276' ?>"/>
	<?php if (!$query['no_coreg']) { ?>
		<input style="display: none;" name="CoRegs" type="checkbox" value="172279" checked="true"/>
		<input style="display: none;" name="CoRegs" type="checkbox" value="233106" checked="true"/>
	<?php } ?>
		<input name="NotSaveSignup" type="hidden" value="False"/>
		<div align="center" class="form-field-container">
			<div class="errorContainer"></div>
			<table class="form-field-table" cellpadding="5" cellspacing="0" style="border:none;">
				<tbody>
					<tr style="border:none">
						<td align="right" style="border:none;">
							<label for="email" id="emailLabel" style="">Email:</label>
							<input class="<?php echo $query['position'] ?>-signup-form-email" id="email" maxlength="255" name="email" onblur="javascript:if (this.value == '') this.value = '';" onfocus="javascript:if (this.value == '') this.value = '';" type="text" value="" placeholder="<?php echo $query['emailtext'] ? $query['emailtext'] : 'Enter Your Email Address' ?>" data-check-nta="false" data-id="email" data-list-id="69172" data-service="emailOversight" />
						</td>
					</tr>
				</tbody>
			</table>
			<div class="Newsletter-signup-submit">
				<input id="<?php echo $query['position'] ?>-signup-form-submit" type="submit" value="<?php echo $query['buttontext'] ? $query['buttontext'] : 'Join Now' ?>"/>
			</div>
		</div>
	</form>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://s3.amazonaws.com/BanyanHillWebTeam/scripts/bh-process-lead.js"></script>
	<script type="text/javascript">
		var parentContainer = window.frameElement.parentNode;

	//	https://stackoverflow.com/a/14570614
	var observeDOM = (function(){
	  var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

	  return function( obj, callback ){
		if( !obj || !obj.nodeType === 1 ) return; // validation

		if( MutationObserver ){
		  // define a new observer
		  var obs = new MutationObserver(function(mutations, observer){
			  callback(mutations);
		  })
		  // have the observer observe foo for changes in children
		  obs.observe( obj[0], { childList:true, subtree:true });
		}

		else if( window.addEventListener ){
		  obj.addEventListener('DOMNodeInserted', callback, false);
		  obj.addEventListener('DOMNodeRemoved', callback, false);
		}
	  }
	})();
	
	// Observe a specific DOM element:
	observeDOM( jQuery('#LeadGen').find('.errorContainer'), function(m){ 

	   if (m[0].target === $('.errorContainer')[0]) {
		   $('.errorContainer').show();
		   resizeFrame();
	   }
	});
		function getAbsoluteHeight( el ) {
			// Get the DOM Node if a string is passed
			el = ( typeof el === 'string' ) ? document.querySelector( el ) : el;

			var styles = window.getComputedStyle( el );
			var margin = parseFloat( styles[ 'marginTop' ] ) +
						 parseFloat( styles[ 'marginBottom' ] );

			return el.offsetHeight + margin;
		}

		function resizeFrame() {
			parentContainer.getElementsByTagName( 'iframe' )[ 0 ].setAttribute( 'height', getAbsoluteHeight( '.LeadGen' ) );
		}

		// Resize Frame to Content Size
		parentContainer.getElementsByTagName( 'iframe' )[ 0 ].setAttribute( 'width', '100%' );
		window.onload = function () {
			resizeFrame();
		}
		window.onresize = function () {
			resizeFrame();
		}
		window.onorientationchange = function () {
			resizeFrame();
		}

		$(document).ready(function () {
			// Initiate form submission.
			$('#<?php echo $query['position'] ?>-signup-form-submit').click(function (e) {
				// Prevent form submission.
				e.preventDefault();
				var email = $('#email').val(); // Set email address.
				var options = { }; // Set options. [optional]
				var callback = function(x) {
					// do nothing
				}
			if (email === '') return;

			BHEmailOversightValidation(
			  'modalConfirm',
			  'banyanhill',
			  emailOversight.apiToken,
			  jQuery(this).closest('.LeadGen').find('[class*="-signup-form-email"]'),
			  jQuery(this),
			  jQuery(this).closest('.LeadGen').find('.errorContainer'),
			  function(rt) {
				submitTheForm($('#LeadGen').attr('action'), $('#LeadGen').serialize());
			  }
			);			
		});

		$('#email').on('focus', function(){
			$('.errorContainer').slideUp(250, function() {
				resizeFrame();
			});
		});
	});

		/**
		* Sample Custom Function to demonstrate form submission with extra fields.
		* @param url – URL for where to send email. * @param data – Data for which to post.
		*/

	function submitTheForm(url, data) {
		// Check & Set zCode if set (by GTM)
		// If not set by GTM, store form source as zCode
//		if (sessionStorage.getItem('zCode') !== null) {
//			$('#LeadGen input[name="source"]').val(sessionStorage.getItem('zCode'));
//		} else {
//			sessionStorage.setItem('zCode', $('#LeadGen input[name="source"]').val());
//		}

		$('#LeadGen').submit();
	}
</script>
</body>
</html>
