<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

script('unsplash', 'settings');
style('unsplash', 'settings');

?>
<div class="section" id="unsplash-settings" data-save="<?=$_['saveSettingsUrl']?>" data-requestUpdate="<?=$_['requestCustomizationUrl']?>">
    <h2>
        <?php p($l->t('Splash: Random Background Images')); ?>
        <span class="msg success"><?php p($l->t('Saved')); ?></span>
        <span class="msg error"><?php p($l->t('Failed')); ?></span>
    </h2>
    <p class="settings-hint">
        <?php p($l->t('Here you can specify where random backgrounds should be used by default.')); ?>
        <?php print_unescaped($this->inc('partials/license')); ?>
    </p>
    <form>
        <div>
            <input id="unsplash-style-login" name="unsplash-style-login" data-setting="style/login" type="checkbox" <?=$_['styleLogin'] ? 'checked':''?> class="checkbox">
            <label for="unsplash-style-login"><?php p($l->t('Set random image as login background')); ?></label>
        </div>
        <div>
            <input id="unsplash-style-header" name="unsplash-style-header" data-setting="style/header" type="checkbox" <?=$_['styleHeader'] ? 'checked':''?> class="checkbox">
            <label for="unsplash-style-header"><?php p($l->t('Set random image as header background')); ?></label>
        </div>
        <?php if($_['hasDashboard']): ?>
        <div>
            <input id="unsplash-style-dashboard" name="unsplash-style-dashboard" data-setting="style/dashboard" type="checkbox" <?=$_['styleDashboard'] ? 'checked':''?> class="checkbox">
            <label for="unsplash-style-dashboard"><?php p($l->t('Set random image as dashboard background')); ?></label>
        </div>
        <?php endif; ?>
        <div>
            <select id="splash-provider-selection" data-setting="provider/provider" type="select">
                <?php foreach ($_['availableProvider'] as &$value) {
                    echo "<option value='" . $value . "'";
                    if ($value==$_['selectedProvider']) {
                        echo "selected";
                    }
                    echo ">";
                    p($l->t($value));
                    echo "</option>";
                }
                ?>
            </select>
            <label for="splash-provider-selection"><?php p($l->t('Set the imageprovider')); ?></label>
        </div>
        <div>
            <input type="text" id="splash-provider-customization" data-setting="provider/customization"
            <?php
                echo " value='".$_['selectionCustomization']."'>";
            ?>
            <label for="splash-provider-customization"><?php p($l->t('Set custom searchterms. Seperate multiple terms by comma.')); ?></label>
        </div>
        <br>
        <h2>
            <?php p($l->t('Splash: Advanced Theming')); ?>
        </h2>
        <p class="settings-hint">
            <?php p($l->t('You can apply effects to your background images.')); ?>
        </p>

		<div>
			<input id="unsplash-style-tinting" name="unsplash-style-grayscale" data-setting="style/tint" type="checkbox" <?=$_['styleTint'] ? 'checked':''?> class="checkbox">
			<label for="unsplash-style-tinting"><?php p($l->t('Enable Tint')); ?></label>
		</div>
		<div>
			<input
					id="unsplash-style-color-strenght"
					type="range"
					name="unsplash-style-color-strenght"
					data-setting="style/strength/color"
					min="0"
					max="100"
					value="<?=$_['styleStrengthColor'] ? $_['styleStrengthColor']:30?>"
					<?=$_['styleTint'] ? '':'disabled'?>
			>
			<label for="unsplash-style-color-strenght"><?php p($l->t('Set the vibrancy of the color')); ?></label>
		</div>
		<div>
			<input type="range" name="unsplash-style-blur" data-setting="style/strength/blur" min="0" max="25" value="<?=$_['styleStrengthBlur'] ? $_['styleStrengthBlur']:0?>">
			<label for="unsplash-style-blur"><?php p($l->t('Set the blur of the image')); ?></label>
			<p><?php p($l->t('Blur is currently only supported in Chrome Browsers.')); ?></p>
		</div>
    </form>
</div>
