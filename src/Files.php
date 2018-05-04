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
         * @param string $dir      Base paste to list files and folders from
         * @param bool   $extended Return extended fileinfo
         * @param int    $depth    Iterator depth
         */
        public function list($dir = './', $extended = false, $depth = 10)
        {
            if (!file_exists($dir) || !is_dir($dir) || !is_readable($dir)) {
                return 'Folder does not exist or is not readable.';
            }

            // Create recursive dir iterator which skips dot folders
            $dir = new \RecursiveDirectoryIterator(
                $dir,
                \FilesystemIterator::SKIP_DOTS
            );

            // Flatten the recursive iterator, folders come before their files
            $it  = new \RecursiveIteratorIterator(
                $dir,
                \RecursiveIteratorIterator::SELF_FIRST
            );

            // Maximum depth is 1 level deeper than the base folder
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
         *
         */
        public function createFile($path = '', $contents = '')
        {
            return file_put_contents($path, $contents);
        }

        /**
         *
         */
        public function getFile($path = '')
        {
            if (file_exists($path)) {
                return file_get_contents($path);
            }
            return false;
        }

        /**
         *
         */
        public function deleteFile($path = '')
        {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
