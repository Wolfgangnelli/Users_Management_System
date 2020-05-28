<?php
session_start();

require_once '../functions.php';
require '../model/User.php';

//VALIDO PER TUTTI GLI UTENTI e AZIONI
//5. prelevo i parametri che mi sono passato nella url, eliminando action e id che non mi serve più nei params di ritorno
$params = $_GET;
unset($params['action']);
unset($params['id']);
//6. ricostruisco la query string con i parametri
$queryString = http_build_query($params, '&amp;');

$action = getParam('action', '');
switch ($action) {
    case 'delete':
        if (!userCanDelete()) {
            break;
        }
        //1.prendo id utente da eliminare
        $id = getParam('id', 0);
        //8.prendo dati utente per poi cancellare suo avatar
        $userData = getUser($id);
        //2.uso funzione php, creata in User.php, che possa cancellare un record lato DB
        $res = delete($id);
        //9.elimino avatar
        if ($res) {
            removeOldAvatar($id, $userData);
        }
        //3. messaggio conferma cancellazione record e redirect 
        $message = $res ? 'USER ' . $id . ' DELETED' : 'ERROR DELETING USER ' . $id;
        //4. (per passare il messaggio posso sia via URL che usando la SESSION)
        $_SESSION['message'] = $message;
        //inserisco altra variabile per vedere se il messaggio è di successo
        $_SESSION['success'] = $res;
        //7. una volta cancellato il record devo tornare alla pagina index passando la queryString
        header('Location:../index.php?' . $queryString);
        break;
        //per l'insert uso save
    case 'save':
        if (!userCanUpdate()) {
            break;
        }
        $data = $_POST;
        $res = saveUser($data);

        //gestione dei messaggi
        if ($res['id'] > 0) {
            $resCopy = copyAvatar($res['id']);
            if ($resCopy['success']) {
                updateUserAvatar($res['id'], $resCopy['filename']);
            }
            $message = 'USER INSERTED WITH ID ' . $res['id'] . ' INSERTED';
        } else {
            $message = 'ERROR INSERTING USER ' . $data['username'] . ':' . $res['message'];
        }
        $_SESSION['message'] = $message;
        $_SESSION['success'] = $res;
        header('Location:../index.php?' . $queryString);

        break;

    case 'store':
        if (!userCanUpdate()) {
            break;
        }
        $data = $_POST;
        $id = getParam('id', 0);
        //var_dump($_FILES); die;
        $resCopy = copyAvatar($id);

        if ($resCopy['success']) {
            removeOldAvatar($id);
            $data['avatar'] = $resCopy['filename'];
        }

        $res = storeUser($data, $id);
        //gestione dei messaggi
        if ($res['success']) {
            $message = 'USER ' . $id . ' UPDATED';
        } else {
            $message = 'ERROR UPDATING USER ' . $id . ':' . $res['error'];
        }
        //gestione risultato copy
        if (!$resCopy['success']) {
            $message .= $resCopy['message'];
        }

        $_SESSION['message'] = $message;
        $_SESSION['success'] = $res;
        header('Location:../index.php?' . $queryString);

        break;

    default:
}
