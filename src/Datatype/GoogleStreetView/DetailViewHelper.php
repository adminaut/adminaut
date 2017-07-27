<?php
namespace Adminaut\Datatype\GoogleStreetView;

use Adminaut\Datatype\GoogleStreetView;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\AbstractHelper;

class DetailViewHelper extends AbstractHelper
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|GoogleStreetView
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (! $element) {
            return $this;
        }

        return $this->render($element);
    }

    public function render($datatype) {
        if (! $datatype instanceof GoogleStreetView) {
            throw new \Zend\Form\Exception\InvalidArgumentException(sprintf(
                '%s requires that the element is of type Adminaut\Datatype\StreetView',
                __METHOD__
            ));
        }

        if($datatype->getValue()) {
            $data = json_decode($datatype->getValue());
            $config = $this->getView()->plugin("config")->getConfig();
            $api = isset($config['adminaut']['google-api']) ? $config['adminaut']['google-api'] : "";

            $sRender = '<div class="row datatype-streetview-detail">';
            $sRender .= '<div class="col-xs-12"><img src="https://maps.googleapis.com/maps/api/streetview?size=1000x300&location=' . $data->latitude . ',' . $data->longitude . '&fov=90&heading=' . $data->povHeading . '&pitch=' . $data->povPitch . '&key=' . $api . '"></div>';
            $sRender .= '</div>';

            return $sRender;
        } else {
            return '';
        }
    }
}