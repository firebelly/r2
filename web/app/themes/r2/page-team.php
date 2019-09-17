<?php
/*
  Template name: Team
*/

$team_members = Firebelly\PostTypes\TeamMember\get_team_members();
?>

<?php get_template_part('templates/page', 'header'); ?>

<?php if (!empty($team_members)): ?>
  <div class="team-grid">
    <?= $team_members ?>
  </div>
<?php endif ?>