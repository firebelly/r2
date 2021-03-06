<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\Fields\Pages;

add_filter( 'cmb2_admin_init', __NAMESPACE__ . '\metaboxes' );
function metaboxes() {
  $prefix = '_cmb2_';

  /**
    * Page intro fields
    */
  $page_intro = new_cmb2_box([
    'id'            => $prefix . 'page_intro',
    'title'         => esc_html__( 'Page Intro', 'cmb2' ),
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro Title', 'cmb2' ),
    'id'   => $prefix .'intro_title',
    'type' => 'text',
  ]);
  $page_intro->add_field([
    'name' => esc_html__( 'Intro paragraph', 'cmb2' ),
    'id'   => $prefix .'intro_para',
    'type' => 'textarea_small',
  ]);

  /**
    * Homepage Fields
    */
  $homepage_fields = new_cmb2_box([
    'id'            => $prefix . 'homepage_fields',
    'title'         => esc_html__('Homepage Settings', 'cmb2'),
    'show_on'       => ['key' => 'page-template', 'value' => ['front-page.php']],
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $homepage_fields->add_field([
    'name'  => esc_html__('Animation Text', 'cmb2'),
    'id'    => $prefix .'animation_text',
    'type'  => 'text',
  ]);
  $homepage_fields->add_field([
    'name'    => esc_html__('Banner Video Vimeo ID', 'cmb2'),
    'id'      => $prefix .'banner_video',
    'desc'    => 'Paste in the video ID from vimeo, ex: https://vimeo.com/<strong>359676836</strong>',
    'type'    => 'text_small',
  ]);

  /**
    * About Page Fields
    */
  $about_header = new_cmb2_box([
    'id'            => $prefix . 'about_header',
    'title'         => esc_html__('Header Video', 'cmb2'),
    'show_on'       => ['key' => 'page-template', 'value' => ['page-about.php']],
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'   => false
  ]);
  $about_header->add_field([
    'name'  => 'Header Video',
    'id'    => $prefix . 'header_video',
    'desc'  => 'Full vimeo video url (ex: https://vimeo.com/364142068/ac2becbf0f) from vimeo. This is optional, an image can be used instead, set from the Featured Image field.',
    'type'  => 'text',
  ]);

  $about_test = new_cmb2_box([
    'id'            => $prefix . 'about_stats_box',
    'title'         => esc_html__('Stats', 'cmb2'),
    'show_on'       => ['key' => 'page-template', 'value' => ['page-about.php']],
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $about_test_group = $about_test->add_field([
    'id'              => $prefix .'about_stats',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Stat {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Stat', 'cmb2' ),
      'remove_button' => __( 'Remove Stat', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $about_test->add_group_field( $about_test_group, [
    'name' => 'Stat Figure',
    'id'   => 'figure',
    'type' => 'text',
    'sanitization_cb' => 'Firebelly\Utils\unsanitize_cmb2_field',
  ]);
  $about_test->add_group_field( $about_test_group, [
    'name' => 'Stat Description',
    'id'   => 'description',
    'type' => 'text',
  ]);

  $about_platform = new_cmb2_box([
    'id'            => $prefix . 'about_platform_box',
    'title'         => esc_html__('Platform', 'cmb2'),
    'show_on'       => ['key' => 'page-template', 'value' => ['page-about.php']],
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'low',
  ]);
  $about_platform_group = $about_platform->add_field([
    'id'              => $prefix .'about_platform',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Platform Item {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Platform Item', 'cmb2' ),
      'remove_button' => __( 'Remove Platform Item', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $about_platform->add_group_field( $about_platform_group, [
    'name' => 'Icon',
    'id'   => 'icon',
    'type' => 'file',
    'options' => array(
      'url' => false,
    ),
    'query_args' => array(
      'type' => array(
        'image/svg',
        'image/png',
      ),
    ),
    'preview_size' => 'small',
  ]);
  $about_platform->add_group_field( $about_platform_group, [
    'name' => 'Title',
    'id'   => 'title',
    'type' => 'text',
  ]);
  $about_platform->add_group_field( $about_platform_group, [
    'name' => 'Description',
    'id'   => 'description',
    'type' => 'textarea',
  ]);


  /**
    * Media Page Fields
    */
  $media_fields = new_cmb2_box([
    'id'            => $prefix . 'media_fields',
    'title'         => esc_html__('Featured Media Sources', 'cmb2'),
    'show_on'       => ['key' => 'page-template', 'value' => ['page-media.php']],
    'object_types'  => ['page'],
    'context'       => 'normal',
    'priority'      => 'high',
  ]);
  $media_sources_group = $media_fields->add_field([
    'id'              => $prefix .'media_sources',
    'type'            => 'group',
    'options'         => [
      'group_title'   => __( 'Source {#}', 'cmb2' ),
      'add_button'    => __( 'Add Another Source', 'cmb2' ),
      'remove_button' => __( 'Remove Source', 'cmb2' ),
      'sortable'      => true,
    ],
  ]);
  $media_fields->add_group_field( $media_sources_group, [
    'name' => 'Source Title',
    'id'   => 'title',
    'type' => 'text',
  ]);
  $media_fields->add_group_field( $media_sources_group, [
    'name' => 'Source Logo',
    'id'   => 'logo',
    'type' => 'file',
    'options' => array(
      'url' => false,
    ),
    'query_args' => array(
      'type' => array(
        'image/svg',
        'image/png',
      ),
    ),
    'preview_size' => 'small',
  ]);
}

function sanitize_text_callback( $value, $field_args, $field ) {
  $value = strip_tags( $value, '<b><strong><i><em>' );
  return $value;
}
