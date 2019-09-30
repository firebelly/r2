<?php
/*
  Template name: Portfolio
*/

$projects = Firebelly\PostTypes\Project\get_projects();
?>

<div class="container">
  <?php get_template_part('templates/page', 'header'); ?>

  <?php get_template_part('templates/project-filters'); ?>

  <?php if (!empty($projects)): ?>
    <div class="project-grid grid spaced filter-grid">
      <?= $projects ?>
    </div>
  <?php endif ?>
</div>
