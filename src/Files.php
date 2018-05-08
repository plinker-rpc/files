<?php
namespace Plinker\Files {

    /**
     * Files Manager Class
     */
    class Files
    {
        public $config = array();

        /**
         * Construct
         *
         * @param array $config
         */
        public function __construct(array $config = array())
        {
            $this->config = $config;
        }
        
        /**
         * List files
         *
         * @param string $dir      Base path to list files and folders from
         * @param bool   $extended Return extended fileinfo
         * @param int    $depth    Iterator depth
         */
        public function list($dir = './', $extended = false, $depth = 10)
        {
            if (!file_exists($dir) || !is_dir($dir) || !is_readable($dir)) {
                throw new \Exception('Folder does not exist or is not readable.');
            }

            // recursive iterator
            $it  = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            // maximum depth
            $it->setMaxDepth($depth);

            // Basic loop displaying different messages based on file or folder
            $i = 0;
            foreach ($it as $fileinfo) {
                $curDir = (empty($it->getSubPath()) ? '' : $it->getSubPath());
                $key = '/'.str_replace(['//', '/.'], ['/', '.'], $curDir);
                
                // basic
                $return[$key][$i] = array(
                    "name" => $fileinfo->getFilename(),
                    "type" => ($fileinfo->isDir() ? "folder" : "file"),
                    "size" => $fileinfo->getSize()
                );
                
                // extended
                if ($extended) {
                    $return[$key][$i]["info"] = [
                        "last_access" => $fileinfo->getATime(),
                        "change_time" => $fileinfo->getCTime(),
                        "modified_time" => $fileinfo->getMTime(),
                        "basename" => $fileinfo->getBasename(),
                        "extension" => $fileinfo->getExtension(),
                        "filename" => $fileinfo->getFilename(),
                        "group" => $fileinfo->getGroup(),
                        "owner" => $fileinfo->getOwner(),
                        "inode" => $fileinfo->getInode(),
                        "path" => $fileinfo->getPath(),
                        "pathname" => $fileinfo->getPathname(),
                        "size" => $fileinfo->getSize(),
                        "type" => $fileinfo->getType(),
                        "isDir" => $fileinfo->isDir(),
                        "isExecutable" => $fileinfo->isExecutable(),
                        "isFile" => $fileinfo->isFile(),
                        "isLink" => $fileinfo->isLink(),
                        "readable" => $fileinfo->isReadable(),
                        "writable" => $fileinfo->isWritable()
                    ];
                }
                
                $i++;
            }

            return $return;
        }
        
        /**
         * Put file
         *
         * @param string  $path     File path
         * @param string  $contents Contents of the file
         * @param int     $flags    File operations flags
         * @return int
         */
        public function put($path = '', $contents = '', $flags = 0)
        {
            return file_put_contents($path, $contents, $flags);
        }
        
        /**
         * Get file
         *
         * @param string  $path     File path
         * @return bool|mixed
         */
        public function get($path = '')
        {
            if (file_exists($path)) {
                return file_get_contents($path);
            }
            return false;
        }

        /**
         * Delete file
         *
         * @param string  $path     File path
         * @return null
         */
        public function delete($path = '')
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
