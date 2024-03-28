<?php
$post_id = $args[ 'post_id' ];
$term_slug = $args[ 'term_slug' ];
?>
<div class="realty-fields">
	<span>Площадь:</span>
	<span><?= get_field( 'square', $post_id ) ? get_field( 'square', $post_id ) . ' кв.м.' : ''; ?></span>
	<span>Стоимость:</span>
	<span><?= get_field( 'price', $post_id ) ? get_field( 'price', $post_id ) . ' руб.' : '' ?></span>
	<span>Адрес:</span>
	<span><?= get_field( 'address', $post_id ) ? esc_attr( get_field( 'address', $post_id ) ) : '' ?></span>
	<?php if ( $term_slug === 'flat' || $term_slug === 'house' ) : ?>
		<span>Жилая площадь</span>
		<span><?= get_field( 'living-square', $post_id ) ? get_field( 'living-square', $post_id ) . ' кв.м.' : ''; ?></span>
	<?php endif; ?>
	<?php if ( $term_slug === 'flat' ) : ?>
		<span>Этаж</span>
		<span><?= get_field( 'floor', $post_id ) ? get_field( 'floor', $post_id ) : ''; ?></span>
	<?php endif; ?>

</div>
