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
         *
         */
        public function files(array $params = array())
        {
            $dir = $params[0];
            
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
            $it->setMaxDepth(100);

            // Basic loop displaying different messages based on file or folder
            foreach ($it as $fileinfo) {
                $curDir = (empty($it->getSubPath()) ? '' : $it->getSubPath());

                if ($fileinfo->isDir()) {
                    $return['/'.str_replace(['//', '/.'], ['/', '.'], $curDir)][] = array(
                        "name" => $fileinfo->getFilename(),
                        "type" => "folder",
                    );
                    //$return[str_replace(['//', '/.'], ['/', '.'], $curDir.'/'.$fileinfo->getFilename())] = array();
                } elseif ($fileinfo->isFile()) {
                    $return['/'.str_replace(['//', '/.'], ['/', '.'], $curDir)][] = array(
                        "name" => $fileinfo->getFilename(),
                        "type" => "file",
                    );
                }
            }

            return $return;
        }

        /**
         *
         */
        public function getFile(array $params = array())
        {
            if (file_exists($params[0])) {
                return base64_encode(file_get_contents($params[0]));
            } else {
                // create file
                file_put_contents($params[0], '');
                return base64_encode(file_get_contents($params[0]));
            }
        }

        /**
         *
         */
        public function deleteFile(array $params = array())
        {
            if (file_exists($params[0])) {
                unlink($params[0]);
                return base64_encode(true);
            } else {
                return base64_encode(true);
            }
        }

        /**
         *
         */
        public function saveFile(array $params = array())
        {
            if (file_exists($params[0]) || is_writable(dirname($params[0]))) {
                file_put_contents($params[0], base64_decode(@$params[1]));
                return base64_encode(true);
            } else {
                return base64_encode(true);
            }
        }
    }

}
