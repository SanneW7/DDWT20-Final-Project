<?php
/**
 * Controller
 * User: reinardvandalen
 * Date: 04-11-18
 * Time: 14:24
 */

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
        'name' => 'Mijn account',
        'url' => '/DDWT20-Final-Project/myaccount/'
    ),

    4 => Array(
        'name' => 'Kamer plaatsen',
        'url' => '/DDWT20-Final-Project/add_room/'
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
        'Kamernet 2' => na('/DDWT20-Final-Project/', False),
        'Home' => na('/DDWT20-Final-Project/', True)
    ]);
    $navigation = get_navigation($template, 1);

    /* Specific page information */
    $page_subtitle = 'Welkom op Kamernet 2';
    $page_content = 'Vind hier je nieuwe kamer of nieuwe huisgenoot!';

    /* Used template */
    include use_template('home');
}