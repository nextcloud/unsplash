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
        <?php p($l->t('You can customize your instance with random background images.')); ?>
        <br>
        <?php p($l->t('Here you can specify where random backgrounds should be used by default.')); ?>
        <?php print_unescaped($this->inc('partials/license')); ?>
    </p>
    <form>
        <div class="unsplash-checkboxes">
            <input id="unsplash-style-login" name="unsplash-style-login" data-setting="style/login" type="checkbox" <?=$_['styleLogin'] ? 'checked':''?>>
            <label for="unsplash-style-login"><?php p($l->t('Set random image as login background')); ?></label>
        </div>
        <?php if($_['hasDashboard']): ?>
        <div class="unsplash-checkboxes">
            <input id="unsplash-style-dashboard" name="unsplash-style-dashboard" data-setting="style/dashboard" type="checkbox" <?=$_['styleDashboard'] ? 'checked':''?>>
            <label for="unsplash-style-dashboard"><?php p($l->t('Set random image as dashboard background')); ?></label>
        </div>
        <?php endif; ?>
        <div class="unsplash-providerselect">
            <label class="unsplash-label" for="splash-provider-selection"><?php p($l->t('Set the image provider:')); ?></label>
            <select class="unsplash-input" id="splash-provider-selection" data-setting="provider/provider" type="select">
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
            <p class="settings-hint">
                <?php print_unescaped($this->inc('partials/license'.$_['selectedProvider'])); ?>
            </p>
        </div>
        <div class="unsplash-providerselect">
            <label class="unsplash-label" for="splash-provider-customization"><?php p($l->t('Keywords:')); ?></label>
            <input class="unsplash-input" type="text" id="splash-provider-customization" data-setting="provider/customization"
            <?php
                echo " value='".$_['selectionCustomization']."'>";
            ?>
            <p class="settings-hint">
                <?php p($l->t('Set custom search terms. Separate multiple terms by comma.')); ?>
            </p>
        </div>
		<div class="unsplash-providertoken">
			<label class="unsplash-label" for="splash-provider-token"><?php p($l->t('Token:')); ?></label>
			<input class="unsplash-input" type="text" id="splash-provider-token" data-setting="provider/token"
			<?php
			echo " value='****'>";
			?>
			<p class="settings-hint">
				<?php p($l->t('Set the required token.')); ?>
			</p>
		</div>
        <br>
        <h2>
            <?php p($l->t('Splash: Advanced Theming')); ?>
        </h2>
        <p class="settings-hint">
            <?php p($l->t('You can apply effects to your background images.')); ?>
        </p>

		<div class="unsplash-checkboxes">
			<input id="unsplash-style-tinting" name="unsplash-style-tinting" data-setting="style/tint" type="checkbox" <?=$_['styleTint'] ? 'checked':''?>>
            <label for="unsplash-style-tinting"><?php p($l->t('Enable Tint')); ?></label>
		</div>
		<div class="unsplash-slider">
            <label for="unsplash-style-color-strength"><?php p($l->t('Set the vibrancy of the color')); ?></label>
			<input
					id="unsplash-style-color-strength"
					type="range"
					name="unsplash-style-color-strength"
					data-setting="style/strength/color"
					min="0"
					max="100"
					value="<?=$_['styleStrengthColor'] ? $_['styleStrengthColor']:30?>"
					<?=$_['styleTint'] ? '':'disabled'?>
			>
		</div>
		<div class="unsplash-slider">
            <label for="unsplash-style-blur"><?php p($l->t('Set the blur of the image')); ?></label>
			<input type="range" name="unsplash-style-blur" data-setting="style/strength/blur" min="0" max="25" value="<?=$_['styleStrengthBlur'] ? $_['styleStrengthBlur']:0?>">
		</div>

        <br>
        <h2>
            <?php p($l->t('Splash: High Visibility')); ?>
        </h2>
        <p class="settings-hint">
            <?php p($l->t('You can enable a High Visibility Mode for Legal Reasons. This will highlight the Privacy and Data Protection links on the login screen.')); ?>
        </p>
        <div class="unsplash-checkboxes">
            <input id="unsplash-style-highvisibility" name="unsplash-style-highvisibility" data-setting="style/login/highvisibility" type="checkbox" <?=$_['styleHighVisibility'] ? 'checked':''?>>
            <label for="unsplash-style-highvisibility"><?php p($l->t('Enable High-Visibility Mode')); ?></label>
        </div>
    </form>
</div>
