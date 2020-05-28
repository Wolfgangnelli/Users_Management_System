<?php

require_once 'connection.php';

function getConfig($param, $default = null)
{
    $config = require 'config.php';
    return array_key_exists($param, $config) ? $config[$param] : $default;
}

function getParam($param, $default = null)
{
    return (!empty($_REQUEST[$param]) ? $_REQUEST[$param] : $default);
}


//Qui scrivo delle funzioni che mi aiuteranno sia nell'inserimento dei dati dell'utente, connessione al db, inserire dati random, aggiornare o cancellare leggere utenti ecc..





function getRandname()
{
    $names = [
        'ROBERTO', 'GIOVANNI', 'MARIO', 'GIULIA', 'GIACOMINO', 'LEX'
    ];
    $lastnames = [
        'ROSSI', 'RE', 'ARIAS', 'SMITH', 'TURCI', 'LUTOR'
    ];

    return  $names[mt_rand(0, count($names) - 1)] . ' ' . $lastnames[mt_rand(0, count($lastnames) - 1)];
}



/**
 *  genero email random
 */
function getRandEmail($name)
{

    $domains = ['google.com', 'yahoo.com', 'hotmail.it', 'libero.it'];

    return strtolower(str_replace(' ', '.', $name) . mt_rand(10, 99) . '@' . $domains[mt_rand(0, count($domains) - 1)]);
}



/**
 * genero codice fiscale random
 */
function getRandFiscalCode()
{
    $i = 16;
    $res = '';

    while ($i > 0) {

        $res .= chr(mt_rand(65, 90));
        $i--;
    }
    return $res;
}



//genero età random 
function getRandAge()
{
    return mt_rand(0, 120);
}


// INSERIRE UTENTI RANDOM (far interagire php con mysql)
function insertRandUser($total, mysqli $connection)
{
    while ($total > 0) {
        $username = getRandname();
        $email = getRandEmail($username);
        $fiscalcode = getRandFiscalCode();
        $age = getRandAge();


        $sql = 'INSERT INTO users (username, email, fiscalcode, age) VALUES ';
        $sql .= "('$username', '$email', '$fiscalcode', $age)";
        echo $total . ' ' . $sql . "<br>";


        $res = $connection->query($sql);

        if (!$res) {

            echo $connection->error . '<br>';
        } else {
            $total--;
        }
    }
}


function getUsers(array $params = [])
{
    /**
     * @var $conn mysqli
     */

    $conn = $GLOBALS['mysqli'];
    $orderBy = array_key_exists('orderBy', $params) ? $params['orderBy'] : 'id';
    $orderDir = array_key_exists('orderDir', $params) ? $params['orderDir'] : 'ASC';
    $limit = (int) array_key_exists('recordsPerPage', $params) ? $params['recordsPerPage'] : 10;

    $page = (int) array_key_exists('page', $params) ? $params['page'] : 0;

    $start = $limit * ($page - 1);
    if ($start < 0) {
        $start = 0;
    }

    $search = array_key_exists('search', $params) ? $params['search'] : '';
    $search = $conn->escape_string($search);

    if ($orderDir !== 'ASC' && $orderDir !== 'DESC') {
        $orderDir = 'ASC';
    }

    $records = [];


    $sql = 'SELECT * FROM users';
    if ($search) {
        $sql .= " WHERE username LIKE '%$search%'";
        $sql .= " OR fiscalcode LIKE '%$search%'";
        $sql .= " OR email LIKE '%$search%'";
        $sql .= " OR age LIKE '%$search%'";
        $sql .= " OR id LIKE '%$search%'";
    }
    $sql .= " ORDER BY $orderBy $orderDir LIMIT $start, $limit";

    $res = $conn->query($sql);
    if ($res) {

        while ($row = $res->fetch_assoc()) {

            $records[] = $row;
        }
    } else {
        die($conn->error);
    }
    return $records;
}



function countUsers(array $params = [])
{

    $conn = $GLOBALS['mysqli'];
    $orderDir = array_key_exists('orderDir', $params) ? $params['orderDir'] : 'ASC';
    $search = array_key_exists('search', $params) ? $params['search'] : '';

    $search = $conn->escape_string($search);


    if ($orderDir !== 'ASC' && $orderDir !== 'DESC') {
        $orderDir = 'ASC';
    }

    $total = 0;


    $sql = 'SELECT COUNT(*) as total FROM users';
    if ($search) {
        $sql .= " WHERE username LIKE '%$search%'";
        $sql .= " OR fiscalcode LIKE '%$search%'";
        $sql .= " OR email LIKE '%$search%'";
        $sql .= " OR age LIKE '%$search%'";
        $sql .= " OR id LIKE '%$search%'";
    }

    $res = $conn->query($sql);
    if ($res) {

        $row = $res->fetch_assoc();
        $total = $row['total'];
    } else {
        die($conn->error);
    }
    return $total;
}


/**
 * Verifica caricamento file e gestione degli errori
 */
function copyAvatar(int $userId)
{

    $result = [
        'success' => false,
        'message' => 'PROBLEM SAVING IMAGE',
        'filename' => ''
    ];
    if (empty($_FILES)) {
        $result['message'] = 'NO FILE UPLOADED';
        return $result;
    }

    $FILE = $_FILES['avatar'];

    if (!is_uploaded_file($FILE['tmp_name'])) {
        $result['success'] = true;
        $result['message'] = '';

        return $result;
    }

    $finfo = finfo_open(FILEINFO_MIME);
    $info = finfo_file($finfo, $FILE['tmp_name']);

    if (stristr($info, 'image/jpeg') === false) {
        $result['message'] = 'THE UPLOADED FILE IS NOT JPG';
        return $result;
    }

    $maxSize = getConfig('maxFileUpload');
    if ($FILE['size'] > $maxSize) {
        $result['message'] = 'THE UPLOADED FILE IS TOO BIG. MAX SIZE IS ' . $maxSize;
        return $result;
    }

    $fileName = $userId . '_' . str_replace('.', '', microtime(true));
    $fileName .= '.jpg';
    $avatarDir = getConfig('avatarDir');

    if (!move_uploaded_file($FILE['tmp_name'], $avatarDir . $fileName)) {
        $result['message'] = 'COULD NOT MOVE UPLOADED FILE';
        return $result;
    }


    $newImg = imagecreatefromjpeg($avatarDir . $fileName);
    if (!$newImg) {
        $result['message'] = 'COULD NOT CREATE THUMBNAIL RESOURCE';
    }

    $thumbNailImag = imagescale($newImg, getConfig('thumbnail_width', 120));
    $previewImg = imagescale($newImg, getConfig('previewimg_width', 400));
    if (!$thumbNailImag) {
        $result['message'] = 'COULD NOT SCALE THUMBNAIL RESOURCE';
    }

    imagejpeg($thumbNailImag, $avatarDir . 'preview_' . $fileName);
    imagejpeg($previewImg, $avatarDir . 'thumb_' . $fileName);


    $result['filename'] = $fileName;
    $result['success'] = 1;
    $result['message'] = '';
    return $result;
}

/**
 * Delete prev avatar file and thumbnail
 */
function removeOldAvatar(int $id, array $userData = null)
{

    $userData = $userData ?: getUser($id);
    if (!$userData || !$userData['avatar']) {
        return;
    }

    $avatarFolder = getConfig('avatarDir');
    $filename = $avatarFolder . $userData['avatar'];
    if (file_exists($filename)) {
        unlink($filename);
    }

    $filenameThumb = $avatarFolder . 'thumb_' . $userData['avatar'];
    if (file_exists($filenameThumb)) {
        unlink($filenameThumb);
    }
}

/**
 * Verifica login dello user
 */
function verifyLogin($email, $password, $token)
{
    require_once 'model/User.php';

    $result = [
        'message' => 'USER LOGGED IN',
        'success' => true
    ];
    //verifico token
    if ($token !== $_SESSION['csrf']) {

        $result = [
            'message' => 'TOKEN MISMATCH',
            'success' => false
        ];
        return $result;
    }
    //verifico email
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $result = [
            'message' => 'WRONG EMAIL',
            'success' => false
        ];
        return $result;
    }
    //verifico password
    if (strlen($password) < 6) {
        $result = [
            'message' => 'PASSWORD TO SMALL',
            'success' => false
        ];
        return $result;
    }

    //leggo user dal DB mediante la email che è unique nel mio caso per ogni user
    $resEmail = getUserByEmail($email);
    if (!$resEmail) {
        $result = [
            'message' => 'USER NOT FOUND',
            'success' => false
        ];
        return $result;
    }
    if (!password_verify($password, $resEmail['password'])) {
        $result = [
            'message' => 'WRONG PASSWORD',
            'success' => false
        ];
        return $result;
    }

    $result['user'] = $resEmail;

    return $result;
}

// HELPER FUNCTIONS

function isUserLoggedin()
{
    return $_SESSION['loggedin'] ?? false;
}

function getUserLoggedInFullname()
{
    return $_SESSION['userData']['username'] ?? '';
}

function getUserRole()
{
    return $_SESSION['userData']['roletype'] ?? '';
}

function gerUserEmail()
{
    return $_SESSION['userData']['email'] ?? '';
}


function isUserAdmin()
{
    return getUserRole() === 'admin';
}

function userCanUpdate()
{
    $role = getUserRole();
    return $role === 'admin' || $role === 'editor';
}

function userCanDelete()
{
    return isUserAdmin();
}
