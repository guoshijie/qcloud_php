<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once './src/QcloudApi/QcloudApi.php';


$config = array('SecretId'       => 'AKIDCcU7DB3HdvnYqspxLosnLF9HnJkI8gn3',
                'SecretKey'      => 'y2YmUlKVPbWmm0fQbxmEGz0Nj3wgWysM',
                'RequestMethod'  => 'POST',
                'DefaultRegion'  => 'gz');

$wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);

$package = array("content"=>"李亚鹏挺王菲：加油！孩儿他娘。");

$a = $wenzhi->TextSentiment($package); 

if ($a === false) {
    $error = $wenzhi->getError();
    echo "Error code:" . $error->getCode() . ".\n";
    echo "message:" . $error->getMessage() . ".\n";
    echo "ext:" . var_export($error->getExt(), true) . ".\n";
} else {
    var_dump($a);
}

echo "\nRequest :" . $wenzhi->getLastRequest();
echo "\nResponse :" . $wenzhi->getLastResponse();
echo "\n";