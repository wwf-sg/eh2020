<tr class="form-field hide-if-no-js">
	<th scope="row" valign="top"><label for="description"><?php print _e('Marker Icon', 'W2GM') ?></label></th>
	<td>
		<?php echo $w2gm_instance->categories_manager->choose_marker_icon_link($term->term_id); ?>
		<p class="description"><?php _e('Associate an icon to this category', 'W2GM'); ?></p>
	</td>
</tr>