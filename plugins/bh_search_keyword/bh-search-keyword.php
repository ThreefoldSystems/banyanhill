<?php
/**
 * Plugin Name: Banyan Hill Search Keyword
 * Description: Function serach for keyword.
 * Version: 1.1
 * Author: Banyan Hill Web Team
 */

class BhSearchKeyWord
{

  public function __construct() {

    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this,  'bh_plugin_action_links' ));
    add_action( 'admin_menu', array($this, 'bh_register_settings' ));
  }

  public function bh_plugin_action_links( $links ) {
    $links[] = '<a href="'. menu_page_url( 'bh-search-keyword', false ) .'">Plugin</a>';
    return $links;
  }

  public function bh_register_settings() {
    add_options_page( 'BH Search Keyword', 'BH Search Keyword', 'manage_options', 'bh-search-keyword',  array($this, 'bh_plugin_settings_page' ));
  }

  public function bh_plugin_settings_page() {
    $search_results = '';
    if(isset($_GET['op'])) {
      if($_GET['op'] == 'eval'){
        $query = new WP_Query(
          array(
            's' => $_GET['search'],
            'posts_per_page'   => -1
          )
        );

        if ($query->have_posts()){
          while ( $query->have_posts() ) { $query->the_post();
            $search_results .= '<a href="'.get_permalink().'">' . get_permalink() . '</a><br>';
          } //end while
        }
      }
    }

    $content = '<section style="padding:10px;">';
    $content .= '<h3 style="border-bottom:1px solid #d3d3d3;padding-bottom:10px;">Search Keyword</h3>';
    $content .= '<form method="GET">';
    $content .= '<input type="hidden" name="page" value="bh-search-keyword" />';
    $content .= '<div style="display: flex;">';
    $content .= ' <div style="flex-basis: 15%;">Search:</div>';
    $content .= ' <div style="flex-basis: 20%;"><input type="text" name="search" style="width:99%" value=""/></div>';
    $content .= ' <div style=""></div>';
    $content .= '</div>';

    $content .= '<div style="display: flex;">';
    $content .= ' <div style="flex-basis: 15%;"></div>';
    $content .= ' <div style="flex-basis: 20%;"><button class="button button-primary" type="submit" name="op" value="eval" style="float:right;margin-top:10px;">Search</button></div>';
    $content .= ' <div style=""></div>';
    $content .= '</div>';
    $content .= '</form>';
    $content .= '</section>';

    $content .= $search_results;

    echo $content;

  }

  private function bh_debug_var($obj) {
    print('<pre>');
    var_dump($obj);
    print('</pre>');
    exit();
  }
}

$bh_search_keyword = new BhSearchKeyWord();
