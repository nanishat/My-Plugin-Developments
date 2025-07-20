<?php
$args = [
    'post_type' => 'team',
    'posts_per_page' => -1
];

$query = new WP_Query($args);

if ($query->have_posts()) : ?>
    <div class="all-teams" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div style="flex:1 1 300px; border:1px solid #ccc; padding:15px; border-radius:10px;">
                <h3><?php the_title(); ?></h3>
                <p><?php the_excerpt(); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
<?php else : ?>
    <p>No teams found.</p>
<?php endif; wp_reset_postdata(); ?>
