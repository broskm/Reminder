<?php
require_once APPROOT . '/views/includes/head.php';


?>

<body>
    <section class="register">
        <form class="register-form" action="<?php echo URLROOT; ?>/Users/register" method="POST">
            <p class="welcome">Register:</p>
            <div class="alertsbox">
            <?php
            if (!empty($data['Error'])) {
                foreach ($data['Error'] as $error) : ?>
                    <div class="not-ok-alert" role="alert">
                        <?php echo $error ?>
                    </div>
            <?php endforeach;
            } ?></div>
            <input type="email" name="email" value="<?php echo $data['email']; ?>" placeholder="Email" class="login__input login__input--Email" />
            <input type="text" placeholder="User name" value="<?php echo $data['username']; ?>" name="username" class="login__input login__input--user" />
            <input type="password" name="password" placeholder="Password" class="login__input login__input--pin" />
            <input type="password" name="confirmPassword" placeholder="Repeat-Password" class="login__input login__input--pin" />
            <button class="register__btn">Register</button>
            <div class="create-account">
                <p>Back to <a href="<?php echo URLROOT; ?>/Users/login">login</a></p>
            </div>
        </form>
    </section>

    <?php
    require_once APPROOT . '/views/includes/foot.php';

    ?>