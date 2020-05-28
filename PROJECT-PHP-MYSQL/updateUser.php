<?php
session_start();
require_once 'functions.php';
if (!isUserLoggedin()) {
    header('Location: login.php');
}
if (!userCanUpdate()) {
    header('Location: index.php');
}

require_once 'headerInclude.php';
?>
<main role="main" class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5 text-center p-2 font-weight-bold text-danger">USER MANAGEMENT SYSTEM</h1>

        <?php
        $id = getParam('id', 0);
        $action = getParam('action', '');
        $orderBy = getParam('orderBy');
        $orderDir = getParam('orderDir', 'ASC');
        $search = getParam('search', '');
        $page = getParam('page', 1);

        $defaultParams = http_build_query(compact('orderBy', 'orderDir', 'page', 'search'), '', '&amp;');

        if ($id) {
            $user = getUser($id);
        } else {
            $user = [
                'username' => '',
                'email' => '',
                'fiscalcode' => '',
                'age' => '',
                'id' => '',
                'avatar' => '',
                'password' => '',
                'roletype' => 'user'
            ];
        }

        require_once 'view/formUpdate.php';
        ?>

    </div>
</main>

<?php
require_once 'view/footer.php';
