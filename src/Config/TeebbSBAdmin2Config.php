<?php

namespace Teebb\SBAdmin2Bundle\Config;


class TeebbSBAdmin2Config implements TeebbSBAdmin2ConfigInterface
{
    /**
     * @var string
     */
    private $logoText;

    /**
     * @var string
     */
    private $logoImage;

    /**
     * @var string
     */
    private $favicon;

    /**
     * @var array
     */
    private $adminGroups;

    /**
     * @var array
     */
    private $adminServiceIds;

    /**
     * @var array
     */
    private $options;


    public function __construct($logoText, $logoImage, $favicon, $options=[])
    {
        $this->logoText = $logoText;

        $this->logoImage = $logoImage;

        $this->favicon = $favicon;

        $this->options = $options;
    }


    /**
     * @return string
     */
    public function getLogoText(): string
    {
        return $this->logoText;
    }

    /**
     * @return string
     */
    public function getLogoImage(): string
    {
        return $this->logoImage;
    }


    public function getFavicon(): string
    {
        return $this->favicon;
    }

    public function getOption(string $optionName, $default=[])
    {
        if (isset($this->options[$optionName])) {
            return $this->options[$optionName];
        }

        return $default;
    }

    public function getAdminGroups(): array
    {
        return $this->adminGroups;
    }

    public function setAdminGroups(array $adminGroups = [])
    {
        $this->adminGroups = $adminGroups;
    }
}