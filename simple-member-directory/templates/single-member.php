<?php
$slug = get_query_var('member_slug');
list($first, $last) = explode('_', $slug);

$query = new WP_Query([
    'post_type' => 'smd_member',
    'meta_query' => [
        ['key' => '_smd_first_name', 'value' => $first],
        ['key' => '_smd_last_name', 'value' => $last],
        ['key' => 'status', 'value' => 'Active']
    ]
]);

if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

    $position = get_post_meta(get_the_ID(), '_smd_position', true);
    $email = get_post_meta(get_the_ID(), '_smd_email', true);
    $phone = get_post_meta(get_the_ID(), '_smd_phone', true);
    $favorite_color = get_post_meta(get_the_ID(), '_smd_color', true);
    $team_ids = get_post_meta(get_the_ID(), '_smd_teams', true) ?: [];
    $address = get_post_meta(get_the_ID(), '_smd_address', true);
    $profile = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
    $cover = get_post_meta(get_the_ID(), '_smd_cover_image', true);

    // Get team names
    $teams = [];
    if ($team_ids) {
        foreach ($team_ids as $team_id) {
            $team_post = get_post($team_id);
            if ($team_post) {
                $teams[] = $team_post->post_title;
            }
        }
    }
?>
<div class="member-profile" style="max-width: 700px; margin: auto;">
    <?php if ($cover): ?>
        <img src="<?php echo esc_url($cover); ?>" class="cover-image" alt="Cover Image">
    <?php endif; ?>

    <?php if ($profile): ?>
        <img src="<?php echo esc_url($profile); ?>" class="profile-image" alt="Profile Image">
    <?php endif; ?>

    <h2><?php the_title(); ?></h2>
    <?php if ($position): ?>
        <p><strong>Position:</strong> <?php echo esc_html($position); ?></p>
    <?php endif; ?>
    <?php if ($email): ?>
        <p><strong>Email:</strong> <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
    <?php endif; ?>
    <?php if ($phone): ?>
        <p><strong>Phone:</strong> <?php echo esc_html($phone); ?></p>
    <?php endif; ?>
    <?php if ($address): ?>
        <p><strong>Address:</strong> <?php echo esc_html($address); ?></p>
    <?php endif; ?>
    <?php if ($favorite_color): ?>
        <p><strong>Favorite Color:</strong> <span style="display:inline-block;width:20px;height:20px;background:<?php echo esc_attr($favorite_color); ?>;"></span></p>
    <?php endif; ?>
    <?php if ($teams): ?>
        <p><strong>Teams:</strong> <?php echo esc_html(implode(', ', $teams)); ?></p>
    <?php endif; ?>

    <h3>Contact <?php the_title(); ?></h3>
    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
        <input type="hidden" name="action" value="member_contact">
        <input type="hidden" name="member_email" value="<?php echo esc_attr($email); ?>">

        <p><input type="text" name="name" placeholder="Your Name" required></p>
        <p><input type="email" name="email" placeholder="Your Email" required></p>
        <p><textarea name="message" placeholder="Your Message" required></textarea></p>
        <p><button type="submit">Send Message</button></p>
    </form>
</div>

<?php endwhile; endif; wp_reset_postdata(); ?>
