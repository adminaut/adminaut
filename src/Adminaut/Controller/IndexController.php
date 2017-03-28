<?php
namespace Adminaut\Controller;

use Zend\View\Model\JsonModel;

/**
 * Class IndexController
 * @package Adminaut\Controller
 */
class IndexController extends AdminautBaseController
{
    /**
     * @var array
     */
    private $defaultManifest = [
        'name' => 'Adminaut',
        'show_name' => 'Adminaut',
        'description' => 'Adminaut is the open-source platform for rapid development of PHP applications with the automatic construction of administration backend. It\'s built on the top of PHP, Zend Framework, Doctrine ORM and other frameworks.',
        'display' => 'standalone',
        'theme_color' => '#3c8dbc',
        'background_color' => '#3c8dbc',
        "icons" => [
            [
                "src" => "/adminaut/img/adminaut-favicon-16x16.png",
                "sizes" => "16x16",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-favicon-24x24.png",
                "sizes" => "24x24",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-favicon-32x32.png",
                "sizes" => "32x32",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-favicon-48x48.png",
                "sizes" => "48x48",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-favicon-57x57.png",
                "sizes" => "57x57",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-favicon-64x64.png",
                "sizes" => "64x64",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-favicon-72x72.png",
                "sizes" => "72x72",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-icon-114x114.png",
                "sizes" => "114x114",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-icon-120x120.png",
                "sizes" => "120x120",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-icon-144x144.png",
                "sizes" => "144x144",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-icon-152x152.png",
                "sizes" => "152x152",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-icon-167x167.png",
                "sizes" => "167x167",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-icon-180x180.png",
                "sizes" => "180x180",
                "type" => "image/png"
            ],
            [
                "src" => "/adminaut/img/adminaut-icon-1024x1024.png",
                "sizes" => "1024x1024",
                "type" => "image/png"
            ]
        ]
    ];

    /**
     * @return \Zend\Http\Response
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('adminaut-dashboard');
    }

    /**
     * @return JsonModel
     */
    public function manifestAction() {
        if(isset($this->getConfig()['adminaut']['manifest'])) {
            $manifest = array_merge($this->getConfig()['adminaut']['manifest'], $this->defaultManifest);
        } else {
            $manifest = $this->defaultManifest;
        }

        $uri = $this->getRequest()->getUri();
        $baseUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        foreach($manifest['icons'] as &$icon) {
            if(strpos('http', $icon['src']) === false && strpos('https', $icon['src']) === false) {
                $icon['src'] = $baseUrl . $icon['src'];
            }
        }

        return new JsonModel($manifest);
    }
}