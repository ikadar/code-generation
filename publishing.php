<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 19.
 * Time: 23:17
 */

use GitWrapper\GitWrapper;

require __DIR__ . '/vendor/autoload.php';

$gitWrapper = new GitWrapper();


//$git = $gitWrapper->cloneRepository('https://github.com/ikadar/wheeltest.git', 'gittest');
//die('OK');

$git = $gitWrapper->workingCopy('gittest');

$git->config('user.name', 'ikadar');
$git->config('user.email', 'odesk.kadari@gmail.com');

$textFile = 'text01.txt';

// Create a file in the working copy
touch('gittest/' . $textFile);

// Add it, commit it, and push the change
$git->add($textFile);
$git->commit('Added the ' . $textFile . ' file as per the examples.');
echo $git->push();

//var_dump($gitWrapper);