<?php
require_once 'functions.php';
require_once 'model/User.php';
require_once 'view/top.php';
$pageUrl = $_SERVER['PHP_SELF'];
$updateUrl = 'updateUser.php';
$deleteUrl = 'controller/updateRecord.php';
$orderDir = getParam('orderDir', 'ASC');

$orderBy = getParam('orderBy', 'id');
$orderByColumns = getConfig('orderByColumns', ['id', 'email', 'fiscalcode', 'age', 'username', 'roletype']);
$recordsPerPage = getParam('recordsPerPage', getConfig('recordsPerPage'));
$recordsPerPageOptions = getConfig('recordsPerPageOptions', [5, 10, 20, 30, 50]);
$search = getParam('search', '');
$page = getParam('page', 1);
require_once 'view/topbar.php';
