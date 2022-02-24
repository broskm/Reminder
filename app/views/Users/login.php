<?php
require_once APPROOT . '/views/includes/head.php';

?>

<body>
    <section class="login">
        <form class="login-form" action="<?php echo URLROOT; ?>/Users/login" method="POST">
            <p class="welcome">Log in to get started</p>
            <div class="alertsbox">
                <?php
                if (!empty($data['Error'])) {
                    foreach ($data['Error'] as $error) : ?>
                        <div class=" not-ok-alert"><?php echo $error ?></div>
                <?php endforeach;}
                elseif($data['successful'] == true){?>
                <div class=" ok-alert"><?php echo "Registered Successfully!";}?></div>
            </div>
            <input type="text" name="username" placeholder="Username" value="<?php echo $data['username']; ?>" class="login__input login__input--user" />
            <input type="password" name="password" placeholder="Password" class="login__input login__input--pin" />
            <button class="login__btn">&rarr;</button>
            <div class="create-account">
                <p>Not a member? <a href="<?php echo URLROOT; ?>/Users/register">Create Account</a></p>
            </div>
        </form>
        <div class="guest-login">
            <h3>Login as a guest:</h3>
            <p class="username"><strong>Username:</strong> guest</p>
            <p class="password"><strong>Password:</strong> g1234567</p>
            <h6>NOTE: Email reminder is deactivated as guest</h6>
        </div>
    </section>



<?php
require_once APPROOT . '/views/includes/foot.php';
?>