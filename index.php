<!doctype html>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Firefox's sessionstore.js reader</title>
        <style></style>
        <script></script>
    </head>
    <body>
        <pre>
<?php

define('DATETIME_FORMAT', 'Y-m-d H:i:s');

$file_content = file_get_contents('sessionstore.js');
$json_content = json_decode($file_content, true);

$startTime = new DateTime();
$startTime->setTimestamp($json_content['session']['startTime']);
$lastUpdate = new DateTime();
$lastUpdate->setTimestamp($json_content['session']['lastUpdate']);

$windows = array();
foreach ($json_content['windows'] as $window_data) {
    $windows[] = $window_data;
}

foreach ($json_content['_closedWindows'] as $window_data) {
    $windows[] = $window_data;
}

foreach ($windows as $window_data) {
    //var_dump($window_data);
}

?>
        </pre>
        <div>Session started on <strong><?php echo $startTime->format(DATETIME_FORMAT); ?></strong>
        and last updated on <strong><?php echo $lastUpdate->format(DATETIME_FORMAT); ?></strong>.</div>
<?php
    foreach ($windows as $window_data) {
        $closedAt = new DateTime();
        $closedAt->setTimestamp($window_data['closedAt']);
?>
        <h2>Window <?php echo $closedAt->format(DATETIME_FORMAT); ?></h2>
        <ol>
<?php
        foreach ($window_data['tabs'] as $tab_group) {
            $lastAccessed = new DateTime();
            $lastAccessed->setTimestamp($tab_group['lastAccessed']);

            $entry = array_shift($tab_group['entries']);
            $url = $entry['url'];
            $title = empty($entry['title']) ? $entry['url'] : $entry['title'];
?>
            <li>
                <a href="<?php echo $url; ?>"><?php echo $title; ?></a>
                <ul>
                    <li>Last accessed: <strong><?php echo $closedAt->format(DATETIME_FORMAT); ?></strong></li>
                </ul>
            </li>
<?php
        }
?>
        </ol>
<?php
    }
?>
    </body>
</html>