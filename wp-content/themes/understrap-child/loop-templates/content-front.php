<?php
/**
 * Partial template for content in page.php
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<div class="entry-content">

        <?php

        global $post;

        $realty_objects = get_posts( [
            'posts_per_page' => 8,
            'post_type' => 'realty',
        ] );

        $cities = get_posts( [
            'posts_per_page' => 4,
            'post_type' =>'cities'
        ] );

        ?>

        <?php if ( $realty_objects ) : ?>
            <div class="realty-title">Недвижимость</div>
            <div class="realty-wrapper">
                <?php foreach ( $realty_objects as $post ) : ?>
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
        <?php endif; ?>

		<?php if ( $cities ) : ?>
            <div class="realty-title">Города</div>
            <div class="realty-wrapper">
				<?php foreach ( $cities as $post ) : ?>
					<?php setup_postdata( $post ); ?>
                    <div class="realty-item">
                        <a href="<?= the_permalink(); ?>">
							<?= get_the_post_thumbnail( get_the_ID(), 'small' ); ?>
                            <h3><?= get_the_title(); ?></h3>
                        </a>
                    </div>
				<?php endforeach; ?>
				<?php wp_reset_postdata(); ?>
            </div>
		<?php endif; ?>

        <div class="add-realty-form">
            <div class="title">Добавление объекта недвижимости</div>
            <form id="add-form" method="post">
                <div class="form-row">
                    <input type="text" id="realty_name" name="realty_name" class="form-control" placeholder="Название">
                    <input type="text" id="realty_area" name="realty_area" class="form-control" placeholder="Площадь, кв.м.">
                    <input type="text" id="realty_cost" name="realty_cost" class="form-control" placeholder="Стоимость, руб.">
                    <input type="text" id="realty_address" name="realty_address" class="form-control" placeholder="Адрес">
                    <input type="text" id="realty_living_area" name="realty_living_area" class="form-control" placeholder="Жилая площадь, кв.м.">
                    <input type="text" id="realty_floor" name="realty_floor" class="form-control" placeholder="Этаж">
                    <?php wp_dropdown_categories( [
                        'taxonomy' => 'realty_type',
                    ] ); ?>
                    <select id="object_city" name="object_city" class="postform form-select" name="object_city">
                        <?php if ( $cities ) : ?>
                            <?php foreach ( $cities as $city ) : ?>
                                <option class="level-0" value="<?= $city->ID; ?>"><?= $city->post_title; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <button id="form-submit" type="submit" class="btn btn-primary">Отправить</button>
                </div>
            </form>
        </div>
		<?php

		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_edit_post_link(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
