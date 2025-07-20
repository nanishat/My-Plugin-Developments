<?php
$args = [
    'post_type' => 'member',
    'meta_query' => [
        [
            'key' => 'status',
            'value' => 'Active'
        ]
    ]
];

$query = new WP_Query($args);

if ($query->have_posts()) : ?>
    <div class="all-members" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <?php while ($query->have_posts()) : $query->the_post();
            $first = get_post_meta(get_the_ID(), 'first_name', true);
            $last = get_post_meta(get_the_ID(), 'last_name', true);
            $profile = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            $url = site_url("/{$first}_{$last}");
        ?>
            <div style="text-align: center; border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                <a href="<?php echo esc_url($url); ?>">
                    <?php if ($profile): ?>
                        <img src="<?php echo esc_url($profile); ?>" style="width:100px; height:100px; border-radius:50%;">
                    <?php endif; ?>
                    <h4><?php the_title(); ?></h4>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
<?php else : ?>
    <p>No members found.</p>
<?php endif; wp_reset_postdata(); ?>
