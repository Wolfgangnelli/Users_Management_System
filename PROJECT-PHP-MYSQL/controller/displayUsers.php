<?php

if (!in_array($orderBy, getConfig('orderByColumns'))) {
    $orderBy = 'id';
}
$params = [
    'orderBy' => $orderBy,
    'orderDir' => $orderDir,
    'recordsPerPage' => $recordsPerPage,
    'search' => $search,
    'page' => $page
];

$orderByParams = $orderByNavigator = $params;
unset($orderByParams['orderBy']);
unset($orderByParams['orderDir']);
unset($orderByNavigator['page']);

$orderByQueryString = http_build_query($orderByParams, '', '&amp;');
$orderByNavQueryString = http_build_query($orderByNavigator, '', '&amp;');


$totalUsers = countUsers($params);
$numPages = ceil($totalUsers / $recordsPerPage);
$users = getUsers($params);

require_once 'view/usersList.php';
