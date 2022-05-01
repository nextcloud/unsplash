<?php
/**
 * This file is part of the Unpslash App
 * and licensed under the AGPL.
 */

namespace OCA\Unsplash\Settings;

use OCP\IL10N;
use OCP\IURLGenerator;
use OCP\Settings\IIconSection;

/**
 * Class PersonalSection
 *
 * @package OCA\Unsplash\Settings
 */
class PersonalSection implements IIconSection {

    /**
     * @var IURLGenerator
     */
    protected $urlGenerator;

    /**
     * @var IL10N
     */
    protected $localisation;

    /**
     * @var
     */
    protected $appName;

    /**
     * AdminSection constructor.
     *
     * @param IL10N         $localisation
     * @param IURLGenerator $urlGenerator
     * @param               $appName
     */
    public function __construct(IL10N $localisation, IURLGenerator $urlGenerator, $appName) {
        $this->localisation = $localisation;
        $this->urlGenerator = $urlGenerator;
        $this->appName      = $appName;
    }

    /**
     * Returns the relative path to an 16*16 icon describing the section.
     * e.g. '/core/img/places/files.svg'
     *
     * @returns string
     * @since 12
     */
    public function getIcon(): string {
        return $this->urlGenerator->imagePath($this->appName, 'app-dark.svg');
    }

    /**
     * Returns the ID of the section. It is supposed to be a lower case string,
     * e.g. 'passwords'
     *
     * @returns string
     * @since 9.1
     */
    public function getID(): string {
        return $this->appName;
    }

    /**
     * Returns the translated name as it should be displayed, e.g. 'Splash'.
     * Use the L10N service to translate it.
     *
     * @return string
     * @since 9.1
     */
    public function getName(): string {
        return $this->localisation->t('Splash');
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the settings navigation. The sections are arranged in ascending order of
     * the priority values. It is required to return a value between 0 and 99.
     *
     * E.g.: 70
     * @since 9.1
     */
    public function getPriority(): int {
        return 50;
    }
}