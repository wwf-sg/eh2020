<?php if ($content_field->value): ?>
<div class="w2gm-field w2gm-field-output-block w2gm-field-output-block-<?php echo $content_field->type; ?> w2gm-field-output-block-<?php echo $content_field->id; ?>">
	<?php if ($content_field->icon_image || !$content_field->is_hide_name): ?>
	<span class="w2gm-field-caption <?php w2gm_is_any_field_name_in_group($group); ?> w2gm-field-phone-caption">
		<?php if ($content_field->icon_image): ?>
		<span class="w2gm-field-icon w2gm-fa w2gm-fa-lg <?php echo $content_field->icon_image; ?>"></span>
		<?php endif; ?>
		<?php if (!$content_field->is_hide_name): ?>
		<span class="w2gm-field-name"><?php echo $content_field->name?>:</span>
		<?php endif; ?>
	</span>
	<?php endif; ?>
	<span class="w2gm-field-content w2gm-field-phone-content">
		<?php if ($content_field->phone_mode == 'phone'): ?>
		<meta itemprop="telephone" content="<?php echo $content_field->value; ?>" />
		<a href="tel:<?php echo $content_field->value; ?>"><?php echo antispambot($content_field->value); ?></a>
		<?php elseif ($content_field->phone_mode == 'viber'): ?>
		<a href="viber://chat?number=<?php echo $content_field->value; ?>"><?php echo antispambot($content_field->value); ?></a>
		<?php elseif ($content_field->phone_mode == 'whatsapp'): ?>
		<a href="https://wa.me/<?php echo $content_field->value; ?>"><?php echo antispambot($content_field->value); ?></a>
		<?php elseif ($content_field->phone_mode == 'telegram'): ?>
		<a href="tg://resolve?domain=<?php echo $content_field->value; ?>"><?php echo antispambot($content_field->value); ?></a>
		<?php endif; ?>
	</span>
</div>
<?php endif; ?>