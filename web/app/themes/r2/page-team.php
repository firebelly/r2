<?php
/*
  Template name: Team
*/

$team_members = Firebelly\PostTypes\TeamMember\get_team_members();
?>

<div class="container">
  <?php get_template_part('templates/page', 'header'); ?>

  <?php get_template_part('templates/team-filters'); ?>

  <?php if (!empty($team_members)): ?>
    <div class="team-grid grid spaced filter-grid">
      <?= $team_members ?>
    </div>
  <?php endif ?>
</div>
