<?php
/**
 * Class TFS_Export_Posts
 *
 * Post export by date
 */
class TFS_Export_Posts
{
    public function __construct()
    {
        // Add menu item for admin page
        add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );

        // Do export posts if exporting
        if ( isset( $_GET['page'] ) && $_GET['page'] == 'tfs_ra_export' && isset( $_GET['download'] ) ) {
            add_action( 'init', array( $this, 'do_post_export' ) );
        }
    }

    /**
     * Add menu item for admin page.
     *
     * @return void
     */
    public function admin_menu_item()
    {
        add_submenu_page(
            'tfs_ra_export_import',
            'Post Export by date',
            'Post Export by date',
            'manage_options',
            'tfs_ra_export',
            array( $this, 'post_export_page' )
        );
    }

    /**
     * Export post page
     */
    public function post_export_page()
    {
        global $wp_locale;

        $months = "";

        for ( $i = 1; $i < 13; $i++ ) {
            $months .= "\t\t\t<option value=\"" . zeroise($i, 2) . '">' .
                $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) . "</option>\n";
        }
        ?>
        <div class="wrap">
            <h2>Post Export by date</h2>

            <p>When you click the button below WordPress will create an XML file for you to save to your computer.</p>
            <p>This format, which we call WordPress eXtended RSS or WXR, will contain your posts, pages, comments, custom fields, categories, and tags.</p>
            <p>Once you have saved the download file, you can use the Import function on another WordPress blog to import this blog.</p>

            <form action="" method="get">
                <input type="hidden" name="page" value="tfs_ra_export" />
                <h3>Options</h3>

                <table class="form-table">
                    <tr>
                        <th><label for="mm_start">Date Range</label></th>

                        <td><strong>Start:</strong> Month&nbsp;
                            <select name="mm_start" id="mm_start">
                                <option value="all" selected="selected">All Dates</option>
                                <?php echo $months; ?>
                            </select>&nbsp;Year&nbsp;
                            <input type="text" id="aa_start" name="aa_start" value="" size="4" maxlength="5" />
                        </td>

                        <td><strong>End:</strong> Month&nbsp;
                            <select name="mm_end" id="mm_end">
                                <option value="all" selected="selected">All Dates</option>
                                <?php echo $months; ?>
                            </select>&nbsp;Year&nbsp;
                            <input type="text" id="aa_end" name="aa_end" value="" size="4" maxlength="5" />
                        </td>
                    </tr>

                    <tr>
                        <th><label for="post_type">Post Types</label></th>
                        <td>
                            <?php
                            foreach ( get_post_types() as $post_type ) {
                                ?>
                                <input id="cpt_<?php echo $post_type;?>" type="checkbox" name="post_type[]" value="<?php echo $post_type; ?>"> <label name="cpt_<?php echo $post_type;?>" id="cpt_<?php echo $post_type;?>"><?php echo $post_type; ?></label><br />
                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                </table>

                <p class="submit"><input type="submit" name="submit" class="button" value="Download Export File" />
                    <input type="hidden" name="download" value="true" />
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * Do post export
     */
    public function do_post_export()
    {
        global $wpdb, $post_ids, $post;

        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : 'all';
        $mm_start = isset($_GET['mm_start']) ? $_GET['mm_start'] : 'all';
        $mm_end = isset($_GET['mm_end']) ? $_GET['mm_end'] : 'all';
        $aa_start = isset($_GET['aa_start']) ? intval($_GET['aa_start']) : 0;
        $aa_end = isset($_GET['aa_end']) ? intval($_GET['aa_end']) : 0;

        if($mm_start != 'all' && $aa_start > 0) {
            $start_date = sprintf( "%04d-%02d-%02d", $aa_start, $mm_start, 1 );
        } else {
            $start_date = 'all';
        }

        if($mm_end != 'all' && $aa_end > 0) {
            if($mm_end == 12) {
                $mm_end = 1;
                $aa_end++;
            } else {
                $mm_end++;
            }
            $end_date = sprintf( "%04d-%02d-%02d", $aa_end, $mm_end, 1 );
        } else {
            $end_date = 'all';
        }

        $this->post_export_setup();

        define('WXR_VERSION', '1.0');

        do_action('export_wp');

        if ( strlen( $start_date ) > 4 && strlen( $end_date ) > 4) {
            $filename = 'wordpress.' . $start_date . '.' . $end_date . '.xml';
        } else {
            $filename = 'wordpress.' . date('Y-m-d') . '.xml';
        }

        header('Content-Description: File Transfer');
        header("Content-Disposition: attachment; filename=$filename");
        header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

        if ( ! empty( $post_type ) && is_array( $post_type ) ) {
            $post_types_use = $post_type;
        } else {
            $post_types_use = array( 'post' );
        }

        $how_many = count( $post_types_use );
        $placeholders = array_fill( 0, $how_many, '%s' );

        $format = implode( ', ', $placeholders );

        $cpt_query = " WHERE post_type IN($format)";

        $where = $wpdb->prepare($cpt_query, $post_types_use) ;

        if ( $start_date and $start_date != 'all' ) {
            $where .= $wpdb->prepare("AND post_date >= %s ", $start_date);
        }
        if ( $end_date and $end_date != 'all' ) {
            $where .= $wpdb->prepare("AND post_date < %s ", $end_date);
        }

        // grab a snapshot of post IDs, just in case it changes during the export
        $post_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts $where ORDER BY post_date_gmt ASC");

        echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . '"?' . ">\n";
        ?>
        <?php the_generator('export');?>
        <rss version="2.0"
             xmlns:excerpt="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/excerpt/"
             xmlns:content="http://purl.org/rss/1.0/modules/content/"
             xmlns:wfw="http://wellformedweb.org/CommentAPI/"
             xmlns:dc="http://purl.org/dc/elements/1.1/"
             xmlns:wp="http://wordpress.org/export/<?php echo WXR_VERSION; ?>/">

            <channel>
                <title><?php bloginfo_rss('name'); ?></title>
                <link><?php bloginfo_rss('url') ?></link>
                <description><?php bloginfo_rss("description") ?></description>
                <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
                <generator>http://wordpress.org/?v=<?php bloginfo_rss('version'); ?></generator>
                <language><?php echo get_option('rss_language'); ?></language>
                <wp:wxr_version><?php echo WXR_VERSION; ?></wp:wxr_version>
                <wp:base_site_url><?php echo wxr_site_url(); ?></wp:base_site_url>
                <wp:base_blog_url><?php bloginfo_rss('url'); ?></wp:base_blog_url>
                <?php do_action('rss2_head'); ?>

                <?php if ($post_ids) {
                    // Get attachment post IDs for each post
                    foreach ( $post_ids as $postID ) {
                        $args = array(
                            'post_parent' => $postID,
                            'post_type'   => 'attachment',
                            'numberposts' => -1,
                            'post_status' => 'inherit'
                        );

                        $children = get_children( $args );

                        if ( $children ) {
                            foreach( $children as $child ) {
                                array_push( $post_ids, $child->ID );
                            }
                        }
                    }

                    // Ensure post IDs in array are unique
                    $post_ids = array_unique( $post_ids );

                    global $wp_query;
                    $wp_query->in_the_loop = true;  // Fake being in the loop.
                    // fetch 20 posts at a time rather than loading the entire table into memory
                    while ( $next_posts = array_splice($post_ids, 0, 20) ) {
                        $where = "WHERE ID IN (".join(',', $next_posts).")";
                        $posts = $wpdb->get_results("SELECT * FROM $wpdb->posts $where ORDER BY post_date_gmt ASC");
                        foreach ($posts as $post) {
                            setup_postdata($post); ?>
                            <item>
                                <title><?php echo apply_filters('the_title_rss', $post->post_title); ?></title>
                                <link><?php the_permalink_rss() ?></link>
                                <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                                <dc:creator><?php echo wxr_cdata(get_the_author()); ?></dc:creator>
                                <?php wxr_post_taxonomy() ?>

                                <guid isPermaLink="false"><?php the_guid(); ?></guid>
                                <description></description>
                                <content:encoded><?php echo wxr_cdata( apply_filters('the_content_export', $post->post_content) ); ?></content:encoded>
                                <excerpt:encoded><?php echo wxr_cdata( apply_filters('the_excerpt_export', $post->post_excerpt) ); ?></excerpt:encoded>
                                <wp:post_id><?php echo $post->ID; ?></wp:post_id>
                                <wp:post_date><?php echo $post->post_date; ?></wp:post_date>
                                <wp:post_date_gmt><?php echo $post->post_date_gmt; ?></wp:post_date_gmt>
                                <wp:comment_status><?php echo $post->comment_status; ?></wp:comment_status>
                                <wp:ping_status><?php echo $post->ping_status; ?></wp:ping_status>
                                <wp:post_name><?php echo $post->post_name; ?></wp:post_name>
                                <wp:status><?php echo $post->post_status; ?></wp:status>
                                <wp:post_parent><?php echo $post->post_parent; ?></wp:post_parent>
                                <wp:menu_order><?php echo $post->menu_order; ?></wp:menu_order>
                                <wp:post_type><?php echo $post->post_type; ?></wp:post_type>
                                <wp:post_password><?php echo $post->post_password; ?></wp:post_password>
                                <?php
                                if ($post->post_type == 'attachment') { ?>
                                    <wp:attachment_url><?php echo wp_get_attachment_url($post->ID); ?></wp:attachment_url>
                                <?php } ?>
                                <?php
                                $postmeta = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID) );
                                if ( $postmeta ) {
                                    ?>
                                    <?php foreach( $postmeta as $meta ) { ?>
                                        <wp:postmeta>
                                            <wp:meta_key><?php echo $meta->meta_key; ?></wp:meta_key>
                                            <wp:meta_value><?Php echo $meta->meta_value; ?></wp:meta_value>
                                        </wp:postmeta>
                                    <?php } ?>
                                <?php } ?>
                                <?php
                                $comments = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d", $post->ID) );
                                if ( $comments ) { foreach ( $comments as $c ) { ?>
                                    <wp:comment>
                                        <wp:comment_id><?php echo $c->comment_ID; ?></wp:comment_id>
                                        <wp:comment_author><?php echo wxr_cdata($c->comment_author); ?></wp:comment_author>
                                        <wp:comment_author_email><?php echo $c->comment_author_email; ?></wp:comment_author_email>
                                        <wp:comment_author_url><?php echo $c->comment_author_url; ?></wp:comment_author_url>
                                        <wp:comment_author_IP><?php echo $c->comment_author_IP; ?></wp:comment_author_IP>
                                        <wp:comment_date><?php echo $c->comment_date; ?></wp:comment_date>
                                        <wp:comment_date_gmt><?php echo $c->comment_date_gmt; ?></wp:comment_date_gmt>
                                        <wp:comment_content><?php echo wxr_cdata($c->comment_content) ?></wp:comment_content>
                                        <wp:comment_approved><?php echo $c->comment_approved; ?></wp:comment_approved>
                                        <wp:comment_type><?php echo $c->comment_type; ?></wp:comment_type>
                                        <wp:comment_parent><?php echo $c->comment_parent; ?></wp:comment_parent>
                                        <wp:comment_user_id><?php echo $c->user_id; ?></wp:comment_user_id>
                                    </wp:comment>
                                <?php } } ?>
                            </item>
                        <?php }
                    }
                }
                ?>
            </channel>
        </rss>
        <?php

        die();
    }

    /**
     * Set up post export
     */
    public function post_export_setup() {
        if(!function_exists('wxr_missing_parents')) {
            function wxr_missing_parents($categories) {
                if ( !is_array($categories) || empty($categories) )
                    return array();

                foreach ( $categories as $category )
                    $parents[$category->term_id] = $category->parent;

                $parents = array_unique(array_diff($parents, array_keys($parents)));

                if ( $zero = array_search('0', $parents) )
                    unset($parents[$zero]);

                return $parents;
            }
        }
        if(!function_exists('wxr_cdata')) {
            function wxr_cdata($str) {
                if ( seems_utf8($str) == false )
                    $str = utf8_encode($str);

                // $str = ent2ncr(wp_specialchars($str));

                $str = "<![CDATA[$str" . ( ( substr($str, -1) == ']' ) ? ' ' : '') . "]]>";

                return $str;
            }
        }
        if(!function_exists('wxr_site_url')) {
            function wxr_site_url() {
                global $current_site;

                // mu: the base url
                if ( isset($current_site->domain) ) {
                    return 'http://'.$current_site->domain.$current_site->path;
                }
                // wp: the blog url
                else {
                    return get_bloginfo_rss('url');
                }
            }
        }
        if(!function_exists('wxr_cat_name')) {
            function wxr_cat_name($c) {
                if ( empty($c->name) )
                    return;

                echo '<wp:cat_name>' . wxr_cdata($c->name) . '</wp:cat_name>';
            }
        }
        if(!function_exists('wxr_category_description')) {
            function wxr_category_description($c) {
                if ( empty($c->description) )
                    return;

                echo '<wp:category_description>' . wxr_cdata($c->description) . '</wp:category_description>';
            }
        }
        if(!function_exists('wxr_tag_name')) {
            function wxr_tag_name($t) {
                if ( empty($t->name) )
                    return;

                echo '<wp:tag_name>' . wxr_cdata($t->name) . '</wp:tag_name>';
            }
        }
        if(!function_exists('wxr_tag_description')) {
            function wxr_tag_description($t) {
                if ( empty($t->description) )
                    return;

                echo '<wp:tag_description>' . wxr_cdata($t->description) . '</wp:tag_description>';
            }
        }
        if(!function_exists('wxr_post_taxonomy')) {
            function wxr_post_taxonomy() {
                $categories = get_the_category();
                $tags = get_the_tags();
                $the_list = '';
                $filter = 'rss';

                if ( !empty($categories) ) foreach ( (array) $categories as $category ) {
                    $cat_name = sanitize_term_field('name', $category->name, $category->term_id, 'category', $filter);
                    // for backwards compatibility
                    $the_list .= "\n\t\t<category><![CDATA[$cat_name]]></category>\n";
                    // forwards compatibility: use a unique identifier for each cat to avoid clashes
                    // http://trac.wordpress.org/ticket/5447
                    $the_list .= "\n\t\t<category domain=\"category\" nicename=\"{$category->slug}\"><![CDATA[$cat_name]]></category>\n";
                }

                if ( !empty($tags) ) foreach ( (array) $tags as $tag ) {
                    $tag_name = sanitize_term_field('name', $tag->name, $tag->term_id, 'post_tag', $filter);
                    $the_list .= "\n\t\t<category domain=\"tag\"><![CDATA[$tag_name]]></category>\n";
                    // forwards compatibility as above
                    $the_list .= "\n\t\t<category domain=\"tag\" nicename=\"{$tag->slug}\"><![CDATA[$tag_name]]></category>\n";
                }

                echo $the_list;
            }
        }
    }
}
