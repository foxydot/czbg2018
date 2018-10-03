<?php global $wpalchemy_media_access; ?>
    <?php $mb->the_field('event_start_date'); ?>
        <label for="<?php $mb->the_name(); ?>">Start Date</label>
            <p><input class="large-text datepicker" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="" /></p>
    <?php $mb->the_field('event_end_date'); ?>
    <label for="<?php $mb->the_name(); ?>">End Date</label>
    <p><input class="large-text datepicker" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="" /></p>
<?php $mb->the_field('event_blurb'); ?>
<label for="<?php $mb->the_name(); ?>">Event Blurb</label>
<p><textarea maxlength="100" style="width: 100%;" name="<?php $mb->the_name(); ?>"><?php $mb->the_value(); ?></textarea></p>


<?php $mb->the_field('event_recurs_boolean'); ?>
    <p><input class="trigger" type="checkbox" name="<?php $mb->the_name(); ?>" value="1"<?php $mb->the_checkbox_state('1'); ?>/> Event recurrs
    <div class="toggle">
        <p>
        <?php $mb->the_field('event_recurs_frequency'); ?>
        Recurrs every <input class="tiny-text" type="number" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="" />
        <?php $mb->the_field('event_recurs_period'); ?>
            <select name="<?php $mb->the_name(); ?>">
                <option value="">Select...</option>
                <option value="day"<?php $mb->the_select_state('day'); ?>>Days</option>
                <option value="week"<?php $mb->the_select_state('week'); ?>>Week</option>
                <option value="month"<?php $mb->the_select_state('month'); ?>>Months</option>
                <option value="year"<?php $mb->the_select_state('year'); ?>>Years</option>
            </select>
        <?php $mb->the_field('event_recurs_end'); ?>
            until <input class="large-text datepicker" type="text" name="<?php $mb->the_name(); ?>" value="<?php $mb->the_value(); ?>" placeholder="" />

        </p>
    </div>
    </p>
<script>
    jQuery(document).ready(function($) {
        $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
        $('.trigger').each(function(){
            if($(this).prop("checked")) {
                $('.toggle').show();
            } else {
                $('.toggle').hide();
            }
        });
        $('.trigger').change(function(){
            if($(this).prop("checked")) {
                //console.log('checked');
                $('.toggle').show();
            } else {
                //console.log('unchecked');
                $('.toggle').hide();
            }
        });
    });
</script>