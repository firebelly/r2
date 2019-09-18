<?php
/*
  Template name: Portfolio
*/

$projects = Firebelly\PostTypes\Project\get_projects();
?>

<?php get_template_part('templates/page', 'header'); ?>

<?php if (!empty($projects)): ?>
  <div class="project-grid">
    <?= $projects ?>
  </div>
<?php endif ?>