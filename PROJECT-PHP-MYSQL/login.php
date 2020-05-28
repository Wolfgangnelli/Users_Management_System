<?php
session_start();
require_once 'functions.php';
if (isUserLoggedin()) {
    header('Location: index.php');
}

$bytes = random_bytes(32);
$token = bin2hex($bytes);
$_SESSION['csrf'] = $token;

require_once 'view/top.php';

?>

<section class="container mt-5">
    <div class="col-8 mx-auto" id="loginForm">
        <h1 class="text-center font-weight-bold">LOGIN</h1>
        <?php
        if (!empty($_SESSION['message'])) : ?>

            <div class="alert alert-info" id="message"><?= $_SESSION['message'] ?></div>
        <?php
            $_SESSION['message'] = '';
        endif;
        ?>
        <form action="verify-login.php" method="POST">
            <input type="hidden" name="_csrf" value="<?= $token ?>">
            <fieldset>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="rememberme" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </fieldset>
        </form>
    </div>
</section>


<?php

require_once 'view/footer.php';
?>
<script>
    $(
        function() {
            $('form').on('submit', function(evt) {
                evt.preventDefault();
                const data = $(this).serialize();
                $.ajax({
                    method: 'post',
                    data: data,
                    url: 'verify-login-ajax.php',
                    success: function(res) {
                        const data = JSON.parse(res);
                        if (data) {
                            alert(data.message);
                            if (data.success) {
                                location.href = 'index.php';
                            }
                        }
                    },
                    failure: function() {
                        alert('PROBLEM CONTACTING SERVER');
                    }
                })
            })

        }
    )
</script>