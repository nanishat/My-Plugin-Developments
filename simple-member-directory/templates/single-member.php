<?php
$slug = get_query_var('member_slug');
list($first, $last) = explode('_', $slug);

$query = new WP_Query([
    'post_type' => 'member',
    'meta_query' => [
        ['key' => 'first_name', 'value' => $first],
        ['key' => 'last_name', 'value' => $last],
        ['key' => 'status', 'value' => 'Active']
    ]
]);

if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

    $email = get_post_meta(get_the_ID(), 'email', true);
    $profile = get_the_post_thumbnail_url(get_the_ID());
    $cover = get_post_meta(get_the_ID(), 'cover_image', true);
    $address = get_post_meta(get_the_ID(), 'address', true);
    $color = get_post_meta(get_the_ID(), 'favorite_color', true);
?>
<div class="member-profile" style="max-width: 700px; margin: auto;">
    <?php if ($cover): ?>
        <img src="<?php echo esc_url($cover); ?>" style="width: 100%; height: 200px; object-fit: cover;">
    <?php endif; ?>

    <?php if ($profile): ?>
        <img src="<?php echo esc_url($profile); ?>" style="width: 150px; border-radius: 50%; margin-top: -75px; border: 5px solid white;">
    <?php endif; ?>

    <h2><?php the_title(); ?></h2>
    <p><strong>Email:</strong> <?php echo esc_html($email); ?></p>
    <p><strong>Address:</strong> <?php echo esc_html($address); ?></p>
    <p><strong>Favorite Color:</strong> <span style="display:inline-block;width:20px;height:20px;background:<?php echo esc_attr($color); ?>;"></span></p>

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
