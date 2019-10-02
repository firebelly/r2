<?php
/**
 * Media Publication Post Type
 */

namespace Firebelly\PostTypes\MediaPublication;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$media_publication = new PostType(['name' => 'media_publication', 'plural' => 'Media Publications', 'slug' => 'media'], [
  'supports'   => ['title', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$media_publication->icon('dashicons-media-document');
$media_publication->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $media_info = new_cmb2_box([
    'id'            => $prefix . 'media_info',
    'title'         => __( 'Media Info', 'cmb2' ),
    'object_types'  => ['media_publication'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $media_info->add_field([
    'name'        => 'Publication Name',
    'id'          => $prefix . 'publication_name',
    'type'        => 'text',
  ]);
  $media_info->add_field([
    'name'        => 'Date',
    'id'          => $prefix . 'media_date',
    'type'        => 'text_date',
    'column'      => [
      'position'  => 2,
      'name'      => 'Media Date',
    ],
  ]);
  $media_info->add_field([
    'name'        => 'URL',
    'id'          => $prefix . 'media_url',
    'type'        => 'text_url',
	  'column'      => [
	    'position'  => 3,
	    'name'      => 'URL',
	  ],
  ]);
}
add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );

/**
 * Get Media
 */
function get_media_publications($options=[]) {
  if (empty($options['numberposts'])) $options['numberposts'] = -1;
  $args = [
		'numberposts' => $options['numberposts'],
		'post_type'   => 'media_publication',
		'order'       => 'DESC',
		'orderby'     => 'meta_value',
		'meta_key'    => '_cmb2_media_date',
  ];

  // Display all matching posts using article-{$post_type}.php
  $posts = get_posts($args);
  if (!$posts) return false;

  // Get Years From Publications
  $years = array();
  foreach ($posts as $post):
    $date = get_post_meta($post->ID, '_cmb2_media_date', true);
    $year = substr($date, -4);
    $years[$year][] = $post;
  endforeach;
  krsort($years);

  $output = '<ol class="media-publications">';
  foreach ($years as $year => $yearposts):
    $output .= '<li class="year"><div class="container"><h3 class="year-label">'.$year.'</h3><ul class="publications-list">';
      foreach ($yearposts as $post):
        ob_start();
        \Firebelly\Utils\get_template_part_with_vars('templates/article', 'media_publication', [ 'media_publication' => $post]);
        $output .= ob_get_clean();
      endforeach;
    $output .= '</ul></div></li>';
  endforeach;
  $output .= '</ol>';
  return $output;
}
