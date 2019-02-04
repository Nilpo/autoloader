<?php
//define default json file name who contains all the PHP files inside it.
define('AUTOLOADER_PHP_FILES_JSON', 'autoloaderfiles.json');

class autoloader
{

    public static function instance()
    {
        static $instance = false;
        if ($instance === false) {
            // Late static binding
            $instance = new static();
        }

        return $instance;
    }


    /**
     * Register autoloader.
     * @param $directoryLevel
     * @param bool $debugMode will be try for new php files added in developing time.
     * @param string $fileExtension specific file Extension
     */
    public function register($directoryLevel, $debugMode = false, $fileExtension = ".php")
    {
        if ($directoryLevel != null) {
            new spl_registrar($directoryLevel, $debugMode, $fileExtension);
        }
    }

    /**
     * @param $dir_level : directory level is for file searching
     * @param $php_files_json_directory : the json file who contains all the PHP files inside it
     * @param $file_extension : our specific extension for files Default is .php
     */
    private function export_php_files($dir_level, $php_files_json_directory, $file_extension)
    {

        //save update time in array
        $filePaths = array(mktime());

        /**Get all files and directories using recursive iterator.*/
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir_level, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        /**Add all of iterated files in array */
        while ($iterator->valid()) {

            $path = strval($iterator->current());

            if (stripos(pathinfo($path, PATHINFO_BASENAME), $file_extension)) {
                $filePaths[] = str_replace($dir_level, '', $path);
            }
            //while have next maybe throw an exception.
            try {
                $iterator->next();
            } catch (Exception $ignored) {
                var_dump($ignored);
                break;
            }
        }


        /**Encode and save php files dir in a local json file */
        $fileOpen = fopen($php_files_json_directory, 'w');
        fwrite($fileOpen, json_encode($filePaths));
        fclose($fileOpen);
    }

    /**
     * @param $dir_level
     * @param $php_files_json_directory : the json file who contains all the PHP files inside it
     * @param $class_file_name : name of the class that was taken from @spl_autoload_register plus .php extension
     * @return bool Succeeding end of work
     */
    private function include_matching_files($dir_level, $php_files_json_directory, $class_file_name)
    {
        $inc_is_done = false;

        //prevent opening file each time with making these variables global.
        global $php_files_array;
        global $php_files_last_directory;

        if ($php_files_array == null || $php_files_json_directory != $php_files_last_directory) {
            $php_files_last_directory = $php_files_json_directory;
            $php_files_array = json_decode(file_get_contents($php_files_json_directory), false);
        }

        /**Include matching files here.*/
        foreach ($php_files_array as $path) {
            if (stripos($path, $class_file_name) !== false) {
                require_once $dir_level . $path;
                $inc_is_done = true;
            }
        }
        return $inc_is_done;
    }

    /**
     * @param $dir_level : directory level is for file searching
     * @param $class_name : name of the class that was taken from @spl_autoload_register
     * @param bool $try_for_new_files : Try again to include new files, that this feature is @true in development mode
     * it will renew including file each time after every 10 seconds @see $refresh_time.
     * @param $file_extension
     * @return bool : Succeeding end of work
     */
    protected function request_system_files($dir_level, $class_name, $try_for_new_files, $file_extension)
    {
        //Applying PSR-4 standard for including system files :
        $php_files_json_directory = $dir_level . DIRECTORY_SEPARATOR . AUTOLOADER_PHP_FILES_JSON;
        $class_file_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . $file_extension;
        $files_refresh_time = 10;

        /**Include required php files.*/
        if (is_file($php_files_json_directory)) {


            if (!$try_for_new_files) {

                return $this->include_matching_files($dir_level, $php_files_json_directory, $class_file_name);

            } else {

                //Only in developing mode loader checks for @param $files_refresh_time seconds to renew files or not.
                $last_update = json_decode(file_get_contents($php_files_json_directory), false)[0];
                if ((mktime() - intval($last_update)) < $files_refresh_time) {
                    return $this->include_matching_files($dir_level, $php_files_json_directory, $class_file_name);
                }

            }
        }

        $this->export_php_files($dir_level, $php_files_json_directory, $file_extension);

        return $this->include_matching_files($dir_level, $php_files_json_directory, $class_file_name);

    }

    /**
     * Make constructor private, so nobody can call "new Class".
     */
    private function __construct()
    {
    }

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone()
    {
    }

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep()
    {
    }

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup()
    {
    }

}

/**
 * @spl_registrar this object is a registrar for spl_autoload_register
 */
class spl_registrar extends autoloader
{
    /**
     * @var string
     */
    private $file_extension;
    private $directoryLevel;
    private $debug;

    protected function __construct($directoryLevel, $debugMode, $fileExtension)
    {
        $this->debug = $debugMode;
        $this->directoryLevel = $directoryLevel;
        $this->file_extension = $fileExtension;

        try {
            spl_autoload_register("self::load", true, false);
        } catch (Exception $e) {
            var_dump($e);
            die;
        }

    }

    public function load($className)
    {
        return $this->request_system_files(
            $this->directoryLevel,
            $className,
            $this->debug,
            $this->file_extension
        );
    }
}

