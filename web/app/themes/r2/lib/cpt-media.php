<?php
/**
 * Media Post Type
 */

namespace Firebelly\PostTypes\Media;
use PostTypes\PostType; // see https://github.com/jjgrainger/PostTypes
use PostTypes\Taxonomy;

$media = new PostType(['name' => 'media', 'plural' => 'Media', 'slug' => 'media'], [
  'taxonomies' => ['year'],
  'supports'   => ['title', 'editor', 'thumbnail', 'revisions'],
  'rewrite'    => ['with_front' => false],
]);
$media->icon('dashicons-media-document');
$media->register();

/**
 * CMB2 custom fields
 */
function metaboxes() {
  $prefix = '_cmb2_';

  $media_info = new_cmb2_box([
    'id'            => $prefix . 'media_info',
    'title'         => __( 'Media Info', 'cmb2' ),
    'object_types'  => ['media'],
    'context'       => 'normal',
    'priority'      => 'high',
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
function get_media($options=[]) {
  if (empty($options['numberposts'])) $options['numberposts'] = -1;
  $args = [
		'numberposts' => $options['numberposts'],
		'post_type'   => 'media',
		'post_type'   => 'media',
		'order'       => 'ASC',
		'orderby'     => 'meta_value',
		'meta_key'    => '_cmb2_media_date',
  ];

  // Display all matching posts using article-{$post_type}.php
  $posts = get_posts($args);
  if (!$posts) return false;
  $output = '';
  foreach ($posts as $post):
    ob_start();
    \Firebelly\Utils\get_template_part_with_vars('templates/article', 'media', [ 'media' => $post]);
    $output .= ob_get_clean();
  endforeach;
  return $output;
}
