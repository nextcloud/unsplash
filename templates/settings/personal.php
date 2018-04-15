<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

script('unsplash', 'settings');
style('unsplash', 'settings');

?>
<div class="section" id="unsplash-settings" data-save="<?=$_['saveSettingsUrl']?>">
    <h2>
        <?php p($l->t('Random Background Images')); ?>
        <span class="msg success"><?php p($l->t('Saved')); ?></span>
        <span class="msg error"><?php p($l->t('Failed')); ?></span>
    </h2>
    <p class="settings-hint">
        <?php p($l->t('You can customize %s with random background images.', [$_['label']])); ?>
        <?php print_unescaped($this->inc('partials/license')); ?>
    </p>
    <form>
        <div>
            <input id="unsplash-style-header" name="unsplash-style-header" data-setting="style/header" type="checkbox" <?=$_['styleHeader'] ? 'checked':''?> class="checkbox">
            <label for="unsplash-style-header"><?php p($l->t('Set random image as header background')); ?></label>
        </div>
    </form>
</div>