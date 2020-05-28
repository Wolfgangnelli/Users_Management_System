<?php

/**
 * Elimina un utente dal database
 */
function delete(int $id)
{
    /**
     * @var $conn mysqli
     */

    $conn = $GLOBALS['mysqli'];

    $sql = "DELETE FROM users WHERE id = $id";

    $res = $conn->query($sql);
    return $res && $conn->affected_rows;
}


function getUser(int $id)
{
    /**
     * @var $conn mysqli
     */

    $conn = $GLOBALS['mysqli'];

    $result = [];

    $sql = "SELECT * FROM users WHERE id = $id";

    $res = $conn->query($sql);
    if ($res && $res->num_rows) {
        $result = $res->fetch_assoc();
    }

    return $result;
}


function storeUser(array $data, int $id)
{
    /**
     * @var $conn mysqli
     */
    $result = [
        'success' => 1,
        'affectedRows' => 0,
        'error' => ''
    ];

    $conn = $GLOBALS['mysqli'];
    $username = $conn->escape_string($data['username']);
    $email =  $conn->escape_string($data['email']);
    $fiscalcode =  $conn->escape_string($data['fiscalcode']);
    $age =  (int) ($data['age']);
    $avatar = $data['avatar'];

    $sql = 'UPDATE users SET ';
    $sql .= "username='$username', email='$email', fiscalcode='$fiscalcode', age='$age', avatar='$avatar'";

    if ($data['password']) {
        $data['password'] = $data['password'] ?? 'testuser';
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $sql .= ", password='$password'";
    }
    if ($data['roletype']) {
        $roletype = in_array($data['roletype'], getConfig('roletypes', [])) ? $data['roletype'] : 'user';
        $sql .= ", roletype='$roletype'";
    }
    $sql .= " WHERE id='$id'";


    $res = $conn->query($sql);
    if ($res) {

        $result['affectedRows'] = $conn->affected_rows;
    } else {
        $result['success'] = false;
        $result['error'] = $conn->error;
    }
    return $result;
}

/**
 * Save new user in database
 * @param array $data
 */
function saveUser(array $data)
{
    /**
     * @var $conn mysqli
     */
    $result = [
        'id' => 0,
        'success' => false,
        'message' => 'PROBLEM SAVING USER',
    ];

    $conn = $GLOBALS['mysqli'];
    $username = $conn->escape_string($data['username']);
    $email =  $conn->escape_string($data['email']);
    $fiscalcode =  $conn->escape_string($data['fiscalcode']);
    $age = (int) ($data['age']);
    $data['password'] = $data['password'] ?? 'testuser';
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $roletype = in_array($data['roletype'], getConfig('roletypes', [])) ? $data['roletype'] : 'user';

    $sql = 'INSERT INTO users (username, email, fiscalcode, age, password, roletype) ';
    $sql .= "VALUES ('$username', '$email', '$fiscalcode', '$age', '$password', '$roletype')";

    $res = $conn->query($sql);
    if ($res && $conn->affected_rows) {
        $result['id'] = $conn->insert_id;
        $result['success'] = true;
    } else {

        $result['message'] = $conn->error;
    }
    return $result;
}


/**
 * Aggiorna avatar nuovo utente
 */
function updateUserAvatar(int $id, string $avatar = null)
{
    if (!$avatar) {
        return false;
    }

    $conn = $GLOBALS['mysqli'];
    $avatar = $conn->escape_string($avatar);
    $sql = 'UPDATE users SET ';
    $sql .= "avatar='$avatar' WHERE id='$id'";

    $res = $conn->query($sql);
    return $res && $conn->affected_rows;
}


/**
 * Verifico se email si trova nel DB
 */
function getUserByEmail(string $email)
{
    /**
     * @var $conn mysqli
     */

    $conn = $GLOBALS['mysqli'];

    $result = [];

    //piccolo controllo su email
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$email) {
        return $result;
    }
    //faccio anche escape con mysqli
    $email = mysqli_escape_string($conn, $email);

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $res = $conn->query($sql);
    if ($res && $res->num_rows) {
        $result = $res->fetch_assoc();
    }

    return $result;
}
