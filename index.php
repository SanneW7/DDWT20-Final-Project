<?php

include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'final_project', 'ddwt20','ddwt20');

/* Template not logged in user */
$template_login = Array(
    1 => Array(
        'name' => 'Home',
        'url' => '/DDWT20-Final-Project/'
    ),
    5 => Array(
        'name' => 'Login',
        'url' => '/DDWT20-Final-Project/login/'
    ),
    6 => Array(
        'name' => 'Registreren',
        'url'  => '/DDWT20-Final-Project/register/'
    )
);

/* Home page */
if (new_route('/DDWT20-Final-Project/', 'get')) {
    session_start();

    /* General page information */
    $page_title = 'Home pagina';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Home' => na('/DDWT20-Final-Project/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else {
        $template = $template_login;
    }
    $navigation = get_navigation($template, 1);

    /* Page content */
    $amount_users = get_amount_users($db);
    $amount_rooms = get_amount_rooms($db);
    $latest_room = get_latest_room($db);
    if (!empty($latest_room)) {
        $owner_name = get_name($db, $latest_room['owner']);
        $owner_id = get_owner($db, $latest_room['id']);
        $newest_room = '/DDWT20-Final-Project/room/?id=' . $latest_room['id'];
    }
    $page_subtitle = 'Welkom op Vesta';
    $page_content = 'Vind hier je nieuwe kamer of nieuwe huisgenoot!';

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('home');
}

/* Page containing all rooms */
elseif (new_route('/DDWT20-Final-Project/rooms/', 'get')) {
    /* Login check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    }

    /* General page information */
    $page_title = 'Kamers';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Kamers' => na('/DDWT20-Final-Project/rooms/', True)
    ]);

    /* Navigation template check */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else {
        $template = $template_login;
    }
    $navigation = get_navigation($template, 2);

    /* Page content */
    $page_subtitle = 'Beschikbare kamers';
    $page_content = 'Hier zie je een overzicht van alle kamers';
    $left_content = get_room_table(get_rooms($db));

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('room_overview');
}

/* Page single room info */
elseif (new_route('/DDWT20-Final-Project/room/', 'get')) {
    /* Login check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    }

    /* Get room information */
    $room_id = $_GET['id'];
    $room_info = get_room_info($db, $room_id);

    /* General page information */
    $page_title = $room_info['streetname']. ' ' .$room_info['house_number']; /* $naam */
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Kamers' => na('/DDWT20-Final-Project/rooms/', False),
        $page_title => na('/DDWT20-Final-Project/room/', True) /* $naam */
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 2);

    /* Page content */
    $page_subtitle = 'Kamer info';
    $page_content = 'Hier vind je de specifieke informatie over deze kamer';
    $owner_name = get_name($db, $room_info['owner']);
    $owner_id = get_owner($db, $room_id);
    $opt_in_content = get_opt_in_table($db, $room_id);
    $number_opt_ins = get_number_opt_in($db, $room_id);

    /* Display buttons */
    if ($room_info['owner'] == get_current()){
        $display_buttons_owner = True;
    } else {
        $display_buttons_owner = False;
    }
    if (get_role($db, $_SESSION['user_id']) == 0){
        $display_buttons_tenant = True;
    } else {
        $display_buttons_tenant = False;
    }
    $check = check_opt_in($db, $_SESSION['user_id'], $room_id);
    $button_opt_in = $check['button'];

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('room');
}

/* ADD room GET */
elseif (new_route('/DDWT20-Final-Project/add_room/', 'get')) {
    /* Login check & user check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    } elseif (get_role($db, $_SESSION['user_id']) == 0){
        redirect('/DDWT20-Final-Project/');
    }

    /* General page information */
    $page_title = 'Kamer toevoegen';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Kamer toevoegen' => na('/DDWT20-Final-Project/add_room/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 3);

    /* Page content */
    $page_subtitle = 'Kamer toevoegen';
    $page_content = 'Vul alle velden in om een kamer toe te voegen';
    $submit_btn = "Voeg kamer toe";
    $form_action = '/DDWT20-Final-Project/add_room/';

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('new');
}

/* ADD room POST */
elseif (new_route('/DDWT20-Final-Project/add_room/', 'post')) {
    session_start();

    /* Add room to database */
    $feedback = add_room($db, $_POST, $_SESSION['user_id']);

    /* Redirects */
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/add_room/?error_msg=%s',
            json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/rooms/?error_msg=%s',
            json_encode($feedback)));
    }
}

/* EDIT room GET */
elseif (new_route('/DDWT20-Final-Project/edit/', 'get')) {
    /* Login check & role check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    }  elseif (get_role($db, $_SESSION['user_id']) == 0 or get_owner($db, $_GET['id']) != $_SESSION['user_id']){
        redirect('/DDWT20-Final-Project/');
    }

    /* Get room information */
    $room_id = $_GET['id'];
    $room_info = get_room_info($db, $room_id);

    /* General page information */
    $page_title = 'Kamer informatie aanpassen';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Aanpassen' => na('/DDWT20-Final-Project/edit/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 0);

    /* Page content */
    $page_subtitle = 'Kamer aanpassen';
    $page_content = 'Pas de velden aan';
    $form_action = '/DDWT20-Final-Project/edit/';
    $submit_btn = "Kamer aanpassen";

    /* Feedback messages */
    if (isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('new');
}

/* EDIT room POST */
elseif (new_route('/DDWT20-Final-Project/edit/', 'post')) {
    /* Update room information */
    $feedback = update_room($db, $_POST);

    /* Redirecting */
    $room_id = $_POST['id'];
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/edit/?error_msg=%s',
            json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/room/?id=%s&error_msg=%s',
            json_encode(intval($room_id)), json_encode($feedback)));
    }
}

/* EDIT account GET */
elseif(new_route('/DDWT20-Final-Project/edit_account/', 'get')){
    /* Login check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');}

    /* Get user information */
    $user_id = $_SESSION['user_id'];
    $user_info = get_user_info($db, $user_id);

    /* General page information */
    $page_title = 'Account informatie aanpassen';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Mijn account' => na('/DDWT20-Final-Project/myaccount/', False),
        'Account aanpassen' => na('/DDWT20-Final-Project/edit_account', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 0);

    /* Specific page information */
    $page_subtitle = 'Account aanpassen';
    $page_content = 'Pas de velden aan';
    $form_action = '/DDWT20-Final-Project/edit_account/';
    $button_name = "Account aanpassen";

    /* Feedback messages */
    if (isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('register');
}

/* EDIT account POST */
elseif (new_route('/DDWT20-Final-Project/edit_account/', 'post')) {
    /* Update user information */
    $feedback = update_user($db, $_POST);

    /* Redirecting */
    $user_id = $_POST['id'];
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/edit_account/?error_msg=%s',
            json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/myaccount/?error_msg=%s',
            json_encode($feedback)));
    }
}

/* DELETE room */
elseif (new_route('/DDWT20-Final-Project/delete/', 'post')) {
    /* Delete room from database */
    $room_id = $_POST['id'];
    $feedback = remove_room($db, $room_id);

    /* Redirecting */
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/room/?id=%s&error_msg=%s',
            json_encode(intval($room_id)), json_encode($feedback)));
    } else {
        /* Redirect to room overview */
        redirect(sprintf('/DDWT20-Final-Project/rooms/?error_msg=%s',
            json_encode($feedback)));
    }
}

/* DELETE account */
elseif (new_route('/DDWT20-Final-Project/delete_account/', 'post')) {
    /* Delete user from database */
    $user_id = $_POST['id'];
    $feedback = remove_user($db, $user_id);

    /* Redirecting */
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/myaccount/?error_msg=%s',
            json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/?error_msg=%s',
            json_encode($feedback)));
    }
}

/* Account GET */
elseif (new_route('/DDWT20-Final-Project/myaccount/', 'get')) {
    /* Login check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    }

    /* General page information */
    $page_title = sprintf('Account van %', 'NAAM');
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Mijn account' => na('/DDWT20-Final-Project/myaccount/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 5);

    /* Page content */
    $page_subtitle = 'Welkom ';
    $page_content = 'Hier zie je een overzicht van je kamers';
    $user = get_name($db, $_SESSION['user_id']);
    $username = get_username($db, $_SESSION['user_id']);
    $user_info = get_user_info($db, $_SESSION['user_id']);
    $room_content = get_account_table(get_user_rooms($db, $_SESSION['user_id']));

    /* Set card header */
    $role = get_role($db, $_SESSION['user_id']);
    if ($role == 0) {
        $card_header = "Jouw inschrijvingen";
    } else {
        $card_header = "Jouw kamers";
    }

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('account');
}

/* Register user GET */
elseif (new_route('/DDWT20-Final-Project/register/', 'get')) {
    /* Login check */
    if (check_login()){
        redirect('/DDWT20-Final-Project/myaccount/');
    }

    /* General page information */
    $page_title = 'Registreren';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Registreren' => na('/DDWT20-Final-Project/register/', True)
    ]);

    /* Navigation template check */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 6);

    /* Specific page information */
    $page_subtitle = 'Registreer je account';
    $page_content = 'Vul alle velden in om te registreren';
    $form_action = '/DDWT20-Final-Project/register/';
    $button_name = 'Registreer';

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('register');
}

/* Register user POST */
elseif (new_route('/DDWT20-Final-Project/register/', 'POST')) {
    /* Register user to database */
    $feedback = register_user($db, $_POST);

    /* Redirecting */
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/register/?error_msg=%s',
            json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/myaccount/?error_msg=%s',
            json_encode($feedback)));
    }
}

/* Login GET */
elseif (new_route('/DDWT20-Final-Project/login/', 'get')) {
    /* Login check */
    if (check_login()){
        redirect('/DDWT20-Final-Project/myaccount/');
    }

    /* General page information */
    $page_title = 'Login';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Login' => na('/DDWT20-Final-Project/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 5);

    /* Page content */
    $page_subtitle = 'Hier kun je inloggen';
    $page_content = 'Vul je gegevens in om in te loggen';

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('login');
}

/* Login POST */
elseif (new_route('/DDWT20-Final-Project/login/', 'post')) {
    /* Login user */
    $feedback = login_user($db, $_POST);

    /* Redirecting */
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/login/?error_msg=%s',
            json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/myaccount/?error_msg=%s',
            json_encode($feedback)));
    }
}

/* Opt-in POST */
elseif (new_route('/DDWT20-Final-Project/opt-in/', 'post')) {
    session_start();

    /* Opt-in/opt-out user */
    $room_id = $_POST['id'];
    $check = check_opt_in($db, $_SESSION['user_id'], $room_id);
    if ($check['opt_in'] == True) {
        $feedback = opt_in($db, $_SESSION['user_id'], intval($room_id));
    }
    else {
        $feedback = opt_out($db, $_SESSION['user_id'], intval($room_id));
    }

    /* Redirecting */
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/room/?id=%s&error_msg=%s',
            json_encode(intval($room_id)), json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/room/?id=%s&error_msg=%s',
            json_encode(intval($room_id)), json_encode($feedback)));
    }
}

/* Account details GET */
elseif (new_route('/DDWT20-Final-Project/account_details/', 'get')) {
    session_start();
    /* Login check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    }

    /* General page information */
    $page_title = 'Account details';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Account details' => na('/DDWT20-Final-Project/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }

    /* Page content */
    $navigation = get_navigation($template, 0);
    $current_user = get_current();
    $user_id = $_GET['id'];
    $user_info = get_user_info($db, $user_id);
    $submit_btn = 'Stuur een bericht';
    $page_title = 'Account details';
    $page_content = 'Hier zie je een overzicht van de account gegevens';

    /* Used template */
    include use_template('account_details');
}

/* Send Message GET */
elseif (new_route('/DDWT20-Final-Project/send_message/', 'get')) {
    /* Login check & id check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    }
    elseif (check_id($db, $_GET['id']) == False){
        redirect('/DDWT20-Final-Project/inbox/');
    }

    /* User check */
    $receiver_id = $_GET['id'];
    $sender_id = get_current();
    if ($sender_id == $receiver_id){
        redirect('/DDWT20-Final-Project/myaccount/');
    }

    /* General page information */
    $receiver_name = get_name($db, $receiver_id);
    $page_title = sprintf('Berichten met '. $receiver_name);
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Verstuur bericht' => na('/DDWT20-Final-Project/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }
    $navigation = get_navigation($template, 0);

    /* Page content */
    $page_subtitle = '';
    $page_content = '';
    $submit_btn = 'Versturen';
    $form_action = '/DDWT20-Final-Project/send_message/';
    $message_history = get_message_history($db, $receiver_id, $sender_id);

    /* Feedback messages */
    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('send_message');
}

/* Send Message POST */
elseif (new_route('/DDWT20-Final-Project/send_message/', 'post')) {
    /* Send message */
    $feedback = send_message($db, $_POST);

    /* Redirecting */
    $receiver_id = $_POST['receiver'];
    if ($feedback['type'] == 'danger') {
        redirect(sprintf('/DDWT20-Final-Project/send_message/?id=%s&error_msg=%s',
            json_encode(intval($receiver_id)), json_encode($feedback)));
    } else {
        redirect(sprintf('/DDWT20-Final-Project/send_message/?id=%s&error_msg=%s',
            json_encode(intval($receiver_id)), json_encode($feedback)));
    }
}

/* Inbox GET */
elseif (new_route('/DDWT20-Final-Project/inbox/', 'get')) {
    /* Login check */
    if (!check_login()){
        redirect('/DDWT20-Final-Project/login/');
    }

    /* General page information */
    $page_title = 'Inbox';
    $breadcrumbs = get_breadcrumbs([
        'Vesta' => na('/DDWT20-Final-Project/', False),
        'Inbox' => na('/DDWT20-Final-Project/', True)
    ]);

    /* Navigation template set */
    if (isset($_SESSION['user_id'])){
        $template = template_check($db, $_SESSION['user_id']);
    } else{
        $template = $template_login;
    }

    /* Page content */
    $navigation = get_navigation($template, 4);
    $page_subtitle = 'Een overzicht van al je berichten';
    $page_content = '';
    $message_table = get_message_table($db, get_current());

    /* Used template */
    include use_template('inbox');
}

/* Logout POST */
elseif (new_route('/DDWT20-Final-Project/logout/', 'get')) {
    session_destroy();

    /* Logging out user */
    $feedback = logout_user();

    /* Redirecting */
    redirect(sprintf('/DDWT20-Final-Project/?error_msg=%s',
        json_encode($feedback)));
}

else {
    p_print('De pagina kan niet gevonden worden');
}