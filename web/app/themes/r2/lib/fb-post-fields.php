<?php
/**
 * Extra fields for Posts (and shared fields w/ other post types)
 */

namespace Firebelly\Fields\Posts;

add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\\metaboxes' );
function metaboxes() {
  $prefix = '_cmb2_';

  $post_is_featured = new_cmb2_box([
    'id'            => $prefix . 'post_is_featured',
    'title'         => esc_html__( 'Is this a featured post on the homepage?', 'cmb2' ),
    'object_types'  => ['post', 'program'],
    'context'       => 'side',
    'priority'      => 'default',
    'show_names'    => false,
  ]);
  $post_is_featured->add_field([
    'name'    => esc_html__( 'Featured', 'cmb2' ),
    'id'      => $prefix . 'featured',
    'desc'    => 'Featured?',
    'type'    => 'checkbox',
  ]);

  $seo_fields = new_cmb2_box([
    'id'            => $prefix . 'seo_fields',
    'title'         => esc_html__( 'SEO', 'cmb2' ),
    'object_types'  => ['post', 'page'],
    'context'       => 'normal',
    'priority'      => 'default',
  ]);
  $seo_fields->add_field([
    'name'    => esc_html__( 'SEO Title', 'cmb2' ),
    'id'      => $prefix . 'seo_title',
    'desc'    => 'Custom title override to improve SEO — limit to ~70 chars',
    'type'    => 'text',
  ]);
  $seo_fields->add_field([
    'name'    => esc_html__( 'SEO Description', 'cmb2' ),
    'id'      => $prefix . 'seo_description',
    'desc'    => 'Used for meta description to improve SEO, and for social sharing — limit to ~160 chars',
    'type'    => 'textarea_small',
  ]);

  // $image_slideshow = new_cmb2_box([
  //   'id'            => 'image_slideshow',
  //   'title'         => esc_html__( 'Image Slideshow', 'cmb2' ),
  //   'object_types'  => ['program', 'workshop'],
  //   'context'       => 'side',
  //   'priority'      => 'low',
  //   // 'closed'        => true,
  // ]);
  // $image_slideshow->add_field([
  //   'name'       => __( 'Images', 'cmb2' ),
  //   'show_names' => false,
  //   'id'         => $prefix .'slideshow_images',
  //   'type'       => 'file_list',
  //   'desc'       => esc_html__('Slideshow for bottom of post', 'cmb2'),
  // ]);

}

/**
 * Omit pages from search results (CMB2 field added in fb-post-fields)
 */
function omit_pages_from_search($query) {
  global $wpdb;
  if (!is_admin() && is_search()) {
    $excluded_ids = $wpdb->get_col('SELECT post_id FROM '.$wpdb->postmeta.' WHERE meta_key="_cmb2_omit_from_search"');
    $query->set('post__not_in', $excluded_ids);
  }
  return $query;
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\omit_pages_from_search');
