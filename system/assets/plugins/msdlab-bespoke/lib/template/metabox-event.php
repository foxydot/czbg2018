<?php global $wpalchemy_media_access; ?>
    <?php $mb->the_field('event_start_date'); ?>
        <label for="<?php $mb->the_name(); ?>">Start Date</label>
            <p><input class="large-text datepicker" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="" /></p>
    <?php $mb->the_field('event_end_date'); ?>
        <label for="<?php $mb->the_name(); ?>">End Date</label>
            <p><input class="large-text datepicker" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="" /></p>
<script>
    jQuery(document).ready(function($) {
        $('.datepicker').datepicker();
    });
</script>