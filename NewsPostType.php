<?php
/*
Plugin Name: NewsPostType
Plugin URI: kazunari.hal2016.com
Description: ニュースをカスタム投稿タイプとして表示するプラグイン
Version: 1.0
Author: Kazunari Hirosawa
Author URI: kazunari.hal2016.com
License:GPL2
*/
/*  Copyright 2017 Kazunari Hirosawa (email : kazunari@hal2016.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//ここからプラグイン
add_action('init', 'kasotsuka_news');
function kasotsuka_news_init(){
    $labels = array(
        'name' => _x('仮想通貨ニュース', 'post type general name'),
        'singular_name' => _x('仮想通貨ニュースラインナップ', 'post type singular name'),
        'add_new' => _x('新しくニュースを書く', 'wpfont'),
        'add_new_item' => __('ニュースを書く'),
        'edit_item' => __('ニュースを編集'),
        'new_item' => __('新しいニュース'),
        'view_item' => __('ニュースを見てみる'),
        'search_items' => __('ニュースを探す'),
        'not_found' =>  __('ニュースはありません'),
        'not_found_in_trash' => __('ゴミ箱にニュースはありません'),
        'parent_item_colon' => ''
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
        'supports' => array('title','editor','thumbnail','custom-fields','excerpt','revisions','page-attributes','comments'),
        'has_archive' => true
    );
    register_post_type('wpnews',$args);
    //カテゴリータイプ
    $args = array(
        'label' => 'ニュースカテゴリー',
        'public' => true,
        'show_ui' => true,
        'hierarchical' => true
    );
    register_taxonomy('wpnews_category','wpnews',$args);
    //タグタイプ
    $args = array(
        'label' => 'ニュースタグ',
        'public' => true,
        'show_ui' => true,
        'hierarchical' => false
    );
    register_taxonomy('wpnews_tag','wpnews',$args);
}
/* post_id.htmlにRewrite */
function myposttype_rewrite() {
    global $wp_rewrite;
    $queryarg = 'post_type=wpnews&p=';
    $wp_rewrite->add_rewrite_tag('%wpnews_id%', '([^/]+)',$queryarg);
    $wp_rewrite->add_permastruct('wpnews', '/wpnews/entry-%wpnews_id%/', false);
}
add_action('init', 'myposttype_rewrite');
function myposttype_permalink($post_link, $id = 0, $leavename) {
    global $wp_rewrite;
    $post = &get_post($id);
    if ( is_wp_error( $post ) )
        return $post;
    $newlink = $wp_rewrite->get_extra_permastruct($post->post_type);
    $newlink = str_replace('%'.$post->post_type.'_id%', $post->ID, $newlink);
    $newlink = home_url(user_trailingslashit($newlink));
    return $newlink;
}
add_filter('post_type_link', 'myposttype_permalink', 1, 3);
?>
