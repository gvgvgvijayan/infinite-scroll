<?php
$vg_posts_ui_query = new WP_Query($att);

if ($vg_posts_ui_query->have_posts()) {
    while ($vg_posts_ui_query->have_posts()) {
        $vg_posts_ui_query->the_post();
?>
        <article class="vg-post-excerpt vg-item">
            <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php the_post_thumbnail('medium'); ?>
                </a>
            <?php endif; ?>
            <h2 class="vg-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div><?php the_excerpt(); ?></div>
            <a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
        </article>
<?php
    }
} ?>