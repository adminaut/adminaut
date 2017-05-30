<?php
namespace Adminaut\Manager;

use League\Flysystem\Filesystem;
use Adminaut\Entity\File;
use Adminaut\Entity\FileKeyword;
use Adminaut\Exception;

use WideImage\WideImage;
use Zend\Validator\File\IsImage;

/**
 * Class Manager
 * @package Application\FileManager
 */
class FileManager
{
    private static $instance = null;

    private static $constructParams = null;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Filesystem
     */
    protected $cacheFilesystem;

    /**
     * @var \Adminaut\Entity\File
     */
    protected $file;

    /**
     * @var array
     */
    protected $cache;

    /**
     * FileManager constructor.
     * @param array $params
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct()
    {
        $this->setEntityManager(self::$constructParams["em"]);
        $this->setParams(self::$constructParams["params"]);
        $this->setFilesystem(self::$constructParams["filesystem"]);
        $this->setCacheFilesystem(self::$constructParams["cache_filesystem"]);
    }

    /**
     * @param null $constructParams
     */
    public static function setConstructParams($em, $params, $filesystem, $cacheFS)
    {
        self::$constructParams = [
            'em' => $em,
            'params' => $params,
            'filesystem' => $filesystem,
            'cache_filesystem' => $cacheFS
        ];
    }

    public static function getInstance(){
        if(null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $filename
     */
    private function getMimeType($filename)
    {
        $this->getFilesystem()->getMimetype($filename);
    }

    /**
     * @param $fileId
     * @return \Adminaut\Entity\File
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Adminaut\Exception\FileNotFoundException
     */
    public function getFileById($fileId)
    {
        if (isset($this->cache[$fileId])) {
            $entity = $this->cache[$fileId];
        } else {
            $entity = $this->em->find('Application\Entity\File', $fileId);
        }
        if (!$entity) {
            throw new Exception\FileNotFoundException(
                'File does not exist.', 404
            );
        }
        $this->cache[$fileId] = $entity;
        return $entity;
    }

    /**
     * @param $keywords
     * @param bool|false $fromCache
     * @return mixed
     */
    /*public function getFilesByKeywords($keywords, $fromCache = false)
    {
        // Create unique ID of the array for cache
        $id = md5(serialize($keywords));

        // Change all given keywords to lowercase
        $keywords = array_map('strtolower', $keywords );

        // Get the entity from cache if available
        if ($fromCache && isset($this->cache[$id])) {
            return $this->cache[$id];
        }

        $list = "'" . implode("','", $keywords) . "'";

        $q = $this->em->createQuery(
            "select f from FileBank\Entity\File f, FileBank\Entity\Keyword k
             where k.file = f
             and k.value in (" . $list . ")"
            );

        // Cache the file entity so we don't have to access db on each call
        // Enables to get multiple entity's properties at different times
        $this->cache[$id] = $q->getResult();
        return $this->cache[$id];
    }*/

    /**
     * @param \Adminaut\Form\Element\File $element
     * @param \Adminaut\Entity\User|null $user
     * @param array $option
     * @return File
     */
    public function upload($element, \Adminaut\Entity\UserEntity $user = null, array $option = [])
    {
        $_file = $element->getValue();
        if($_file['error'] != 0){
            return null;
        }
        $fileName = $_file['name'];
        $mimetype = $_file['type'];
        $hash     = md5(microtime(true) . $fileName);
        $savePath = substr($hash,0,1).'/'.substr($hash,1,1).'/';

        $file = new File();
        if ($user) {
            $file->setInsertedBy($user->getId());
        }
        if (isset($option['fileName'])) {
            $file->setName($option['fileName']);
        } else {
            $file->setName($fileName);
        }
        $file->setMimetype($mimetype);
        $file->setSize($_file['size']);
        $file->setActive($this->params['default_is_active']);
        $file->setSavePath($savePath . $hash);
        if (isset($option['keywords'])) {
            $this->addKeywordsToFile($option['keywords']);
        }

        try {
            $this->getFilesystem()->writeStream($savePath . $hash, fopen($_file['tmp_name'], 'r+'));

            $element->setFileObject($file);
            $this->getEntityManager()->persist($file);
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                'File cannot be saved.', 0, $e
            );
        }

        return $file;
    }

    /**
     * @param File $file
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getThumbImage(File $file, $width = 200, $height = 200)
    {
        $sourceImage = $file->getSavePath();
        $resultImage = $file->getSavePath() . '-' . $width . '-' . $height . '.' . $file->getFileExtension();

        if (!$this->getCacheFilesystem()->has($resultImage)) {
            try {
                $_file = $this->getFilesystem()->read($sourceImage);
                $image = WideImage::load($_file);
                $image_data = $image->resize($width, $height, 'outside')
                                    ->crop('center', 'center', $width, $height)
                                    ->asString($file->getFileExtension());

                $this->getCacheFilesystem()->write($resultImage, $image_data);
            } catch (\Exception $e) {
                throw new Exception\RuntimeException(
                    'File cannot be saved.', 0, $e
                );
            }
        }

        $fsAdapter = $this->getCacheFilesystem()->getAdapter();

        return str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', realpath($fsAdapter->getPathPrefix() . $resultImage)));
    }

    /**
     * @param array $keywords
     * @return \Adminaut\Entity\File
     */
    protected function addKeywordsToFile(array $keywords)
    {
        if (!empty($keywords)) {
            $keywordEntities = array();
            foreach ($keywords as $word) {
                $keyword = new FileKeyword();
                $keyword->setValue(strtolower($word));
                $keyword->setFile($this->file);
                $this->em->persist($keyword);
                $keywordEntities[] = $keyword;
            }
            $this->file->setKeywords($keywordEntities);
        }
        return $this->file;
    }

    /**
     * @param $path
     * @param $mode
     * @param $isFileIncluded
     * @throws \Adminaut\Exception\RuntimeException
     */
    protected function createPath($path, $mode, $isFileIncluded)
    {
        $success = true;
        if (!is_dir(dirname($path))) {
            if ($isFileIncluded) {
                $success = mkdir(dirname($path), $mode, true);
            } else {
                $success = mkdir($path, $mode, true);
            }
        }
        if (!$success) {
            throw new Exception\RuntimeException('Can\'t create file manager storage folders');
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return Filesystem
     */
    public function getCacheFilesystem()
    {
        return $this->cacheFilesystem;
    }

    /**
     * @param Filesystem $cacheFilesystem
     */
    public function setCacheFilesystem($cacheFilesystem)
    {
        $this->cacheFilesystem = $cacheFilesystem;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }
}