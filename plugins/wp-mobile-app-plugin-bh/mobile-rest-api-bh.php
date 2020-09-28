<?php
/**
 * Plugin Name: TFS Mobile Rest Api - BanyanHill
 * Plugin URL: http://www.threefoldsystems.com
 * Description: Customisation of TFS Mobile Rest API
 * Version: 1.0.0
 * Author: Threefold Systems
 * Author URI: http://www.threefoldsystems.com
 */

add_filter('ma_extension_rest_extra_data', [BHMobileApp::class, 'extraDataProcessing']);
add_filter('ma_get_posts_query_args', [BHMobileApp::class, 'getPostsQueryArgs'], 10, 2);

class BHMobileApp
{
    public static function extraDataProcessing($post)
    {
        // Get URL of featured image
        $featuredImageUrl = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0] ?? false;
        $categories = static::getCategories($post->ID) + static::getCategories($post->ID, 'archives-category');
        $author = static::getAuthor($post);
        $isSavedPost = static::isSavedPost($post->ID);
        $meta = static::getMetaData($post);

        $processedContent = static::processContent($post->post_content, $meta);
        $post->post_content = $processedContent['content'];
        $meta = $processedContent['meta'];

        // Add featured image, add author data
        $postData = array_merge((array)$post, [
                'featured_image_url' => $featuredImageUrl,
                'author_data' => $author,
                'post_categories' => $categories,
                'post_saved' => $isSavedPost,
                'post_meta' => $meta,
            ]
        );


        return (object)$postData;
    }

    /**
     * @param $queryArgs
     * @param $request
     *
     * @return mixed
     */
    public function getPostsQueryArgs($queryArgs, $request)
    {
        $category = sanitize_text_field($request->get_param('category'));

        if (empty($category)) {
            if (empty($queryArgs['tax_query'])) {
                $queryArgs['tax_query'] = [];
            }

            $queryArgs['tax_query'][] = [
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => ['great-stuff'],
                'operator' => 'NOT IN',
            ];
        }

        return $queryArgs;
    }

    public function extraUserData()
    {
        $extraData = ['user' => wp_get_current_user()->ID];

        $pubCodes = MA_Mobile_Rest_API::getMiddlewareWrapper()->getSubscribedPubCodes();

        $tradeAlertPages = new WP_Query([
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'page_title',
                    'value' => 'Trade Alerts',
                    'compare' => '=',
                ],
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'pubcode',
                    'field' => 'name',
                    'terms' => $pubCodes,
                ],
            ],
        ]);

        $categories = [];

        while ($tradeAlertPages->have_posts()) {
            $tradeAlertPages->the_post();
            $postTerms = get_the_terms($tradeAlertPages->post->ID, 'archives-category');
            $categories[] = $postTerms[0]->term_id;
        }

        $userMeta = get_user_meta(wp_get_current_user()->ID, MA_Mobile_Rest_API::getCore()->user_meta_key_name, true);

        $unreadTradeAlerts = new WP_Query([
            'post_type' => 'archives',
            'meta_query' => [
                [
                    'key' => 'archieve_date',
                    'value' => strtotime($userMeta['last_login']),
                    'compare' => '>='
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'archives-category',
                    'field' => 'term_id',
                    'terms' => $categories,
                ],
            ],
        ]);

        $extraData['lastLog'] = $userMeta;
        $extraData['unreadTradeAlerts'] = $unreadTradeAlerts->have_posts();

        return $extraData;
    }

    /**
     * Return array of post categories
     *
     * @param $postId
     * @param string $taxonomy
     *
     * @return array
     */
    private static function getCategories($postId, $taxonomy = 'category')
    {
        $categories = [];
        $postTerms = get_the_terms($postId, $taxonomy);

        foreach ($postTerms as $term) {
            if ($term->slug === 'uncategorized') {
                continue;
            }

            $categories[] = [
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            ];
        }

        return $categories;
    }

    /**
     * Return boolean if post is saved
     *
     * @param $postId
     *
     * @return bool
     */
    private static function isSavedPost($postId)
    {
        $isSavedPost = false;
        // Get user's saved posts, anc check if this post is saved
        $savedPosts = get_user_meta(get_current_user_id(), MA_Mobile_Rest_API::getCore()->posts_saved_meta_name, true);

        if (!empty($savedPosts) && is_array($savedPosts) && in_array($postId, $savedPosts)) {
            $isSavedPost = true;
        }

        return $isSavedPost;
    }

    /**
     * Return string of estimated time to read content
     *
     * @param $content
     *
     * @return string
     */
    private static function getReadTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = floor($wordCount / 200);
        $seconds = floor($wordCount % 200 / (200 / 60));
        $readTime = ($minutes === 0 ? '' : $minutes . ' minute')
            . ($minutes !== 0 && $seconds !== 0 ? ', ' : '')
            . ($seconds == 0 ? '' : $seconds . ' second');

        return $readTime;
    }

    /**
     * @param $content
     * @param $meta
     *
     * @return array
     */
    private static function processContent($content, $meta)
    {
        // Overwrite post content if `expert_about` meta data is present
        if (!empty($meta['expert_about'])) {
            $content = $meta['expert_about'];
            unset($meta['expert_about']);
        }


        $content = preg_replace('/\[ipt\_fsqm\_form .*?\]/', sprintf('<a href="%s" target="_blank">View Poll on website</a>', $meta['permalink']), $content);

        // Render shortcode/block content
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        $filteredContent = static::getMediaFromContent($content);
        $content = $filteredContent['content'];
        $meta['media'] = $filteredContent['media'];
        $meta['featured_video'] = $filteredContent['featuredVideo'];
        // Add read time to meta data
        $meta['readTime'] = static::getReadTime($content);

        return [
            'content' => $content,
            'meta' => $meta,
        ];
    }

    /**
     * Return content with illegal tags replaced with placeholder and data extracted
     *
     * @param $content
     *
     * @return array
     */
    private static function getMediaFromContent($content)
    {
        $featuredVideo = false;

        preg_match_all('/\<(iframe|video|audio) .+?\>\<\/\1\>/', $content, $matches);
        foreach ($matches[0] as $index => $match) {
            $tag = $matches[1][$index];
            $key = sprintf('extracted%d', $index);

            $content = str_replace($match, sprintf('{{%s}}', $key), $content);
            $extracted = [
                'tag' => $tag,
            ];;

            preg_match_all('/ ([^\s]*?)=\"(.*?)\"/', $match, $attributes);
            foreach ($attributes[1] as $attributeIndex => $attribute) {
                $extracted[$attribute] = $attributes[2][$attributeIndex];
            }

            if (
                !$featuredVideo && (
                    $tag === 'video' || (
                        $tag === 'iframe' &&
                        $extracted['src'] &&
                        strpos($extracted['src'], 'youtube') !== false
                    )
                )
            ) {
                $featuredVideo = $extracted;
            }

            $media[$key] = $extracted;
        }

        return [
            'content' => $content,
            'media' => $media,
            'featuredVideo' => $featuredVideo,
        ];
    }

    /**
     * Return post meta data
     *
     * @param $post
     *
     * @return array
     */
    private static function getMetaData($post)
    {
        $metaData = [
            'permalink' => get_the_permalink($post->ID),
        ];

        // Get meta data
        $postMeta = get_post_meta($post->ID);
        foreach ($postMeta as $key => $value) {
            if (substr($key, 0, 1) !== '_' && $key !== 'amazonS3_cache') {
                $metaData[$key] = get_post_meta($post->ID, $key, true);
            }
        }

        if (!empty($postMeta['archieve_date'])) {
            $metaData['unread'] = strtotime($postMeta['archieve_date']) >= MA_Mobile_Rest_API::getRestHelpers()->getLastLogin();
        }

        return $metaData;
    }

    private static function getAuthor($post)
    {
        // Get author data
        $authorInfo = get_userdata($post->post_author);
        $exportPost = false;

        $authorData = [
            'id' => $authorInfo->ID,
            'slug' => $authorInfo->user_nicename,
            'author_name' => $authorInfo->display_name,
            'author_image' => get_avatar_url($authorInfo->ID, [150, 150]),
        ];

        if ($post->post_type === 'archives') {
            $pubCodeCats = static::getCategories($post->ID, 'pubcode');
            $pubCodes = [];

            foreach ($pubCodeCats as $pubCode) {
                $pubCodes[] = $pubCode['name'];
            }

            if (!empty($pubCodes)) {
                $userPubCodes = MA_Mobile_Rest_API::getMiddlewareWrapper()->getSubscribedPubCodes();
                $pubCodes = array_intersect($pubCodes, $userPubCodes);
            }


            if (!empty($pubCodes)) {
                $pubCodesCount = count($pubCodes);
                if ($pubCodesCount === 1) {
                    $pubCode = strtolower($pubCodes[0]);
                } else if ($pubCodesCount > 1) {
                    if (false !== $key = array_search('svc', $pubCodes)) {
                        $pubCode = 'svc';
                    } else {
                        // only check for Bauman (Light)
                        if (false !== $key = array_search('sce_cf', $pubCodes)) {
                            $pubCode = 'sce_cf';
                        } else {
                            $pubCode = 'svc';
                        }
                    }
                }

                $subscriptionsQuery = new WP_Query([
                    'cat' => 209, // Subscription Category
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'posts_per_page' => 1,
                    'tax_query' => [
                        [
                            'taxonomy' => 'pubcode',
                            'field' => 'name',
                            'terms' => $pubCode,
                            'include_children' => false,
                        ],
                    ],
                ]);

                if ($subscriptionsQuery->have_posts()) {
                    $parent = false;
                    // For each subscription
                    while ($subscriptionsQuery->have_posts()) {
                        $subscriptionsQuery->the_post();

                        if ($subscriptionsQuery->post->post_parent) {
                            $ancestors = get_post_ancestors($subscriptionsQuery->post->ID);
                            $root = count($ancestors) - 1;
                            $parent = $ancestors[$root];
                        } else {
                            $parent = $subscriptionsQuery->post->ID;
                        }
                    }

                    wp_reset_postdata();

                    // Get Expert image and subscription logo
                    if (get_post_meta($parent, 'expert_id', true)) {
                        $exportPostId = get_post_meta($parent, 'expert_id', true);
                        $expertPost = get_post($exportPostId);
                    }
                }
            }
        }

        if (!$expertPost) {
            $expertQuery = new WP_Query([
                'post_type' => 'expert',
                'meta_query' => [
                    [
                        'key' => 'author_connection',
                        'value' => $authorInfo->ID,
                    ],
                ],
            ]);

            if ($expertQuery->have_posts()) {
                $expertQuery->the_post();
                $expertPost = $expertQuery->post;
            }
        }

        if ($expertPost) {
            $expertAuthorId = get_post_meta($expertPost->ID, 'author_connection', true);
            if ($expertAuthorId !== $authorInfo->ID) {
                $authorInfo = get_userdata($expertAuthorId);

                $authorData = [
                    'id' => $authorInfo->ID,
                    'slug' => $authorInfo->user_nicename,
                    'author_name' => $authorInfo->display_name,
                    'author_image' => get_avatar_url($authorInfo->ID, [150, 150]),
                ];
            }

            $expertImage = get_the_post_thumbnail_url($expertPost->ID, [150, 150]);
            if ($expertImage) {
                $authorData['author_image'] = $expertImage;
            }
        }

        return $authorData;
    }
}

