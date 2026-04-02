<?php
// PHP script to initialize git in execution phase...
$output = shell_exec('git init 2>&1');
file_put_contents('git_log.txt', $output);
echo "Git Init Done";
