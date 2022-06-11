<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */


$links = [
    "<a href=\"https://commons.wikimedia.org/wiki/Main_Page\" rel=\"noreferrer noopener\" target=\"_blank\">commons.wikimedia.org</a>"
];

print_unescaped($l->t('These images are provided by %s. Each image has it\'s own license.', $links));
