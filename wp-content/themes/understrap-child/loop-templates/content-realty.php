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
        $term = get_realty_term();
    ?>

    <div class="realty-content-wrapper">
		<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>
        <?php get_template_part( 'loop-templates/content-object', 'chars', [ 'post_id' => $post->ID, 'term_slug' => $term->slug ] ); ?>
    </div>

    <div class="entry-content">

        <?php
        the_content();
        understrap_link_pages();
        ?>

    </div><!-- .entry-content -->

    <footer class="entry-footer">

		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
