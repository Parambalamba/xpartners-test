<?php
/**
 * Single realty partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">

			<?php understrap_posted_on(); ?>

		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->


    <?php
        global $post;

        $objects = get_posts( [
            'posts_per_page' => 10,
            'post_type' => 'realty',
            'post_parent' => get_the_ID()
        ] );
    ?>

    <div class="entry-content">

        <?php if ( $objects ) : ?>
            <div class="realty-wrapper">
                <?php foreach ( $objects as $post ) : ?>
                    <?php setup_postdata( $post ); ?>
	                <?php $term = get_realty_term(); ?>
                    <div class="realty-item">
                        <a href="<?= the_permalink(); ?>">
			                <?= get_the_post_thumbnail( get_the_ID(), 'small' ); ?>
                            <h3><?= get_the_title(); ?></h3>
			                <?php get_template_part( 'loop-templates/content-object', 'chars', [ 'post_id' => get_the_ID(), 'term_slug' => $term->slug ] ); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
                <?php wp_reset_postdata(); ?>
            </div>
        <?php else: ?>
            <div class="realty-title">Нет объектов недвижимости в данном городе</div>
        <?php endif; ?>

    </div><!-- .entry-content -->

    <footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
