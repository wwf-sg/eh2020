<?php
if (has_term('', W2GM_CATEGORIES_TAX, $listing->post->ID)) {
	$terms = get_the_terms($listing->post->ID, W2GM_CATEGORIES_TAX);
	$categories = array();
	foreach ($terms as $term) {
		$categories[] = $term->name;
	}
	echo implode(", ", $categories);
}
?>