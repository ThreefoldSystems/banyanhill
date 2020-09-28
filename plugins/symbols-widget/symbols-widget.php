<?php
/*
Plugin Name: Symbols Widget
Description: Symbols Widget.
Version: 0.1
Min WP Version: 3.0
Author: Andrei Shevel
*/
?>
<?php

add_action('widgets_init', function() {
    return register_widget("Symbols_Widget");
});

class Symbols_Widget extends WP_Widget
{

	function __construct() {
		$widget_ops = array('classname' => 'Symbols_Widget', 'description' => "Symbols_Widget." );
		parent::__construct('symbolswidget', __('Symbols Widget'), $widget_ops);
	}

	function form($instance) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-autocomplete');
		?>

		<script type="text/javascript">
		    jQuery(document).ready(function() {
		        function split( val ) {
		            return val.split( /,\s*/ );
		        }
		        function extractLast( term ) {
		            return split( term ).pop();
		        }

		        jQuery('input#<?php echo $this->get_field_id('title'); ?>').each(function() {
		            jQuery(this).bind( "keydown", function( oddt ) {
		                if ( oddt.keyCode === jQuery.ui.keyCode.TAB &&
		                        jQuery( this ).data( "autocomplete" ).menu.active ) {
		                    oddt.proddtDefault();
		                }
		            })
		            .autocomplete({
		                minLength: 0,
		                source: function( request, response ) {
		                    jQuery.ajax({
		                        type: "GET",
		                        url: "<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?term=" + extractLast( request.term ),
		                        dataType: "json",
		                        success: function (data) {
		                            response(
		                                    jQuery.map(data, function (item) {
		                                        return {
		                                            label: item.name,
		                                            value: item
		                                        }
		                                    }))
		                        }
		                    });
		                },
		                focus: function() {
		                    // proddt value inserted on focus
		                    return false;
		                },
		                select: function( oddt, ui ) {
		                    jQuery('#<?php echo $this->get_field_id('symbols'); ?>').append(
		                            '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
		                            '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name][]" value="'+ui.item.value.name+'">' +
		                            '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id][]" value="'+ui.item.value.symbol_id+'"></div>'
		                    );
		                    this.value = '';

		                    return false;
		                }
		            });
		        });
		    });
		</script>
		<p>
		    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Symbols:') ?></label>
		    <input type="text" style="width: 100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		    <div id="<?php echo $this->get_field_id('symbols'); ?>">
		        <? for ($i = 0; $i < count($instance['symbols']['name']); $i++) { ?>
		        <div>
		            <div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;<?= $instance['symbols']['name'][$i] ?></span></div>
		            <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name][]" value="<?= $instance['symbols']['name'][$i] ?>">
		            <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id][]" value="<?= $instance['symbols']['id'][$i] ?>">
		        </div>
		        <? } ?>
		    </div>
		</p>
		<?php
	}

	function update($new_instance, $old_instance) {
		for ($i = 0; $i < count($new_instance['symbols']['name']); $i++) {
		    $instance['symbols']['name'][] = strip_tags( stripslashes($new_instance['symbols']['name'][$i]) );
		    $instance['symbols']['id'][] = strip_tags( stripslashes($new_instance['symbols']['id'][$i]) );
		}
		return $instance;
	}

	// This prints the widget
	function widget( $args, $instance ) {
		extract($args);
		wp_enqueue_script('jquery');
		wp_head();

		echo $before_widget;
		echo $before_title . 'world' . $after_title;

        if ($instance !== null && count($instance['symbols']['name']) > 0) {
		?>

        <style type="text/css">
            .bold_text {
                font-weight: bold;
            }

            .symbols-item-link:hover {
                text-decoration: none!important;
                cursor: pointer;
            }
            .symbols-item-link .symbols-item{
                background-color: #0f881e;
                color: white;
                border-bottom: 1px dashed white;
            }
            .symbols-item-link .symbols-item .second {
                background-color: #109621;
            }
            .symbols-item-link .symbols-item td {
                text-align: right;
                vertical-align: middle;
                padding: 3px 5px;
                font-weight: bold;
            }

            .symbols-item-link:hover .symbols-item{
                background-color: #03ad18;
                color: white;
                border-bottom: 1px dashed white;
            }
            .symbols-item-link:hover .symbols-item .second {
                background-color: #06bc1c;
            }

            .symbols-item-link .symbols-item.bold_text{
                background-color: #ae0301;
                color: white;
                border-bottom: 1px dashed white;
            }
            .symbols-item-link .symbols-item.bold_text .second {
                background-color: #bf0604;
            }

            .symbols-item-link:hover .symbols-item.bold_text{
                background-color: #d60200;
                color: white;
                border-bottom: 1px dashed white;
            }
            .symbols-item-link:hover .symbols-item.bold_text .second {
                background-color: #e00604;
            }

        </style>

		<img id="symbol-chart" src="" style="width: 188px; height: 100px;">

		<?php
		for ($i = 0; $i < count($instance['symbols']['name']); $i++) {
		    $ch = curl_init();

		    curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetLatestData?symbolId=".$instance['symbols']['id'][$i]."&startDate=".date("m/d/Y", mktime(0, 0, 0, date("m"),date("d")-14,date("Y")))."&endDate=".date('m/d/Y'));
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		    $result = curl_exec($ch);
		    curl_close($ch);

		    $xml = new SimpleXMLElement($result);


		    ?>
            <a class="symbols-item-link">
                <div class="symbols-item <?= $i == 0 ? 'bold_text' : '' ?>" data-id="<?= $instance['symbols']['id'][$i] ?>">
                    <table>
                        <tr>
                            <td style="width: 100px;"><?= $instance['symbols']['name'][$i] ?></td>
                            <td class="second" style="width: 44px;"><?= $xml->LatestPrice ?></td>
                            <td style="width: 44px;"><?= $xml->Change ?> (<?= $xml->ChangePercent ?>)</td>
                        </tr>
                    </table>
                </div>
            </a>
		    <?php
		}
		?>

		<script type="text/javascript">
		    jQuery(document).ready(function() {
		        jQuery.ajax({
		            type: "GET",
		            url: '<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?symbol_id=<?= $instance['symbols']['id'][0] ?>',
		            success: function(data) {
		                jQuery('#symbol-chart').attr('src', data);
		            }
		        });


		        jQuery('.symbols-item').click(
		            function() {
		                jQuery('.symbols-item').removeClass('bold_text');
		                jQuery(this).addClass('bold_text');

		                var id = jQuery(this).attr('data-id');
		                jQuery.ajax({
		                    type: "GET",
		                    url: '<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?chart=true&symbol_id='+id,
		                    success: function(data) {
		                        jQuery('#symbol-chart').attr('src', data);
		                    }
		                });
		            }
		        );
		    });
		</script>

		<?php
		} else {
		    echo 'no items';
		}

		echo $after_widget;
	}
}

add_action('widgets_init', function() {
    return register_widget("SymbolsV_Widget");
});

class SymbolsV_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'SymbolsV_Widget', 'description' => "SymbolsV_Widget." );
        parent::__construct('symbolsvwidget', __('Symbols Vertical Widget'), $widget_ops);
    }

    function form($instance) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-autocomplete');
        ?>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('input#<?php echo $this->get_field_id('title'); ?>').each(function() {
                var that = this;
                jQuery(this).bind( "keydown", function( oddt ) {
                    if ( oddt.keyCode === jQuery.ui.keyCode.TAB &&
                            jQuery( this ).data( "autocomplete" ).menu.active ) {
                        oddt.proddtDefault();
                    }
                })
                        .autocomplete({
                            minLength: 0,
                            source: function( request, response ) {
                                jQuery.ajax({
                                    type: "GET",
                                    url: "<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?term1=" + request.term,
                                    dataType: "json",
                                    success: function (data) {
                                        response(
                                                jQuery.map(data, function (item) {
                                                    return {
                                                        label: item.name,
                                                        value: item
                                                    }
                                                }))
                                    }
                                });
                            },
                            focus: function() {
                                // proddt value inserted on focus
                                return false;
                            },
                            select: function( oddt, ui ) {
                                jQuery('#<?php echo $this->get_field_id('symbols'); ?>').html(
                                        '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                                '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name]" value="'+ui.item.value.name+'">' +
                                                '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id]" value="'+ui.item.value.symbol_id+'"></div>'
                                );
                                this.value = '';

                                return false;
                            }
                        });
            });
        });
    </script>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Symbols:') ?></label>
        <input type="text" style="width: 100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
    <div id="<?php echo $this->get_field_id('symbols'); ?>">
        <? if (isset($instance['symbols']['name']) && trim($instance['symbols']['name']) != '') { ?>
        <div>
            <div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;<?= $instance['symbols']['name'] ?></span></div>
            <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name]" value="<?= $instance['symbols']['name'] ?>">
            <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id]" value="<?= $instance['symbols']['id'] ?>">
        </div>
        <? } ?>
    </div>
    </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance['symbols']['name'] = strip_tags( stripslashes($new_instance['symbols']['name']) );
        $instance['symbols']['id'] = strip_tags( stripslashes($new_instance['symbols']['id']) );

        return $instance;
    }

    // This prints the widget
    function widget( $args, $instance ) {
        extract($args);
        wp_enqueue_script('jquery');
        wp_head();

        echo $before_widget;
        echo $before_title . 'SymbolsV' . $after_title;

        if (isset($instance['symbols']['name']) && trim($instance['symbols']['name']) != '') {
            ?>

        <style type="text/css">
            .bold_text {
                font-weight: bold;
            }
        </style>

        <?php
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetData?symbolId=".$instance['symbols']['id']."&startDate=".date("m/d/Y", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")))."&endDate=".date('m/d/Y')."&dataType=daily&column=");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $result = curl_exec($ch);
            curl_close($ch);

            $xml = new SimpleXMLElement($result);


            $value_1 = $xml->ArrayOfString->string[1].'';
            $value_2 = $xml->ArrayOfString->string[2].'';


            ?>
        <div class="symbolsV-item" data-id="<?= $instance['symbols']['id'] ?>" style="border: 1px solid #000000; padding: 5px;">
            <div><?= $instance['symbols']['name'] ?> - <?= $value_1 ?></div>

            <div style="border: 1px solid #000000; padding: 5px; margin-bottom: 5px;">
                <table style="width: 100%;">
                    <tr>
                        <td>Last Price:</td><td>$ <?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Bid:</td><td><?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Open:</td><td>$ <?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Last Trade:</td><td>$ <?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Market Cap:</td><td><?= $value_2 ?></td>
                    </tr>
                </table>
            </div>
            <div style="border: 1px solid #000000; padding: 5px;">
                <table style="width: 100%;">
                    <tr>
                        <td>Last Price:</td><td>$ <?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Bid:</td><td><?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Open:</td><td>$ <?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Last Trade:</td><td>$ <?= (int)$value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Market Cap:</td><td><?= $value_2 ?></td>
                    </tr>
                    <tr>
                        <td>Shares:</td><td>1M</td>
                    </tr>
                </table>
            </div>

        </div>
        <?php
            ?>

        <?php
        } else {
            echo 'no item';
        }

        echo $after_widget;
    }
}

add_action('widgets_init', function() {
    return register_widget("SymbolsC_Widget");
});

class SymbolsC_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'SymbolsC_Widget', 'description' => "SymbolsC_Widget." );
        parent::__construct('symbolscwidget', __('Symbols Compare Widget'), $widget_ops);
    }

    function form($instance) {
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-autocomplete');
        ?>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('input#<?php echo $this->get_field_id('title1'); ?>').each(function() {
                jQuery(this).bind( "keydown", function( oddt ) {
                    if ( oddt.keyCode === jQuery.ui.keyCode.TAB &&
                            jQuery( this ).data( "autocomplete" ).menu.active ) {
                        oddt.proddtDefault();
                    }
                })
                        .autocomplete({
                            minLength: 0,
                            source: function( request, response ) {
                                jQuery.ajax({
                                    type: "GET",
                                    url: "<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?term=" + request.term,
                                    dataType: "json",
                                    success: function (data) {
                                        response(
                                                jQuery.map(data, function (item) {
                                                    return {
                                                        label: item.name,
                                                        value: item
                                                    }
                                                }))
                                    }
                                });
                            },
                            focus: function() {
                                // proddt value inserted on focus
                                return false;
                            },
                            select: function( oddt, ui ) {
                                jQuery('#<?php echo $this->get_field_id('symbols1'); ?>').html(
                                        '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                                '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name1]" value="'+ui.item.value.name+'">' +
                                                '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id1]" value="'+ui.item.value.symbol_id+'"></div>'
                                );
                                this.value = '';

                                return false;
                            }
                        });
            });
        });

        jQuery(document).ready(function() {
            jQuery('input#<?php echo $this->get_field_id('title2'); ?>').each(function() {
                jQuery(this).bind( "keydown", function( oddt ) {
                    if ( oddt.keyCode === jQuery.ui.keyCode.TAB &&
                            jQuery( this ).data( "autocomplete" ).menu.active ) {
                        oddt.proddtDefault();
                    }
                })
                        .autocomplete({
                            minLength: 0,
                            source: function( request, response ) {
                                jQuery.ajax({
                                    type: "GET",
                                    url: "<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?term=" + request.term,
                                    dataType: "json",
                                    success: function (data) {
                                        response(
                                                jQuery.map(data, function (item) {
                                                    return {
                                                        label: item.name,
                                                        value: item
                                                    }
                                                }))
                                    }
                                });
                            },
                            focus: function() {
                                // proddt value inserted on focus
                                return false;
                            },
                            select: function( oddt, ui ) {
                                jQuery('#<?php echo $this->get_field_id('symbols2'); ?>').html(
                                        '<div><div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;'+ui.item.value.name+'</span></div>' +
                                                '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name2]" value="'+ui.item.value.name+'">' +
                                                '<input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id2]" value="'+ui.item.value.symbol_id+'"></div>'
                                );
                                this.value = '';

                                return false;
                            }
                        });
            });
        });

    </script>
    <p>
        <label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Symbols 1:') ?></label>
        <input type="text" style="width: 100%;" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>" />
        <div id="<?php echo $this->get_field_id('symbols1'); ?>">
            <? if (isset($instance['symbols']['name1']) && trim($instance['symbols']['name1']) != '') { ?>
            <div>
                <div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;<?= $instance['symbols']['name1'] ?></span></div>
                <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name1]" value="<?= $instance['symbols']['name1'] ?>">
                <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id1]" value="<?= $instance['symbols']['id1'] ?>">
            </div>
            <? } ?>
        </div>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('title2'); ?>"><?php _e('Symbols 2:') ?></label>
        <input type="text" style="width: 100%;" id="<?php echo $this->get_field_id('title2'); ?>" name="<?php echo $this->get_field_name('title2'); ?>" />
        <div id="<?php echo $this->get_field_id('symbols2'); ?>">
            <? if (isset($instance['symbols']['name2']) && trim($instance['symbols']['name2']) != '') { ?>
            <div>
                <div class="tagchecklist"><span><a class="ntdelbutton" id="post_tag-check-num-0" onclick="jQuery(this).parent().parent().parent().remove();">X</a>&nbsp;<?= $instance['symbols']['name2'] ?></span></div>
                <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[name2]" value="<?= $instance['symbols']['name2'] ?>">
                <input type="hidden" name="<?php echo $this->get_field_name('symbols'); ?>[id2]" value="<?= $instance['symbols']['id2'] ?>">
            </div>
            <? } ?>
        </div>
    </p>
    <?php
    }

    function update($new_instance, $old_instance) {
        $instance['symbols']['name1'] = strip_tags( stripslashes($new_instance['symbols']['name1']) );
        $instance['symbols']['id1'] = strip_tags( stripslashes($new_instance['symbols']['id1']) );
        $instance['symbols']['name2'] = strip_tags( stripslashes($new_instance['symbols']['name2']) );
        $instance['symbols']['id2'] = strip_tags( stripslashes($new_instance['symbols']['id2']) );

        return $instance;
    }

    // This prints the widget
    function widget( $args, $instance ) {
        extract($args);
        wp_enqueue_script('jquery');
        wp_head();

        echo $before_widget;
        echo $before_title . 'SymbolsC' . $after_title;

        if (isset($instance['symbols']['id1']) && isset($instance['symbols']['id2'])) {
            ?>
        <img id="symbol-compare-chart" src="" style="width: 188px; height: 100px;">
        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery.ajax({
                    type: "GET",
                    url: '<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?action=compare&width=188&symbol_1_id=<?= $instance['symbols']['id1'] ?>&symbol_2_id=<?= $instance['symbols']['id2'] ?>',
                    success: function(data) {
                        jQuery('#symbol-compare-chart').attr('src', data);
                    }
                });
            });
        </script>

        <?php
        } else {
            echo 'no items';
        }

        echo $after_widget;
    }
}

$instance_index = 0;

// JW 10/14/2019
// Displays an inline window that displays on ticker symbol hover
function get_symbol_widget() {
	if (isset($_POST['name'])) {
		
	$names = explode(',', $_POST['name']);		
    ob_start();

    //global $instance_index;
    $uid = md5($names[0]); //$instance_index++;

    $widget = new Symbols_Widget();
    $settings = $widget->get_settings();

    $instance = null;
    foreach ($settings as $options) {
        $instance = $options;
        break;
    }
	
?>
    <style type="text/css">
        #symbols-<?= $uid ?> {
            width: 255px; font-family: Arial; font-size: 11px; color: #333333;
        }
        #symbols-<?= $uid ?> .header {
            float: left; font-family: Georgia; font-size: 14px; color: #456626; line-height: 14px; padding-left: 15px;
        }
        #symbols-<?= $uid ?> .date {
            float: right; font-weight: bold; padding-right: 30px;
        }
        #symbols-<?= $uid ?> .chart {
            font-family: Georgia; font-size: 12px; color: #456626; width: 255px; height: 135px; line-height: 135px; text-align: center; position: relative;
        }
        #symbols-<?= $uid ?> ul {
            list-style: none; padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li {
            padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li:nth-child(odd) {
            background-color: #eeebe6;
        }
        #symbols-<?= $uid ?> .symbols-item {
            cursor: pointer;
        }
        #symbols-<?= $uid ?> .symbols-item {
            line-height: 18px;
        }
        #symbols-<?= $uid ?> .symbols-item .name {
            float: left; width: 100px; overflow: hidden; padding: 3px;
        }
        #symbols-<?= $uid ?> .symbols-item .price {
            float: left; width: 55px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item .percent {
            float: left; width: 80px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item.active {
            background-color: #113752; color: #ffffff;
        }
        #symbols-<?= $uid ?> .chart-container {
            width: 255px; height: 155px;
        }
        #symbols-<?= $uid ?> .chart-container img {
            width: 255px; height: 135px; max-width: none;
        }		
        .clear {
            clear: both;
        }
    </style>

    <?php

    $cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$names[0]);
    if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
        mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
    }

    $xml_file = null;
    $d = dir(dirname(__FILE__).'/temp/'.$cuid);
    while (false !== ($entry = $d->read())) {
        if ($entry[0] != '.') {
            $xml_file = $entry;
        }
    }
    $d->close();

    $valid = false;
    if (!is_null($xml_file)) {
        $timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
        if (time() - $timestamp < 5 * 60) {
	    $valid = true;
        }
    }

    if (!$valid) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$names[0]);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$result = curl_exec($ch);
		curl_close($ch);

		if ($result === false) {
			$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
			$xml = new SimpleXMLElement($result);
		} else {
			$xml = new SimpleXMLElement($result);

			if (!is_null($xml_file)) {
			unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
			}
			file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
		}
    } else {
		$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		$xml = new SimpleXMLElement($result);
    }
    ?>
    <div class="symbols symbols-1" id="symbols-<?= $uid ?>">
		<div style="padding-left: 14px; color: #636363;"><?php echo $xml->Exchange; ?>: <?php echo $names[0]; ?>
        	<div class="date"><?php echo (((int)date("H", strtotime($xml->LatestDate)) == 0) && ((int)date("i", strtotime($xml->LatestDate)) == 0)) ? date("M d", strtotime($xml->LatestDate)) : date("M d  h:i A", strtotime($xml->LatestDate)); ?></div>
		</div>
        <div class="chart-container">
            <?php
            for ($i = 0; $i < count($names); $i++) {
                if (trim($names[$i]) != '') {
                ?>
                <div class="chart chart-<?php echo $uid; ?>-<?php echo $i; ?>" <?php if ($i != 0) { ?>style="display: none;"<?php } ?>>
                    no chart
                </div>
                <?php
                }
            }
            ?>
        </div>
        <ul>
        <?php
        for ($i = 0; $i < count($names); $i++) {
            if (trim($names[$i]) != '') {
				$cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$names[$i]);
				if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
					mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
				}

				$xml_file = null;
				$d = dir(dirname(__FILE__).'/temp/'.$cuid);
				while (false !== ($entry = $d->read())) {
					if ($entry[0] != '.') {
					$xml_file = $entry;
					}
				}
				$d->close();

				$valid = false;
				if (!is_null($xml_file)) {
					$timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
					if (time() - $timestamp < 5 * 60) {
					$valid = true;
					}
				}

				if (!$valid) {
					$ch = curl_init();

					curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$names[$i]);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

					$result = curl_exec($ch);
					curl_close($ch);

					if ($result === false) {
					$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
					$xml = new SimpleXMLElement($result);
					} else {
					$xml = new SimpleXMLElement($result);

					if (!is_null($xml_file)) {
						unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
					}
					file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
					}
				} else {
					$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
					$xml = new SimpleXMLElement($result);
				}
				?>
			<li>
				<div class="symbols-item<?= $i == 0 ? ' active' : '' ?>" data-id="<?= $i ?>" data-name="<?= $names[$i] ?>" data-date="<?= (((int)date("H", strtotime($xml->LatestDate)) == 0) && ((int)date("i", strtotime($xml->LatestDate)) == 0)) ? date("M d", strtotime($xml->LatestDate)) : date("M d  h:i A", strtotime($xml->LatestDate)) ?>">
					<div class="name"><?php echo $names[$i]; ?></div>
					<div class="price"><?= number_format((float)$xml->LatestPrice, 2) ?></div>
					<div class="percent"><?= number_format((float)$xml->Change, 2) ?>&nbsp;(<?= number_format((float) $xml->ChangePercent, 1) ?>%)</div>
					<div style="clear: both;"></div>
				</div>
			</li>
		<?php
            }
        ?>
        </ul>
	</div>
	<script type="text/javascript">
		jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-<?php echo $i; ?>').html('<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDBweCIgIGhlaWdodD0iNDBweCIgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIiBjbGFzcz0ibGRzLWVjbGlwc2UiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsgYmFja2dyb3VuZDogbm9uZTsiPjxwYXRoIG5nLWF0dHItZD0ie3tjb25maWcucGF0aENtZH19IiBuZy1hdHRyLWZpbGw9Int7Y29uZmlnLmNvbG9yfX0iIHN0cm9rZT0ibm9uZSIgZD0iTTEwIDUwQTQwIDQwIDAgMCAwIDkwIDUwQTQwIDQzIDAgMCAxIDEwIDUwIiBmaWxsPSJyZ2JhKDAlLDAlLDAlLDAuNikiIHRyYW5zZm9ybT0icm90YXRlKDM2MCAtOC4xMDg3OGUtOCAtOC4xMDg3OGUtOCkiIGNsYXNzPSIiIHN0eWxlPSJhbmltYXRpb24tcGxheS1zdGF0ZTogcnVubmluZzsgYW5pbWF0aW9uLWRlbGF5OiAwczsiPjxhbmltYXRlVHJhbnNmb3JtIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSIgdHlwZT0icm90YXRlIiBjYWxjTW9kZT0ibGluZWFyIiB2YWx1ZXM9IjAgNTAgNTEuNTszNjAgNTAgNTEuNSIga2V5VGltZXM9IjA7MSIgZHVyPSIwLjVzIiBiZWdpbj0iMHMiIHJlcGVhdENvdW50PSJpbmRlZmluaXRlIiBjbGFzcz0iIiBzdHlsZT0iYW5pbWF0aW9uLXBsYXktc3RhdGU6IHJ1bm5pbmc7IGFuaW1hdGlvbi1kZWxheTogMHM7Ij48L2FuaW1hdGVUcmFuc2Zvcm0+PC9wYXRoPjwvc3ZnPg==" alt="Loading Chart" class="loading">');
		jQuery.ajax({
			type: "GET",
			url: '<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?width=255&symbolA=<?= $names[$i] ?>&period=1d',
			success: function(data) {
				if (jQuery.trim(data) != '') {
					jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-<?php echo $i; ?>').html('');
					jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-<?php echo $i; ?>').append('<img src="'+data+'" style="width: 255px; height: 135px;" />');
				}
			}
		});
	</script>
    <?php
		} // end for loop
    } else {
        echo 'no items';
    }

    echo trim( ob_get_clean() );
	
	die();
}

add_action( 'wp_ajax_get_symbol_widget', 'get_symbol_widget' );
add_action( 'wp_ajax_nopriv_get_symbol_widget', 'get_symbol_widget' );

function symbols_shortcode($atts, $content = null) {
    if (isset($atts['name'])) {
        $names = explode(',', $atts['name']);
		
		wp_enqueue_script( 'chart-display', plugin_dir_url( __FILE__ ) . 'js/chart-display.js', array( 'jquery' ), true );
		wp_enqueue_style( 'chart-display', plugin_dir_url( __FILE__ ) . 'css/chart-display.css' );
		
		return '<span class="chart-display" data-ticker="' . $atts['name'] . '">' . $atts['name'] . '<span class="rwc-container" data-symbol="' . $atts['name']. '"><span class="rwc-ticker-title"></span><span class="rwc-ticker-time"></span><span class="rwc-ticker-chart"></span><span class="rwc-ticker-footer"></span></span></span>';
	}
}

add_shortcode( 'rwc_multi_symbol', 'symbols_shortcode' );

function symbolsM_shortcode($atts, $content) {
    ob_start();

    global $instance_index;
    $uid = md5($instance_index); $instance_index++;

    $widget = new Symbols_Widget();
    $settings = $widget->get_settings();

    $instance = null;
    foreach ($settings as $options) {
        $instance = $options;
        break;
    }


    if (isset($atts['name'])) {
        $names = explode(',', $atts['name']);
        $titles = explode(',', $atts['title']);

        ?>

    <style type="text/css">
	#symbols-<?= $uid ?> table {
	    width: auto;
	}
        #symbols-<?= $uid ?> table, #symbols-<?= $uid ?> tr, #symbols-<?= $uid ?> td {
	    border: none 0; margin: 0; padding: 0; height: 26px;
        }
        #symbols-<?= $uid ?> td {
	    border-top: none 0 !important; padding: 0 !important; vertical-align: middle;
        }
        #symbols-<?= $uid ?> td:last {
	    border-right: 1px solid #cfcfcf;
        }
        #symbols-<?= $uid ?> td .container {
            padding: 0 10px; height: 10px; overflow: hidden; display: block;
        }
        #symbols-<?= $uid ?> td div {
	        float: left; line-height: 10px; color: #666; font-family: Tahoma; font-size: <?= (isset($atts['fontsize']) && is_numeric($atts['fontsize'])) ? $atts['fontsize'] : 10 ?>px;
        }
        #symbols-<?= $uid ?> td div.up {
            width: 12px; height: 9px; background: url('/wp-content/plugins/symbols-widget/img/arrows.png') 0 0 no-repeat;
        }
        #symbols-<?= $uid ?> td div.down {
            width: 12px; height: 9px; background: url('/wp-content/plugins/symbols-widget/img/arrows.png') -18 0 no-repeat;
        }
    </style>


<div id="symbols-<?= $uid ?>">

<table border="0">
<tr>
        <?php
        for ($i = 0; $i < count($names); $i++) {
            if (trim($names[$i]) != '') {

            
		$cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$names[$i]);
		if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
		    mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
		}

		$xml_file = null;
		$d = dir(dirname(__FILE__).'/temp/'.$cuid);
		while (false !== ($entry = $d->read())) {
		    if ($entry[0] != '.') {
			$xml_file = $entry;
		    }
		}
		$d->close();

		$valid = false;
		if (!is_null($xml_file)) {
		    $timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
		    if (time() - $timestamp < 5 * 60) {
			$valid = true;
		    }
		}

		if (!$valid) {
		    $ch = curl_init();

		    curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$names[$i]);
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		    $result = curl_exec($ch);
		    curl_close($ch);

		    if ($result === false) {
			$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
			$xml = new SimpleXMLElement($result);
		    } else {
			$xml = new SimpleXMLElement($result);
			
			if (!is_null($xml_file)) {
			    unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
			}
			file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
		    }
		} else {
		    $result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		    $xml = new SimpleXMLElement($result);
		}
            
            
                ?>
                
                <td>
		    <div class="container">
			<div style="font-weight: bold;"><?php echo (trim($titles[$i]) == '') ? $names[$i] : $titles[$i]; ?></div>
            <div style="line-height: 10px;">
                <?php if ((float)$xml->Change > 0) { ?>
                    <div class="up"></div>
                <?php } else if ((float)$xml->Change < 0) { ?>
                    <div class="down"></div>
                <?php } ?>
            </div>
			<div style="line-height: 10px;"><?= number_format((float)$xml->Change, 2) ?>&nbsp;(<?= number_format((float) $xml->ChangePercent, 2) ?>%)</div>
            <div>&nbsp;<?php echo number_format((float)$xml->LatestPrice, 2); ?></div>
		    </div>
                </td>
    <?php
  }
}
?>
<td style="width: 0px;"></td>
</tr>
</table>
</div>

<?php
    }

    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

add_shortcode( 'rwc_morning_data', 'symbolsM_shortcode' );

function symbolsV_shortcode($atts, $content) {
    ob_start();

    global $instance_index;
    $uid = md5($instance_index); $instance_index++;

    if (isset($atts['name'])) {
        $name = $atts['name'];

        ?>


    <style type="text/css">
        #symbols-<?= $uid ?> {
            width: 253px; font-family: Arial; font-size: 11px; color: #333333;
        }
        #symbols-<?= $uid ?> .header {
            float: left; font-family: Georgia; font-size: 14px; color: #456626; line-height: 10px; padding-left: 1px; font-weight: bold;
        }
        #symbols-<?= $uid ?> .date {
            float: right; font-weight: bold;
        }
        #symbols-<?= $uid ?> .chart {
            font-family: Georgia; font-size: 12px; color: #456626; width: 253px; height: 150px; line-height: 150px; text-align: center;
        }
        #symbols-<?= $uid ?> ul {
            list-style: none; padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li {
            padding: 0; margin: 0; width: 253px;
        }
        #symbols-<?= $uid ?> li:nth-child(odd) {
            background-color: #eeebe6;
        }
        #symbols-<?= $uid ?> .symbols-item {
            line-height: 18px;
        }
        #symbols-<?= $uid ?> .symbols-item .name {
            float: left; width: 100px; overflow: hidden; padding: 3px;
        }
        #symbols-<?= $uid ?> .symbols-item .price {
            float: left; width: 55px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item .percent {
            float: left; width: 80px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item .key {
            float: left; width: 151px; overflow: hidden; padding: 3px;
        }
        #symbols-<?= $uid ?> .symbols-item .value {
            float: left; width: 90px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item.active {
            background-color: #456626; color: #ffffff;
        }
        #symbols-<?= $uid ?> .chart-container {
            width: 253px; height: 150px;
        }


    </style>

    <?php
        if (trim($name) != '') {

        
	    $cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$name);
	    if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
		mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
	    }

	    $xml_file = null;
	    $d = dir(dirname(__FILE__).'/temp/'.$cuid);
	    while (false !== ($entry = $d->read())) {
		if ($entry[0] != '.') {
		    $xml_file = $entry;
		}
	    }
	    $d->close();

	    $valid = false;
	    if (!is_null($xml_file)) {
		$timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
		if (time() - $timestamp < 5 * 60) {
		    $valid = true;
		}
	    }

	    if (!$valid) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$name);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$result = curl_exec($ch);
		curl_close($ch);
		
		if ($result === false) {
		    $result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		    $xml = new SimpleXMLElement($result);
		} else {
		    $xml = new SimpleXMLElement($result);
		    
		    if (!is_null($xml_file)) {
			unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		    }
		    file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
		}
	    } else {
		$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		$xml = new SimpleXMLElement($result);
	    }
        
        
        
        
        

                ?>
                <div class="symbols" id="symbols-<?= $uid ?>">
                    <div class="header" style="font-weight: bold;">
                        <?php echo $xml->CompanyName; ?>
                    </div>
                    <div style="clear: both;"></div>

                    <div style="color: #636363; padding-left: 1px;">
                        <?php echo $xml->Exchange; ?>: <?= $name ?> - <?= number_format((float)$xml->LatestPrice, 2) ?>
                        <div class="date"><?php echo (((int)date("H", strtotime($xml->LatestDate)) == 0) && ((int)date("i", strtotime($xml->LatestDate)) == 0)) ? date("M d", strtotime($xml->LatestDate)) : date("M d  h:i A", strtotime($xml->LatestDate)); ?></div>
                    </div>

                    <div style="clear: both;"></div>
                    <ul>
                        <li>
                            <div class="symbols-item">
                                <div class="key">Open</div>
                                <div class="value"><?= number_format((float)$xml->Open, 2) ?></div>

                                <div style="clear: both;"></div>
                            </div>
                        </li>
                        <li>
                            <div class="symbols-item">
                                <div class="key">Day High</div>
                                <div class="value"><?= number_format((float)$xml->PeriodHigh, 2) ?></div>

                                <div style="clear: both;"></div>
                            </div>
                        </li>
                        <li>
                            <div class="symbols-item">
                                <div class="key">Day Low</div>
                                <div class="value"><?= number_format((float)$xml->PeriodLow, 2) ?></div>

                                <div style="clear: both;"></div>
                            </div>
                        </li>
                        <li>
                            <div class="symbols-item">
                                <div class="key">Volume</div>
                                <div class="value"><?= number_format((float)$xml->Volume) ?></div>

                                <div style="clear: both;"></div>
                            </div>
                        </li>
                        <li>
                            <div class="symbols-item">
                                <div class="key">Bid / Ask</div>
                                <div class="value"><?= number_format((float)$xml->Bid, 2) ?>&nbsp;/&nbsp;<?= number_format((float)$xml->Ask, 2) ?></div>

                                <div style="clear: both;"></div>
                            </div>
                        </li>
                        <li>
                            <div class="symbols-item">
                                <div class="key">Market Cap</div>
                                <div class="value"><?= number_format((float)$xml->MarketCap) ?></div>

                                <div style="clear: both;"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            <?php
        }
        ?>

    <?php
    } else {
        echo 'no item';
    }


    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

add_shortcode( 'rwc_info_only', 'symbolsV_shortcode' );

function symbolsC_shortcode($atts, $content) {
    ob_start();

    global $instance_index;
    $uid = md5($instance_index); $instance_index++;

    $widget = new Symbols_Widget();
    $settings = $widget->get_settings();

    $instance = null;
    foreach ($settings as $options) {
        $instance = $options;
        break;
    }


    if (isset($atts['name1'])) {
        $name1 = $atts['name1'];
        $name2 =  isset($atts['name2']) ? $atts['name2'] : '';
        ?>


    <style type="text/css">
        #symbols-<?= $uid ?> {
            width: 253px; font-family: Arial; font-size: 11px; color: #333333;
        }
        #symbols-<?= $uid ?> .header {
            float: left; font-family: Georgia; font-size: 14px; color: #456626; line-height: 14px; padding-left: 15px; font-weight: bold;
        }
        #symbols-<?= $uid ?> .date {
            float: right; font-weight: bold; padding-right: 30px;
        }
        #symbols-<?= $uid ?> .chart {
            font-family: Georgia; font-size: 12px; color: #456626; width: 253px; height: 135px; line-height: 135px; text-align: center;
        }
        #symbols-<?= $uid ?> ul {
            list-style: none; padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li {
            padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li:nth-child(odd) {
            background-color: #eeebe6;
        }
        #symbols-<?= $uid ?> .symbols-item .name {
            float: left; width: 100px; overflow: hidden; padding: 3px;
        }
        #symbols-<?= $uid ?> .symbols-item .price {
            float: left; width: 55px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item .percent {
            float: left; width: 80px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item.active {
            background-color: #456626; color: #ffffff;
        }
        #symbols-<?= $uid ?> .chart-container {
            width: 253px; height: 135px; padding: 0 0 5px 0;
        }
        #symbols-<?= $uid ?> .chart-container img {
            width: 253px; height: 135px; max-width: none;
        }

    </style>

    <?php

    $cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$name1);
    if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
	mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
    }

    $xml_file = null;
    $d = dir(dirname(__FILE__).'/temp/'.$cuid);
    while (false !== ($entry = $d->read())) {
	if ($entry[0] != '.') {
	    $xml_file = $entry;
	}
    }
    $d->close();

    $valid = false;
    if (!is_null($xml_file)) {
	$timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
	if (time() - $timestamp < 5 * 60) {
	    $valid = true;
	}
    }

    if (!$valid) {
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$name1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	$result = curl_exec($ch);
	curl_close($ch);

	if ($result === false) {
	    $result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
	    $xml = new SimpleXMLElement($result);
	} else {
	    $xml = new SimpleXMLElement($result);
	    
	    if (!is_null($xml_file)) {
		unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
	    }
	    file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
	}
    } else {
	$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
	$xml = new SimpleXMLElement($result);
    }

    ?>

    <div class="symbols" id="symbols-<?= $uid ?>">
        <div class="header"><?php echo $xml->CompanyName; ?></div>

        <div style="clear: both;"></div>

        <div style="padding-left: 15px; color: #636363;">
            <?php echo $xml->Exchange; ?>: <?= $name1 ?>
            <div class="date"><?php echo (((int)date("H", strtotime($xml->LatestDate)) == 0) && ((int)date("i", strtotime($xml->LatestDate)) == 0)) ? date("M d", strtotime($xml->LatestDate)) : date("M d  h:i A", strtotime($xml->LatestDate)); ?></div>
        </div>

        <div class="chart-container">
            <div class="chart chart-<?= $uid ?>">
                no chart
            </div>
        </div>

        Price: <?php echo number_format((float)$xml->LatestPrice, 2); ?> | Ch: <?= number_format((float)$xml->Change, 2) ?>&nbsp;(<?= number_format((float) $xml->ChangePercent, 1) ?>%)
        <?php

        $symbol = $name1;
        if (trim($name2) != '') {
            $symbol .= ','.$name2;
        }
        ?>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#symbols-<?= $uid ?> .chart-<?= $uid ?>').html('loading chart...');
            jQuery.ajax({
                type: "GET",
                url: '<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?width=253&symbol=<?= $symbol ?>',
                success: function(data) {
                    jQuery('#symbols-<?= $uid ?> .chart-<?= $uid ?>').html('');
                    jQuery('#symbols-<?= $uid ?> .chart-<?= $uid ?>').append('<img src="'+data+'" style="width: 253px; height: 135px;" />');
                }
            });


        });
    </script>

    <?php
    } else {
        echo 'no data';
    }


    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

add_shortcode( 'rwc_chart_compare', 'symbolsC_shortcode' );

function symbolsH_shortcode($atts, $content) {
    ob_start();

    global $instance_index;
    $uid = md5($instance_index); $instance_index++;

    if (isset($atts['name'])) {
        $name = $atts['name'];

        ?>


    <style type="text/css">
        #symbols-<?= $uid ?> {
            width: 253px; font-family: Arial; font-size: 11px; color: #333333;
        }
        #symbols-<?= $uid ?>.long {
            width: 560px;
        }
        #symbols-<?= $uid ?> .header {
            float: left; font-family: Georgia; font-size: 14px; color: #456626; line-height: 14px; padding-left: 14px; font-weight: bold;
        }
        #symbols-<?= $uid ?> .date {
            float: right; font-weight: bold;
        }
        #symbols-<?= $uid ?> .chart {
            font-family: Georgia; font-size: 12px; color: #456626; width: 253px; height: 138px; line-height: 138px; text-align: center;
        }
        #symbols-<?= $uid ?> ul {
            list-style: none; padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li {
            padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li:nth-child(odd) {
            background-color: #eeebe6;
        }
        #symbols-<?= $uid ?> .symbols-item .name {
            float: left; width: 100px; overflow: hidden; padding: 3px;
        }
        #symbols-<?= $uid ?> .symbols-item .price {
            float: left; width: 55px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item .percent {
            float: left; width: 80px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item.active {
            background-color: #456626; color: #ffffff;
        }
        #symbols-<?= $uid ?> .chart-container {
            width: 260px; height: 145px; padding: 0;
        }
        #symbols-<?= $uid ?> .chart-container img {
            width: 260px; height: 138px; max-width: none;
        }
        #symbols-<?= $uid ?>.long .chart-container {
            width: 260px; float: left;
        }
        #symbols-<?= $uid ?>.long .chart-container img {
            width: 260px; max-width: none;
        }
        #symbols-<?= $uid ?>.long li {
            width: 300px; height: 18px;
        }
        #symbols-<?= $uid ?>.long li div.left {
            width: 65px; float: left; overflow: hidden; margin-left: 10px; white-space: nowrap; line-height: 18px; height: 18px;
        }
        #symbols-<?= $uid ?>.long li div.right {
            text-align: right; margin-right: 10px; margin-left: 0px;
        }
        #symbols-<?= $uid ?>.long li div.clear {
            clear: both;
        }
        .period span {
            padding: 3px 10px; background-color: #eeebe6; cursor: pointer;
        }
        .period span.active {
            background-color: #456626; color: #ffffff;
        }
        .clear {
            clear: both;
        }
        #overlay {
            background-color: #000000;
            display: none;
            bottom: 0;
            left: 0;
            opacity: 0.6;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10090;
        }

        element.style {
            display: block;
            left: 0;
            top: 230.5px;
        }
        #lightbox {
            left: 0;
            line-height: 0;
            position: absolute;
            text-align: center;
            width: 100%;
            z-index: 10100;
        }
        #outerImageContainer {
            background-color: #FFFFFF;
            height: 250px;
            margin: 0 auto;
            position: relative;
            width: 250px;
        }
        #imageContainer {
            padding: 10px;
        }
        #jqlb_closelabel {
            background-image: url("<?= plugin_dir_url( __FILE__ ) ?>/img/closelabel.gif");
            background-position: center center;
            background-repeat: no-repeat;
            height: 22px;
        }
        .clearfix:after {
            clear: both;
            content: ".";
            display: block;
            height: 0;
            visibility: hidden;
        }
        #imageDataContainer {
            background-color: #FFFFFF;
            font: 10px/1.4em Verdana,Helvetica,sans-serif;
            margin: 0 auto;
        }
        #imageData {
            padding: 0 10px;
        }
        #imageData #imageDetails {
            float: left;
            text-align: left;
            width: 70%;
        }
        #imageData #bottomNavClose {
            float: right;
            padding-bottom: 0.7em;
            width: 66px;
        }
    </style>

    <?php
        if (trim($name) != '') {
            $period_array = array('1d', '5d', '1m', '3m', '1y', '5y', '10y');

            
            
	    $cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=".$period_array[0]."&symbol=".$name);
	    if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
		mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
	    }

	    $xml_file = null;
	    $d = dir(dirname(__FILE__).'/temp/'.$cuid);
	    while (false !== ($entry = $d->read())) {
		if ($entry[0] != '.') {
		    $xml_file = $entry;
		}
	    }
	    $d->close();

	    $valid = false;
	    if (!is_null($xml_file)) {
		$timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
		if (time() - $timestamp < 5 * 60) {
		    $valid = true;
		}
	    }

	    if (!$valid) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=".$period_array[0]."&symbol=".$name);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$result = curl_exec($ch);
		curl_close($ch);
		
		if ($result === false) {
		    $result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		    $xml = new SimpleXMLElement($result);
		} else {
		    $xml = new SimpleXMLElement($result);
		    
		    if (!is_null($xml_file)) {
			unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		    }
		    file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
		}
	    } else {
		$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
		$xml = new SimpleXMLElement($result);
	    }
            
                ?>

            <div class="symbols long" id="symbols-<?= $uid ?>">
                <div class="header"><?php echo $xml->CompanyName; ?></div>

                <div style="clear: both;"></div>
                <div style="padding-left: 14px; margin-bottom: 12px; color: #636363;">
                    <?php echo $xml->Exchange; ?>: <?= $name ?>
                    <div class="date"><?php echo (((int)date("H", strtotime($xml->LatestDate)) == 0) && ((int)date("i", strtotime($xml->LatestDate)) == 0)) ? date("M d", strtotime($xml->LatestDate)) : date("M d  h:i A", strtotime($xml->LatestDate)); ?></div>
                </div>

                <div class="chart-container" style="width: 260px; float: left;">
                    <?php
                    for ($i = 0; $i < 7; $i++) {
                        ?>
                        <div class="chart chart-<?php echo $uid; ?>-<?php echo $i; ?>" <?php if ($i != 0) { ?>style="display: none;"<?php } ?>>
                            no chart
                        </div>
                        <?php
                    }
                    ?>

                </div>
                <?php
                $n = (is_user_logged_in() || isset($_COOKIE['moneymorning_subscribe'])) ? 7 : 1;
                for ($i = 0; $i < $n; $i++) {

                
		    $cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=".$period_array[$i]."&symbol=".$name);
		    if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
			mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
		    }

		    $xml_file = null;
		    $d = dir(dirname(__FILE__).'/temp/'.$cuid);
		    while (false !== ($entry = $d->read())) {
			if ($entry[0] != '.') {
			    $xml_file = $entry;
			}
		    }
		    $d->close();

		    $valid = false;
		    if (!is_null($xml_file)) {
			$timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
			if (time() - $timestamp < 5 * 60) {
			    $valid = true;
			}
		    }

		    if (!$valid) {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=".$period_array[$i]."&symbol=".$name);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

			$result = curl_exec($ch);
			curl_close($ch);

			if ($result === false) {
			    $result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
			    $xml = new SimpleXMLElement($result);
			} else {
			    $xml = new SimpleXMLElement($result);
			    
			    if (!is_null($xml_file)) {
				unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
			    }
			    file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
			}
		    } else {
			$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
			$xml = new SimpleXMLElement($result);
		    }

                ?>
                <div style="width: 300px; float: left;<?php if ($i != 0) { ?> display: none;<?php } ?>" class="data data-<?php echo $i; ?>" data-date="<?php echo (((int)date("H", strtotime($xml->LatestDate)) == 0) && ((int)date("i", strtotime($xml->LatestDate)) == 0)) ? date("M d", strtotime($xml->LatestDate)) : date("M d  h:i A", strtotime($xml->LatestDate)); ?>">
                    <ul>
                        <li>
                            <div class="left">Last price</div><div class="left right"><?php echo number_format((float)$xml->LatestPrice, 2); ?></div>
                            <div class="left">Prev Close</div><div class="left right"><?php echo number_format((float)$xml->PreviousPrice, 2); ?></div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="left">Change</div><div class="left right"><?php echo number_format((float)$xml->Change, 2);?></div>
                            <div class="left">% Change</div><div class="left right"><?php echo $xml->ChangePercent == 'NaN' ? 'NaN' : number_format((float)$xml->ChangePercent, 1); ?>%</div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="left">Open</div><div class="left right"><?php echo number_format((float)$xml->Open, 2);?></div>
                            <div class="left">Volume</div><div class="left right"><?php echo number_format((float)$xml->Volume);?></div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="left">Day Low</div><div class="left right"><?php echo $xml->PeriodLow == 'NaN' ? 'NaN' : number_format((float)$xml->PeriodLow, 2); ?></div>
                            <div class="left">Day High</div><div class="left right"><?php echo $xml->PeriodHigh == 'NaN' ? 'NaN' : number_format((float)$xml->PeriodHigh, 2); ?></div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="left">Bid</div><div class="left right"><?php echo number_format((float)$xml->Bid, 2);?></div>
                            <div class="left">Ask</div><div class="left right"><?php echo number_format((float)$xml->Ask, 2);?></div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="left">52 Wk Low</div><div class="left right"><?php echo $xml->YearLow == 'NaN' ? 'NaN' : number_format((float)$xml->YearLow, 2); ?></div>
                            <div class="left">52 Wk High</div><div class="left right"><?php echo $xml->YearHigh == 'NaN' ? 'NaN' : number_format((float)$xml->YearHigh, 2); ?></div>
                            <div class="clear"></div>
                        </li>
                        <li>
                            <div class="left">Market Cap</div><div class="left right"><?php echo number_format((float)$xml->MarketCap); ?></div>
                            <div class="left">Exchange</div><div class="left right"><?php echo $xml->Exchange;?></div>
                            <div class="clear"></div>
                        </li>
                    </ul>
                </div>
                <?php } ?>

                <div style="clear: both;"></div>

                <div class="period">
                    <span class="active" data-id="0">Today</span>
                    <span data-id="1">5d</span>
                    <span data-id="2">1m</span>
                    <span data-id="3">3m</span>
                    <span data-id="4">1y</span>
                    <span data-id="5">5y</span>
                    <span data-id="6">10y</span>
                </div>

            </div>

            <script type="text/javascript">
                jQuery(document).ready(function() {
                    <?php
                    $n = (is_user_logged_in() || isset($_COOKIE['moneymorning_subscribe'])) ? 7 : 1;
                    for ($i = 0; $i < $n; $i++) {
                        ?>
                        jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-<?php echo $i; ?>').html('loading chart...');
                        jQuery.ajax({
                            type: "GET",
                            url: '<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?width=253&symbolA=<?= $name ?>&period=<?php echo $period_array[$i]; ?>',
                            success: function(data) {
                                if (jQuery.trim(data) != '') {
                                    jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-<?php echo $i; ?>').html('');
                                    jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-<?php echo $i; ?>').append('<img src="'+data+'" style="width: 253px; height: 135px;" />');
                                } else {
                                    jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-<?php echo $i; ?>').html('no chart');
                                }
                            }
                        });
                        <?php
                    }
                    ?>

                    jQuery('#symbols-<?= $uid ?> .period span').click(
                            function() {
                                <?php if (is_user_logged_in() || isset($_COOKIE['moneymorning_subscribe'])) { ?>
                                var i = jQuery(this).attr('data-id');

                                jQuery('#symbols-<?= $uid ?> .period span').removeClass('active');
                                jQuery(this).addClass('active');

                                jQuery('#symbols-<?= $uid ?> .date').text(
                                    jQuery('#symbols-<?= $uid ?> .data.data-'+i).attr('data-date')
                                );


                                jQuery('#symbols-<?= $uid ?> .chart').hide();
                                jQuery('#symbols-<?= $uid ?> .data').hide();
                                jQuery('#symbols-<?= $uid ?> .chart-<?php echo $uid; ?>-'+i).show();
                                jQuery('#symbols-<?= $uid ?> .data-'+i).show();
                                <?php } else { ?>
                                jQuery('#ads-iframe').attr('src', '<?= plugin_dir_url( __FILE__ ) ?>ajax/ads.html');
                                jQuery('#ads').show();
                                <?php } ?>
                            }
                    );

                    <?php if (!is_user_logged_in() && !isset($_COOKIE['moneymorning_subscribe'])) { ?>
                    jQuery('body').append(
                    '<div id="ads" style="display: none;">' +
                    '                        <div id="overlay" style="width: 1907px; height: 1883px; opacity: 0.8; display: block;"></div>' +
                    '   <div id="lightbox" style="display: block; width: 700px; height: 400px; position: fixed; top: 50%; margin-top: -200px; left: 50%; margin-left: -350px;">' +
                    '   <div id="outerImageContainer" style="width: 700px; height: 400px;">' +
                    '   <div id="imageContainer">' +
                    '   <iframe id="ads-iframe" src="<?= plugin_dir_url( __FILE__ ) ?>ajax/ads.html" style="border: 0 none; width: 650px; height: 350px;"></iframe>' +
                    '   </div>' +
                    '   <div class="clearfix" id="imageDataContainer" style="width: 700px;">' +
                    '   <div id="imageData">' +
                    '   <div id="imageDetails">' +
                    '   <span id="caption" style="display: inline;"></span>' +
                    '   <p id="controls"><span id="numberDisplay"></span>' +
                    '   <span id="downloadLink" style="display: none;">' +
                    '   <a target="_self" href="">Download</a>' +
                    '   </span>' +
                    '   </p>' +
                    '   </div>' +
                    '   <div id="bottomNav">' +
                    '   <a title="close image gallery" id="bottomNavClose" href="javascript://">' +
                    '   <div id="jqlb_closelabel"></div>' +
                    '   </a>' +
                    '   </div>' +
                    '   </div>' +
                    '   </div>' +
                    '   </div>' +
                    '   </div>' +
                    '   </div>'
                    );
                    jQuery('#overlay').click(function() {
                        jQuery('#ads').hide();
                    });
                    jQuery('#lightbox').click(function() {
                        jQuery('#ads').hide();
                    });
                    jQuery('#outerImageContainer').click(function(event) {
                        event.stopPropagation();
                        return false;
                    });
                    jQuery('#jqlb_closelabel').click(function() {
                        jQuery('#ads').hide();
                    });

                    window.setInterval(function(){
                        if (getCookie('isReload') == null && getCookie('moneymorning_subscribe') != null) {
                            createCookie('isReload', true, 1);
                            location.reload();
                        }
                    }, 1000);

                    function createCookie(name, value, days) {
                        if (days) {
                            var date = new Date();
                            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                            var expires = "; expires=" + date.toGMTString();
                        }
                        else var expires = "";
                        document.cookie = name + "=" + value + expires + "; path=/";
                    }

                    function getCookie(name) {
                        var dc = document.cookie;
                        var prefix = name + "=";
                        var begin = dc.indexOf("; " + prefix);
                        if (begin == -1) {
                            begin = dc.indexOf(prefix);
                            if (begin != 0) return null;
                        }
                        else
                        {
                            begin += 2;
                            var end = document.cookie.indexOf(";", begin);
                            if (end == -1) {
                                end = dc.length;
                            }
                        }
                        return unescape(dc.substring(begin + prefix.length, end));
                    }



                    <?php } ?>
                });
            </script>



            <?php

        }
        ?>

    <?php
    } else {
        echo 'no item';
    }


    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

add_shortcode( 'rwc_horizontal', 'symbolsH_shortcode' );

function symbolsS_shortcode($atts, $content) {
    ob_start();

    global $instance_index;
    $uid = md5($instance_index); $instance_index++;

    $widget = new Symbols_Widget();
    $settings = $widget->get_settings();

    $instance = null;
    foreach ($settings as $options) {
        $instance = $options;
        break;
    }


    if (isset($atts['name'])) {
        $name1 = $atts['name'];
        ?>


    <style type="text/css">
        #symbols-<?= $uid ?> {
            width: 253px; font-family: Arial; font-size: 11px; color: #333333;
        }
        #symbols-<?= $uid ?> .header {
            float: left; font-family: Georgia; font-size: 14px; color: #456626; line-height: 14px; padding-left: 14px; font-weight: bold;
        }
        #symbols-<?= $uid ?> .date {
            float: right; padding-right: 32px; font-weight: bold;
        }
        #symbols-<?= $uid ?> .chart {
            font-family: Georgia; font-size: 12px; color: #456626; width: 253px; height: 135px; line-height: 135px; text-align: center;
        }
        #symbols-<?= $uid ?> ul {
            list-style: none; padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li {
            padding: 0; margin: 0;
        }
        #symbols-<?= $uid ?> li:nth-child(odd) {
            background-color: #eeebe6;
        }
        #symbols-<?= $uid ?> .symbols-item .name {
            float: left; width: 100px; overflow: hidden; padding: 3px;
        }
        #symbols-<?= $uid ?> .symbols-item .price {
            float: left; width: 55px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item .percent {
            float: left; width: 80px; overflow: hidden; padding: 3px; text-align: right;
        }
        #symbols-<?= $uid ?> .symbols-item.active {
            background-color: #456626; color: #ffffff;
        }
        #symbols-<?= $uid ?> .chart-container {
            width: 253px; height: 135px; padding: 0 0 5px 0;
        }
        #symbols-<?= $uid ?> .chart-container img {
            width: 253px; height: 135px; max-width: none;
        }
        .clear {
            clear: both;
        }

    </style>

    <?php
    
    $cuid = md5("http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$name1);
    if (!is_dir(dirname(__FILE__).'/temp/'.$cuid)) {
	    mkdir(dirname(__FILE__).'/temp/'.$cuid, 0755);
    }

    $xml_file = null;
    $d = dir(dirname(__FILE__).'/temp/'.$cuid);
    while (false !== ($entry = $d->read())) {
        if ($entry[0] != '.') {
            $xml_file = $entry;
        }
    }
    $d->close();

    $valid = false;
    if (!is_null($xml_file)) {
        $timestamp = substr($xml_file, 0, strpos($xml_file, '.'));
        if (time() - $timestamp < 5 * 60) {
            $valid = true;
        }
    }

    if (!$valid) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://api.rightwaytrader.com/v.0.39.1/historicaldata.asmx/GetSymbolData?periodType=&symbol=".$name1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            $result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
            $xml = new SimpleXMLElement($result);
        } else {
            $xml = new SimpleXMLElement($result);

            if (!is_null($xml_file)) {
                unlink(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
            }
            file_put_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.time().'.xml', $result);
        }
    } else {
	$result = file_get_contents(dirname(__FILE__).'/temp/'.$cuid.'/'.$xml_file);
	$xml = new SimpleXMLElement($result);
    }

    ?>


    <div class="symbols" id="symbols-<?= $uid ?>">
        <div class="header"><?php echo $xml->CompanyName; ?></div>

        <div class="clear"></div>

        <div style="padding-left: 14px; color: #636363;">
            <?php echo $xml->Exchange; ?>: <?= $name1 ?>
            <div class="date"><?php echo (((int)date("H", strtotime($xml->LatestDate)) == 0) && ((int)date("i", strtotime($xml->LatestDate)) == 0)) ? date("M d", strtotime($xml->LatestDate)) : date("M d  h:i A", strtotime($xml->LatestDate)); ?></div>
        </div>

        <div class="chart-container">
            <div class="chart chart-<?= $uid ?>">
                no chart
            </div>
        </div>
        Price: <?php echo number_format((float)$xml->LatestPrice, 2); ?> | Ch: <?= number_format((float)$xml->Change, 2) ?>&nbsp;(<?= number_format((float) $xml->ChangePercent, 1) ?>%)
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#symbols-<?= $uid ?> .chart-<?= $uid ?>').html('loading chart...');
            jQuery.ajax({
                type: "GET",
                url: '<?= plugin_dir_url( __FILE__ ) ?>ajax/as-symbols.php?width=253&symbolA=<?= $name1 ?>&period=1d',
                success: function(data) {
                    jQuery('#symbols-<?= $uid ?> .chart-<?= $uid ?>').html('');
                    jQuery('#symbols-<?= $uid ?> .chart-<?= $uid ?>').append('<img src="'+data+'" style="width: 253px; height: 135px;" />');
                }
            });


        });
    </script>

    <?php
    } else {
        echo 'no data';
    }


    $out = ob_get_contents();
    ob_end_clean();

    return $out;
}

add_shortcode( 'rwc_chart_single', 'symbolsS_shortcode' );


function register_symbols_plugin( $buttons ) {
//    array_push( $buttons, "|", "symbolsS" );
//    array_push( $buttons, "|", "symbolsH" );
    array_push( $buttons, "symbols" );
//    array_push( $buttons, "|", "symbolsC" );
//    array_push( $buttons, "|", "symbolsV" );
//    array_push( $buttons, "|", "symbolsM" );

    return $buttons;
}

function add_symbols_plugin( $plugin_array ) {
//    $plugin_array['symbolsS'] = plugin_dir_url( __FILE__ ) . '/js/symbolsS.js';
//    $plugin_array['symbolsH'] = plugin_dir_url( __FILE__ ) . '/js/symbolsH.js';
    $plugin_array['symbols'] = plugin_dir_url( __FILE__ ) . 'js/symbols.js';
//    $plugin_array['symbolsC'] = plugin_dir_url( __FILE__ ) . '/js/symbolsC.js';
//    $plugin_array['symbolsV'] = plugin_dir_url( __FILE__ ) . '/js/symbolsV.js';
//    $plugin_array['symbolsM'] = plugin_dir_url( __FILE__ ) . '/js/symbolsM.js';

    return $plugin_array;
}

function as_symbolsS_button() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-autocomplete');

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    if ( get_user_option('rich_editing') == 'true' ) {
        add_filter( 'mce_external_plugins', 'add_symbols_plugin' );
        add_filter( 'mce_buttons', 'register_symbols_plugin' );
    }

}

add_action('init', 'as_symbolsS_button');

function as_symbolsH_button() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-autocomplete');

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    if ( get_user_option('rich_editing') == 'true' ) {
        add_filter( 'mce_external_plugins', 'add_symbols_plugin' );
        add_filter( 'mce_buttons', 'register_symbols_plugin' );
    }

}

add_action('init', 'as_symbolsH_button');

function as_symbols_button() {
//    wp_enqueue_script('jquery');
//    wp_enqueue_script('jquery-ui-autocomplete');
	
   if ( current_user_can('edit_posts') && current_user_can('edit_pages') ) {
		if ( in_array(basename($_SERVER['PHP_SELF']), array('post-new.php', 'page-new.php', 'post.php', 'page.php') ) ) {	
			add_filter( 'mce_buttons', 'register_symbols_plugin' );
			add_filter( 'mce_external_plugins', 'add_symbols_plugin' );
		}
   }

}

add_action('admin_init', 'as_symbols_button');

function as_symbolsC_button() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-autocomplete');

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    if ( get_user_option('rich_editing') == 'true' ) {
        add_filter( 'mce_external_plugins', 'add_symbols_plugin' );
        add_filter( 'mce_buttons', 'register_symbols_plugin' );
    }

}

add_action('init', 'as_symbolsC_button');

function as_symbolsV_button() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-autocomplete');

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    if ( get_user_option('rich_editing') == 'true' ) {
        add_filter( 'mce_external_plugins', 'add_symbols_plugin' );
        add_filter( 'mce_buttons', 'register_symbols_plugin' );
    }

}

add_action('init', 'as_symbolsV_button');

function as_symbolsM_button() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-autocomplete');

    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
        return;
    }

    if ( get_user_option('rich_editing') == 'true' ) {
        add_filter( 'mce_external_plugins', 'add_symbols_plugin' );
        add_filter( 'mce_buttons', 'register_symbols_plugin' );
    }

}

add_action('init', 'as_symbolsM_button');

?>
