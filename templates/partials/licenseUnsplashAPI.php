<?php
/**
 * This file is part of the Unsplash App
 * and licensed under the AGPL.
 */

$textLicense = $l->t('under an open license free of charge');

$links = [
    "<a href=\"https://unsplash.com/license\" rel=\"noreferrer noopener\" target=\"_blank\">{$textLicense}</a>",
    "<a href=\"https://unsplash.com\" rel=\"noreferrer noopener\" target=\"_blank\">unsplash.com</a>"
];

print_unescaped($l->t('These images are provided %s by %s.', $links));