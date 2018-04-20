<?php
namespace Adminaut\Widget;

/**
 * Interface WidgetInterface
 * @package Adminaut\Widget
 */
interface WidgetInterface
{
    /**
     * @param string $title
     * @return void
     */
    public function setTitle(string $title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $icon
     * @return void
     */
    public function setIcon($icon);

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @return string
     */
    public function renderBody();

    /**
     * @return string
     */
    public function renderFooter();
}