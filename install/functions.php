<?php
    error_reporting(0);

    if (!defined('ROOT')) {
        define('ROOT', strtr(realpath(dirname(dirname(__FILE__))), '\\', '/'));
    }

    if (!defined('WEBURL')) {
        define('WEBURL', substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], '/install/')));
    }

    if (!defined('CONFIG_KEY')) {
        define('CONFIG_KEY', 'config.php');
    }

    define('DB_CHARSET', 'utf8');
    define('DB_COLLATE', 'utf8_general_ci');

    function writeConfig($data)
    {
        $configText = dirname(__FILE__).'/config.txt';
        $configFile = ROOT.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.CONFIG_KEY;

        if (file_exists($configText)) {
            if ($TextHandle = @ fopen($configText, 'rb')) {
                $content = @ fread($TextHandle, filesize($configText));
                @ fclose($TextHandle);
                if ($content) {
                    $replace = array();
                    while (list($key, $value) = each($data)) {
                        if (is_scalar($value)) {
                            $replace['{'.$key.'}'] = "{$value}";
                        } elseif (is_array($value)) {
                            $replace['{'.$key.'}'] = var_export($value, true);
                        }
                    }
                    $content = str_replace(array_keys($replace), array_values($replace), $content);
                    if ($configHandle = @ fopen($configFile, 'wb')) {
                        $written = @ fwrite($configHandle, $content);
                        @ fclose($configHandle);
                    }
                }
            }
        }

        $perms = sprintf("%04o", 0666 & (0666 - umask()));
        if (is_string($perms)) {
            $perms = octdec($perms);
        }
        $chmodSuccess = @ chmod($configFile, $perms);
        if ($written) {
            return true;
        }

        return false;
    }

    function randomPassword()
    {
        $alphabet = "0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass); //turn the array into a string
    }
    function is_writable_deep($path)
    {
        $written = false;
        if (!is_string($path)) {
            return false;
        }

        if (is_file($path)) {
            $path = dirname($path).'/';
        }

        if (substr($path, strlen($path)-1) != '/') {
            $path .= '/';
        }
        $path = strtr($path, '\\', '/');

        $filePath = $path.uniqid().'.cache.php';

        $fp = @fopen($filePath, 'w');
        if ($fp === false || !file_exists($filePath)) {
            return false;
        }

        $written = @fwrite($fp, '<?php echo "webfairy CMS - www.webfairy.com";');
        if (!$written) {
            @fclose($fp);
            @unlink($filePath);

            return false;
        }

        @fclose($fp);
        $written = @unlink($filePath);

        return $written;
    }

    function stripDdFields($data){
    	$arr = array();
        foreach ($data as $key => $value) {
        	$arr["`{$key}`"] = $value;
        }  
        return $arr;      
    }

    function dbRowInsert($table_name, $data, $primary = null)
    {
        foreach ($data as $form_data) {
            $fields = array_keys($form_data);
            if (is_null($primary) == true) {
                $sql = 'INSERT INTO '.$table_name.' (`'.implode('`,`', $fields).'`) VALUES (\''.implode("','", array_map('mysql_real_escape_string',$form_data)).'\');';
                if(!mysql_query($sql)){
                    //echo $sql;
                }
            } else {
                if (mysql_num_rows(
                    mysql_query("SELECT * FROM `{$table_name}` WHERE `{$primary}` = '{$form_data[$primary]}'")
                ) == 0) {
                    mysql_query("INSERT INTO {$table_name} (`".implode('`,`', $fields)."`) VALUES ('".implode("','", $form_data)."');");
                }
            }
        }
    }
    function SplitSQL($file, $data = array(), $delimiter = ';')
    {
        @set_time_limit(0);

        $queries = array();

        $replace = array();

        foreach ($data as $key => $value) {
            $replace['['.$key.']'] = "{$value}";
        }

        if (is_file($file) === true) {
            $file = fopen($file, 'r');

            if (is_resource($file) === true) {
                $query = array();

                while (feof($file) === false) {
                    $query[] = fgets($file);

                    if (preg_match('~'.preg_quote($delimiter, '~').'\s*$~iS', end($query)) === 1) {
                        $query = trim(implode('', $query));

                        $queries[] = str_replace(array_keys($replace), array_values($replace), $query);
                    }

                    if (is_string($query) === true) {
                        $query = array();
                    }
                }

                fclose($file);
            }
        }

        return $queries;
    }

    function renameField($table, $old, $new)
    {
        return sprintf('ALTER TABLE %s CHANGE `%s` `%s` MEDIUMTEXT', $table, $old, $new);
    }

    function addFieldIfNotExists($table, $field, $after, $vars = '')
    {
        return sprintf('SET @s = (SELECT IF(
                (SELECT COUNT(*)
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE table_name = "%s"
                    AND table_schema = DATABASE()
                    AND column_name = "%s"
                ) > 0,
                "SELECT 1",
                "ALTER TABLE %s ADD %s %s AFTER `%s`"
            ));

            PREPARE stmt FROM @s;
            EXECUTE stmt;',
            $table, $field, $table, $field, $vars, $after
        );
    }

    function get_data_files()
    {
        return glob(dirname(__FILE__).'/data/*.php');
    }

    function dsn($host, $db)
    {
        return "mysql:host={$host};dbname={$db};";
    }
