<?php
namespace Adminaut\Widget\View\Helper;

use Adminaut\Widget\WidgetInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class WidgetViewHelper
 * @package Adminaut\Widget\View\Helper
 */
class WidgetViewHelper extends AbstractHelper
{
    public function __invoke(WidgetInterface $widget)
    {
        $boxClass = 'box';
        $boxClass .= !empty($widget->getColor()) ? ' box-' . $widget->getColor() : '';

        $output = '<div class="'. $boxClass .'">
            <div class="box-header with-border">';
        if(!empty($widget->getIcon())) {
            $output .= '<i class="fa ' . $widget->getIcon() . '"></i>';
        }
        $output .= '<h3 class="box-title">'. $widget->getTitle() .'</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              </div>
            </div>
            <div class="box-body">'. $widget->renderBody() .'</div>';
        if(!empty($boxFooter = $widget->renderFooter())) {
            $output .= '<div class="box-footer">'. $boxFooter .'</div>';
        }

        $output .= '</div>';

        return $output;
    }
}