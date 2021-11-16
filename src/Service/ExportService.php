<?php

namespace Adminaut\Service;

use Adminaut\Datatype\Datatype;
use Adminaut\Form\Form;
use Adminaut\Manager\ModuleManager;
use Adminaut\Options\ModuleOptions;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Zend\Form\Element;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Paginator\Paginator;
use Zend\View\HelperPluginManager;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class ExportService
 */
class ExportService
{
    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var AccessControlService
     */
    protected $accessControlService;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param ModuleManager $moduleManager
     * @param AccessControlService $accessControlService
     */
    public function __construct(
        ModuleManager $moduleManager,
        AccessControlService $accessControlService,
        TranslatorInterface $translator
    ) {
        $this->moduleManager = $moduleManager;
        $this->accessControlService = $accessControlService;
        $this->translator = $translator;
    }

    /**
     * @param string $moduleId
     * @param array $columns
     * @param array $sort
     * @param string $searchValue
     * @param string $format
     */
    public function export($moduleId, $columns, $sort, $searchValue, $format)
    {
        $data = $this->prepareData($moduleId, $columns, $sort, $searchValue);

        switch ($format) {
            case 'csv' : $this->exportCsv($moduleId, $data);
                break;
            case 'xlsx' : $this->exportXlsx($moduleId, $data);
        }
    }

    public function exportCsv($moduleId, $data)
    {
        header('Content-Type: text/csv');
        header(sprintf('Content-Disposition: attachment; filename="%s"', sprintf("%s.csv", $moduleId)));
        $fHandler = fopen('php://output', 'w+');
        $printHeader = true;

        foreach ($data as $_data) {
            if ($printHeader) {
                fputcsv($fHandler, array_keys($_data), ';');

                $printHeader = false;
            }

            fputcsv($fHandler, array_values($_data), ';');
        }

        fclose($fHandler);
        die();
    }

    public function exportXlsx($moduleId, $data)
    {
        $spreadSheet = new Spreadsheet();
        $spreadSheet->disconnectWorksheets();
        $sheet = $spreadSheet->createSheet(0);
        $sheet->insertNewRowBefore(1);
        $printHeader = true;
        $row = 2;

        foreach ($data as $_data) {
            if ($printHeader) {
                foreach (array_keys($_data) as $columnIndex => $label) {
                    $sheet->setCellValue(sprintf('%s1', Coordinate::stringFromColumnIndex($columnIndex + 1)), $label);
                }

                $printHeader = false;
            }

            foreach (array_values($_data) as $columnIndex => $value) {
                $sheet->setCellValue(sprintf('%s%s', Coordinate::stringFromColumnIndex($columnIndex + 1), $row), $value);
            }

            $row++;
        }

        foreach ($sheet->getColumnDimensions() as $columnDimension) {
            $columnDimension->setAutoSize(true);
        }

        $writer = new Xlsx($spreadSheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. $moduleId.'.xlsx"');
        $writer->save('php://output');
        die();
    }

    /**
     * @param string $moduleId
     * @param array $columns
     * @param array $sort
     * @param string $searchValue
     * @return array
     */
    private function prepareData($moduleId, $columns, $sort, $searchValue)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $this->moduleManager->createModuleOptions($moduleId);
        /** @var Form $form */
        $form = $this->moduleManager->createForm($moduleOptions);

        if (empty($sort)) {
            foreach ($this->moduleManager->getDatatableColumns($moduleId, $form) as $i => &$column) {
                if(isset($column['order']) && $column['order']) {
                    $sort[] = [$i, $column['order']];
                }
            }
        }

        $listedElements = $this->moduleManager->getListedColumns($moduleId, $form);
        $searchableColumns = $this->moduleManager->getSearchableColumns($moduleId, $form);
        $filterableColumns = $this->moduleManager->getFilterableColumns($moduleId, $form);
        $datatableColumns = $this->moduleManager->getDatatableColumns($moduleId, $form);
        $orders = $this->moduleManager->getOrderedElements($sort ?? [], $moduleId, $form);
        $columnSearch = [];

        $columnNames = array_keys($datatableColumns);
        foreach ($columns as $i => $_column) {
            if (isset($_column['search']) && isset($_column['search']['value']) && strlen($_column['search']['value']) > 0 && $_column['search']['value'] !== 'null') {
                /** @var Datatype|Element $filterableColumnDatatype */
                $filterableColumnDatatype = $filterableColumns[$columnNames[$i]];
                $filterableColumnDatatype->setValue($_column['search']['value']);

                if ( method_exists($filterableColumnDatatype, 'getInsertValue') ) {
                    $columnSearch[$columnNames[$i]] = $filterableColumnDatatype->getInsertValue();
                } else {
                    $columnSearch[$columnNames[$i]] = $filterableColumnDatatype->getValue();
                }
            }
        }

        $criteria = $this->accessControlService->getModuleCriteria($moduleId);
        $ormPaginator = $this->moduleManager->getDatatable($moduleOptions->getEntityClass(), $criteria, $searchableColumns, $searchValue, $columnSearch, $orders);

        $filters = [];
        if ( !empty( $filterableColumns ) ) {
            $_filters = $this->moduleManager->getDatatableFilters($moduleOptions->getEntityClass(), $filterableColumns, $criteria, $searchableColumns, $searchValue, $columnSearch);

            $dtColumns = array_keys($datatableColumns);

            foreach (array_keys($_filters) as $columnName) {
                if ($columnKey = array_search($columnName, $dtColumns)) {
                    $filters[$columnKey] = $_filters[$columnName];
                }
            }
        }

        $paginator = new Paginator(new DoctrineAdapter($ormPaginator));

        $data = [];

        foreach ($paginator as $entity) {
            $_data = [];

            $_data['#'] = $entity->getId();

            /**
             * @var string $key
             * @var Datatype|Element $listedElement
             */
            foreach ($this->moduleManager->getExportableColumns($moduleId, $form) as $key => $element) {
                $label = $this->translator->translate($element->getLabel());
                $element->setValue($entity->{$element->getName()});

                if (method_exists($element, 'getExportValue')) {
                    $value = $element->isPrimary() && empty($element->getExportValue()) ? 'undefined' : $element->getExportValue();
                } else {
                    $value = $element->getValue();
                }

                $_data[$label] = $value;
            }

            $data[] = $_data;
        }

        return $data;
    }
}