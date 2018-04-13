<?php
/** @var array $_ */
/** @var \OCP\IL10N $l */
script('unsplash', 'settings-admin');
style('unsplash', 'settings-admin');
?>
<div id="unsplash-settings" class="section">
	<h2><?php p($l->t('Unsplash')); ?></h2>
	<p class="settings-hint"><?php p($l->t('Shows a random nature image at log in and in the header.')); ?></p>

	<p>
		<input id="headerbackground" type="checkbox" class="checkbox" <?php if($_['headerbackground']) p('checked'); ?> />
		<label for="headerbackground"><?php p($l->t('Enable background in header')); ?></label>
	</p>
    <p>
        <label for="headerbackgroundlink"><?php p($l->t('Enable background in header')); ?></label>

        <input id="headerbackgroundlink" type="text" placeholder="<?php p($l->t('headerbackgroundlink')); ?>"
               value="<?php if(isset($_['headerbackgroundlink']))p($_['headerbackgroundlink']); ?>" maxlength="250" />
    </p>

</div>
