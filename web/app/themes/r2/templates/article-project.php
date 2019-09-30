<?php
$image = \Firebelly\Media\get_post_thumbnail($project->ID);
$location = \Firebelly\Utils\get_first_term($project, 'project_location');
$property_type = \Firebelly\Utils\get_first_term($project, 'property_type');
$status = get_post_meta($project->ID, '_cmb2_status', true);
$locality = get_post_meta($project->ID, '_cmb2_locality', true);
?>
<article class="project grid-item <?= $status ?> <?= $location->slug ?> <?= $property_type->slug ?> col-md-1-2 col-lg-1-4">
  <a class="-inner" href="<?= get_permalink($project); ?>">

    <div class="project-image" style="background-image:url('<?= $image ?>');"></div>
    <div class="project-info">
      <h2><?= $project->post_title ?></h2>
      <h3><?= $locality ?></h3>
    </div>
  </a>
</article>
