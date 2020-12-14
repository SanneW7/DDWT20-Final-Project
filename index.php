<?php

include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'final_project', 'ddwt20','ddwt20');
$template = Array(
    1 => Array(
        'name' => 'Home',
        'url' => '/DDWT20-Final-Project/'
    ),
    2 => Array(
        'name' => 'Kamers',
        'url' => '/DDWT20-Final-Project/rooms/'
    ),
    3 => Array(
        'name' => 'Kamer plaatsen',
        'url' => '/DDWT20-Final-Project/add_room/'
    ),
    4 => Array(
        'name' => 'Mijn account',
        'url' => '/DDWT20-Final-Project/myaccount/'
    ),
    5 => Array(
        'name' => 'Login',
        'url' => '/DDWT20-Final-Project/login/'
    )

);

/* Home page */
if (new_route('/DDWT20-Final-Project/', 'get')) {
    /* General page information */
    $page_title = 'Home pagina';
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Home' => na('/DDWT20-Final-Project/', True)
    ]);
    $navigation = get_navigation($template, 1);

    /* Specific page information */
    $page_subtitle = 'Welkom op Kamernet2';
    $page_content = 'Vind hier je nieuwe kamer of nieuwe huisgenoot!';

    /* Used template */
    include use_template('home');
}

/* Page containing all rooms */
elseif (new_route('/DDWT20-Final-Project/rooms/', 'get')) {
    /* General page information */
    $page_title = 'Kamers';
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Kamers' => na('/DDWT20-Final-Project/rooms/', True)
    ]);
    $navigation = get_navigation($template, 2);

    /* Specific page information */
    $page_subtitle = 'Beschikbare kamers';
    $page_content = 'Hier zie je een overzicht van alle kamers';

    /* Used template */
    include use_template('room_overview');
}

/* Page single room info */
elseif (new_route('/DDWT20-Final-Project/room/', 'get')) {
    /* General page information */
    /* $naam = Functie haalt straatnaam + nr op */
    $page_title = 'NAAM KAMER'; /* $naam */
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Kamers' => na('/DDWT20-Final-Project/rooms/', False),
        'KAMER NAAM' => na('/DDWT20-Final-Project/room/', True) /* $naam */
    ]);
    $navigation = get_navigation($template, 2);

    /* Specific page information */
    $page_subtitle = 'Kamer info';
    $page_content = 'Hier vind je de specifieke informatie over deze kamer';

    /* Used template */
    include use_template('room');
}

/* ADD room GET */
elseif (new_route('/DDWT20-Final-Project/add_room/', 'get')) {
    /* General page information */
    $page_title = 'Kamer toevoegen';
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Kamer toevoegen' => na('/DDWT20-Final-Project/add_room/', True)
    ]);
    $navigation = get_navigation($template, 3);

    /* Specific page information */
    $page_subtitle = 'Kamer toevoegen';
    $page_content = 'Vul alle velden in om een kamer toe te voegen';
    $submit_btn = "Voeg kamer toe";
    $form_action = '/DDWT20-Final-Project/add_room/';

    if ( isset($_GET['error_msg']) ) {
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Used template */
    include use_template('new');
}

/* ADD room POST */
elseif (new_route('/DDWT20-Final-Project/add_room/', 'post')) {
    $feedback = add_room($db, $_POST);
    redirect(sprintf('/DDWT20-Final-Project/add_room/?error_msg=%s',
        json_encode($feedback)));
}

/* EDIT room GET */
elseif (new_route('/DDWT20-Final-Project/edit/', 'get')) {
    /* General page information */
    $page_title = 'Kamer informatie aanpassen';
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        sprintf('% aanpassen', 'KAMER') => na('/DDWT20-Final-Project/edit/', True)
    ]);
    $navigation = get_navigation($template, 0);

    /* Specific page information */
    $page_subtitle = sprintf('% aanpassen', 'KAMER');
    $page_content = 'Pas de velden aan';

    /* Used template */
    include use_template('new');
}

/* EDIT room POST */
elseif (new_route('/DDWT20-Final-Project/edit/', 'post')) {
    /* General page information */

    /* Specific page information */

    /* Used template */
}

/* DELETE room */
elseif (new_route('/DDWT20-Final-Project/delete/', 'post')) {
    /* General page information */

    /* Specific page information */

    /* Used template */
}

/* Account GET */
elseif (new_route('/DDWT20-Final-Project/myaccount/', 'get')) {
    /* General page information */
    $page_title = sprintf('Account van %', 'NAAM');
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Mijn account' => na('/DDWT20-Final-Project/myaccount/', True)
    ]);
    $navigation = get_navigation($template, 4);

    /* Specific page information */
    $page_subtitle = 'Welkom';
    $page_content = 'Hier zie je een overzicht van je kamers';

    /* Used template */
    include use_template('account');
}

/* Register user GET */
elseif (new_route('/DDWT20-Final-Project/register/', 'get')) {
    /* General page information */
    $page_title = 'Registreren';
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Registreren' => na('/DDWT20-Final-Project/register/', True)
    ]);
    $navigation = get_navigation($template, 5);

    /* Specific page information */
    $page_subtitle = 'Registreer je account';
    $page_content = 'Vul alle velden in om te registreren';

    /* Used template */
    include use_template('register');
}

/* Register user POST */
elseif (new_route('/DDWT20-Final-Project/register/', 'POST')) {
    /* General page information */

    /* Specific page information */

    /* Used template */
}

/* Login GET */
elseif (new_route('/DDWT20-Final-Project/login/', 'get')) {
    /* General page information */
    $page_title = 'Login';
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Login' => na('/DDWT20-Final-Project/', True)
    ]);
    $navigation = get_navigation($template, 5);

    /* Specific page information */
    $page_subtitle = 'Hier kun je inloggen';
    $page_content = 'Vul je gegevens in om in te loggen';

    /* Used template */
    include use_template('login');
}

/* Login POST */
elseif (new_route('/DDWT20-Final-Project/login/', 'post')) {
    /* General page information */

    /* Specific page information */

    /* Used template */
}

/* Message GET */
elseif (new_route('/DDWT20-Final-Project/message/', 'get')) {
    /* General page information */
    $page_title = 'Berichten';
    $breadcrumbs = get_breadcrumbs([
        'Kamernet2' => na('/DDWT20-Final-Project/', False),
        'Berichten' => na('/DDWT20-Final-Project/', True)
    ]);
    $navigation = get_navigation($template, 0);

    /* Specific page information */
    $page_subtitle = '';
    $page_content = '';

    /* Used template */
    include use_template('message');
}

else
    p_print('De pagina kan niet gevonden worden');
