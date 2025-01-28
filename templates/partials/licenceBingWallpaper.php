<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */


$links = [
    "<a href=\"https://bingwallpaper.microsoft.com/mac/en/bing/bing-wallpaper\" rel=\"noreferrer noopener\" target=\"_blank\">Bing Wallpaper</a>"
];

print_unescaped($l->t('These images are provided by %s. Each image has it\'s own license.', $links));