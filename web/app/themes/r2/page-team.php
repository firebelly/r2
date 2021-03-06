<?php
/*
  Template name: Team
*/

$team_members = Firebelly\PostTypes\TeamMember\get_team_members();
$advisory_board = Firebelly\PostTypes\TeamMember\get_team_members(['category'=>'advisory-board']);
?>

<div class="container">
  <?php get_template_part('templates/page', 'header'); ?>

  <?php get_template_part('templates/team-filters'); ?>

  <?php if (!empty($team_members)): ?>
    <div class="team-grid grid spaced filter-grid">
      <?= $team_members ?>
    </div>
  <?php endif ?>

  <?php if (!empty($advisory_board)): ?>
    <div class="team-grid grid spaced filter-grid">
      <h4 class=" grid-item advisory-board advisory-board-title" >Advisory Board</h4>
      <?= $advisory_board ?>
    </div>
  <?php endif ?>
</div>
