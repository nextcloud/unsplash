<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

$textLicense = $l->t('under the terms of service');

$links = [
    "<a href=\"https://wallhaven.cc/about#Copyright\" rel=\"noreferrer noopener\" target=\"_blank\">{$textLicense}</a>",
    "<a href=\"https://wallhaven.cc\" rel=\"noreferrer noopener\" target=\"_blank\">wallhaven.cc</a>"
];

print_unescaped($l->t('These images are provided %1%s by %2%s.', $links));
