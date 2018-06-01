<?php
global $wpalchemy_media_access;
$textfields = array('latin_name','common_name','where','pronunciation','length','height','weight','wingspan','ecological','venomous','lifespan','habitat','diet','risk_status');
$textfields2 = array('caption','range');
?>
<table class="form-table">
    <tbody>
    <?php foreach ($textfields AS $tf){
        $title = ucwords(preg_replace('/_/',' ',$tf));
        $mb->the_field($tf);
        print '<tr valign="top">
        <th scope="row"><label for="'.$mb->get_the_name().'">'.$title.'</label></th>
        <td>
            <p><input class="large-text" type="text" name="'.$mb->get_the_name().'" value="'.$mb->get_the_value().'" placeholder="" /></p>
        </td>
    </tr>';
    }
    ?>
    <tr valign="top">
        <th scope="row"><label for="logos">Logos</label></th>
        <td>
            <?php $items = array('Species @ Risk Image' => 'species-at-risk','Species Survival Plan Image' => 'species-survival-plan'); ?>
            <?php foreach ($items as $i => $item): ?>
                <?php $mb->the_field('logos', WPALCHEMY_FIELD_HINT_CHECKBOX_MULTI); ?>
                <input type="checkbox" name="<?php $mb->the_name(); ?>" value="<?php echo $item; ?>"<?php $mb->the_checkbox_state($item); ?>/> <?php echo $i; ?><br/>
            <?php endforeach; ?>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><label for="action_images">Action Images</label></th>
        <td>
            <?php
            $mb->the_field('action_images');
            $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
            $mb_editor_id = sanitize_key($mb->get_the_name());
            $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '5',);
            wp_editor( $mb_content, $mb_editor_id, $mb_settings );
            ?>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row"><label for="video_embed">Video Embed Code</label></th>
        <td>
            <?php
            $mb->the_field('video_embed');
            $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
            $mb_editor_id = sanitize_key($mb->get_the_name());
            $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '5',);
            wp_editor( $mb_content, $mb_editor_id, $mb_settings );
            ?>
        </td>
    </tr>

    <?php foreach ($textfields2 AS $tf){
        $title = ucwords(preg_replace('/_/',' ',$tf));
        $mb->the_field($tf);
        print '<tr valign="top">
        <th scope="row"><label for="'.$mb->get_the_name().'">'.$title.'</label></th>
        <td>
            <p><input class="large-text" type="text" name="'.$mb->get_the_name().'" value="'.$mb->get_the_value().'" placeholder="" /></p>
        </td>
    </tr>';
    }
    ?>

    <tr valign="top">
        <th scope="row"><label for="range_map">Range Map</label></th>
        <td>
            <?php
            $mb->the_field('range_map');
            $mb_content = html_entity_decode($mb->get_the_value(), ENT_QUOTES, 'UTF-8');
            $mb_editor_id = sanitize_key($mb->get_the_name());
            $mb_settings = array('textarea_name'=>$mb->get_the_name(),'textarea_rows' => '5',);
            wp_editor( $mb_content, $mb_editor_id, $mb_settings );
            ?>
        </td>
    </tr>
    </tbody>
</table>