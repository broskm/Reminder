<?php
require_once APPROOT . '/views/includes/head.php';


?>

<body>
    <?php echo "<input type='hidden' id='user_id' value='" . $_SESSION['user_id'] . "'/>";
    ?>
    <section class="create-reminder">
        <form class="create-reminder-form">
            <p class="welcome">Create reminder:</p>
            <div class="alertsbox"></div>
            <div class="input-group">
                <input type="text" id="title" placeholder="Title" class="login__input create-reminder--Title" />
            </div>
            <div class="input-group">
                <input type="text" id="date" placeholder="MM-DD" class="login__input create-reminder--date2" />
                <input type="date" id="date-picker" autofocus class="login__input create-reminder--date" />
            </div>
            <div class="input-group">
                <select name="remindme-beforee" id="remind_before" class="login__input create-reminder--Remindme-beforee">
                    <option value="" disabled selected>Remind before</option>
                    <option value="One day">One day</option>
                    <option value="Two days">Two days</option>
                    <option value="Four days">Four days</option>
                    <option value="One week">One week</option>
                    <option value="Two weeks">Two weeks</option>
                </select>
            </div>
            <div class="fullwidth"><button class="register__btn create-reminder-btn">Create</button></div>
        </form>
    </section>
    <section class="reminders-list reminders-list-big-screen">
        <h1>My reminders</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Date</th>
                    <th scope="col">Remind before</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody id="mytable_big-screen">

            </tbody>
        </table>
    </section>
    <section class="reminders-list reminders-list-small-screen">
        <h1>My reminders</h1>
        <div class="reminders-list-small-screen-list"></div>
    </section>
    <section class="update-reminder">
        <form class="update-reminder-form ">
            <p class="welcome">Update reminder:</p>
            <div class="alertsbox"></div>
            <div class="input-group">
                <input type="text" id="update_title" placeholder="Title" class="login__input update-reminder--Title" />
            </div>
            <div class="input-group">
                <input type="text" id="update_date" placeholder="MM/DD" class="login__input update-reminder--date2" />
                <input type="date" id="update_date-picker" autofocus class="login__input update-reminder--date" />
            </div>

            <div class="input-group">
                <select name="remindme-beforee" id="update_remind_before" class="login__input update-reminder--Remindme-beforee">
                    <option value="" disabled selected>Remind beforee</option>
                    <option value="One day">One day</option>
                    <option value="Two days">Two days</option>
                    <option value="Four days">Four days</option>
                    <option value="One week">One week</option>
                    <option value="Two weeks">Two weeks</option>
                </select>
            </div>
            <div class="fullwidth">
            <button class="register__btn update-reminder-btn">Update</button>
            <button class="register__btn return-update-reminder-btn">Return</button>
            </div>
        </form>
    </section> 
    <section class="delete-reminder">
        <form class="delete-reminder-form">
            <p class="welcome">Delete reminder?</p>
            <div>
            <button class="register__btn delete-reminder-btn">Delete</button>
            <button class="register__btn return-delete-reminder-btn">Return</button>
            </div>
        </form>
    </section>
    <?php
    require_once APPROOT . '/views/includes/foot.php';

    ?>