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
        <? p($l->t('Random Background Images')); ?>
        <span class="msg success"><?php p($l->t('Saved')); ?></span>
        <span class="msg error"><?php p($l->t('Failed')); ?></span>
    </h2>
    <p class="settings-hint">
        <? p($l->t('Here you can specify where random backgrounds should be used by default.')); ?>
        <? print_unescaped($this->inc('partials/license')); ?>
    </p>
    <form>
        <div>
            <input id="unsplash-style-login" name="unsplash-style-login" data-setting="style/login" type="checkbox" <?=$_['styleLogin'] ? 'checked':''?> class="checkbox">
            <label for="unsplash-style-login"><? p($l->t('Use random background image for login and public pages for visitors')); ?></label>
        </div>
        <div>
            <input id="unsplash-style-header" name="unsplash-style-header" data-setting="style/header" type="checkbox" <?=$_['styleHeader'] ? 'checked':''?> class="checkbox">
            <label for="unsplash-style-header"><? p($l->t('Use random background image for navigation bar and public pages for users')); ?></label>
        </div>
        <div>
            <input id="unsplash-keep-image" name="unsplash-keep-image" data-setting="image/persistence" type="checkbox" <?=$_['keepImage'] ? 'checked':''?> class="checkbox">
            <label for="unsplash-keep-image"><? p($l->t('Use the same image for the whole session')); ?></label>
        </div>
        <br>
        <div>
            <label for="unsplash-api-query"><? p($l->t('Image theme')); ?></label>&nbsp;
            <select id="unsplash-api-query" name="unsplash-api-query" data-setting="api/query">
                <?php foreach($_['subjects'] as $option): ?>
                <option value="<?=$option?>" <? echo $option===$_['apiQuery'] ? 'selected':''?> ><? p($l->t(ucfirst($option)))?></option>
                <?php endforeach; ?>
            </select>
        </div>
            <div>
            <label for="unsplash-api-key"><? p($l->t('Api Key')); ?></label>&nbsp;
            <input id="unsplash-api-key" name="unsplash-api-key" data-setting="api/key" value="<?=$_['apiKey']?>" />
        </div>
    </form>
</div>