<?php

namespace Adminaut\Controller;

use Adminaut\Service\ManifestService;
use Zend\Http\PhpEnvironment\Request;
use Zend\View\Model\JsonModel;

/**
 * Class ManifestController
 * @package Adminaut\Controller
 */
class ManifestController extends AdminautBaseController
{
    /**
     * @var ManifestService
     */
    private $manifestService;

    /**
     * ManifestController constructor.
     * @param ManifestService $manifestService
     */
    public function __construct(ManifestService $manifestService)
    {
        $this->manifestService = $manifestService;
    }

    /**
     * @return JsonModel
     */
    public function indexAction()
    {
        $manifest = $this->manifestService->getMergedManifest();

        /** @var Request $request */
        $request = $this->getRequest();

        $uri = $request->getUri();
        $baseUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        foreach ($manifest['icons'] as &$icon) {
            if (strpos('http', $icon['src']) === false && strpos('https', $icon['src']) === false) {
                $icon['src'] = $baseUrl . $icon['src'];
            }
        }

        return new JsonModel($manifest);
    }
}
