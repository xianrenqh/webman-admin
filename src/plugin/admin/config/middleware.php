<?php

return [
    '' => [
        \plugin\admin\app\middleware\AccessControl::class,
        \plugin\admin\app\middleware\SystemLogControl::class,
    ]
];
