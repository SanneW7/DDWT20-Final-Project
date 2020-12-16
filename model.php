<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
* Connects to the database using PDO
* @param string $host database host
* @param string $db database name
* @param string $user database user
* @param string $pass database password
* @return pdo object
*/
function connect_db($host, $db, $user, $pass){
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
$pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
echo sprintf("Failed to connect. %s",$e->getMessage());
}
return $pdo;
}


/**
 * This function checks if the called route exists
 * @param string $route_uri the path we want to check
 * @param string $request_type the type of request
 * @return bool validity of the route
 */

function new_route($route_uri, $request_type){
    $route_uri_expl = array_filter(explode('/', $route_uri));
    $current_path_expl = array_filter(explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($route_uri_expl == $current_path_expl && $_SERVER['REQUEST_METHOD'] == strtoupper($request_type)) {
        return True;
    }
    else
        return false;
}
/**
 * @param string $url containing path of a breadcrumb
 * @param bool $active tell if the path is active
 * @return array containing the navigation items
 */

function na($url, $active){
    return [$url, $active];
}

/**
 * Connect the right template from the view to the controller
 * @param string $template name of the right view
 * @return string the path the right template
 */

function use_template($template){
    $template_doc = sprintf("views/%s.php", $template);
    return $template_doc;
}


/**
 * Creates breadcrumbs showing the current path
 * @param array $breadcrumbs containing breadcrumbs of the current path
 * @return string html code containing breadcrumbs
 */

function get_breadcrumbs($breadcrumbs) {
    $breadcrumbs_exp = '
    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">';
    foreach ($breadcrumbs as $name => $info) {
        if ($info[1]){
            $breadcrumbs_exp .= '<li class="breadcrumb-item active" aria-current="page">'.$name.'</li>';
        }else{
            $breadcrumbs_exp .= '<li class="breadcrumb-item"><a href="'.$info[0].'">'.$name.'</a></li>';
        }
    }
    $breadcrumbs_exp .= '
    </ol>
    </nav>';
    return $breadcrumbs_exp;
}

/**
 * @param array $template containing all possible paths and id's
 * @param int $active_id id used to identify the current path
 * @return string html code containing the navigation bar
 */

function get_navigation($template, $active_id){
    $navigation_exp = session_status().'
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand">Kamernet2</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">';
    foreach ($template as $name => $info) {
        if ($name == $active_id){
            $navigation_exp .= '<li class="nav-item active">';
            $navigation_exp .= '<a class="nav-link" href="'.$template[$name]['url'].'">'.$template[$name]['name'].'</a>';
        }else{
            $navigation_exp .= '<li class="nav-item">';
            $navigation_exp .= '<a class="nav-link" href="'.$template[$name]['url'].'">'.$template[$name]['name'].'</a>';
        }

        $navigation_exp .= '</li>';
    }
    $navigation_exp .= '
    </ul>
    </div>
    </nav>';
    return $navigation_exp;
}

/**
 * Pretty Print Array
 * @param $input
 */
function p_print($input){
    echo '<pre>';
    print_r($input);
    echo '</pre>';
}

function add_room($pdo, $room_info){
    /* Empty check */
    if (
        empty($room_info['price']) or
        empty($room_info['type']) or
        empty($room_info['size']) or
        empty($room_info['street']) or
        empty($room_info['housenumber']) or
        empty($room_info['zipcode']) or
        empty($room_info['city'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'Vul alle velden in om door te gaan.'
        ];
    }

    /* Numeric check */
    if (!is_numeric($room_info['price'])) {
        return [
            'type' => 'danger',
            'message' => 'Vul een getal in in het veld Prijs.'
        ];
    } elseif (!is_numeric($room_info['size'])) {
        return [
            'type' => 'danger',
            'message' => 'Vul een getal in in het veld Oppervlakte.'
        ];
    } elseif (!is_numeric($room_info['housenumber'])) {
        return [
            'type' => 'danger',
            'message' => 'Vul een getal in in het veld Huisnummer.'
        ];
    }

    /* Zip code check */
    /*if (!zipcode_check($room_info['zipcode'])) {
        return [
            'type' => 'danger',
            'message' => 'Vul een geldige postcode in.'
        ];
    }

    /* Add room to database */
    $stmt = $pdo->prepare("INSERT INTO rooms (price, owner, type, size, streetname, city, zip_code, house_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $room_info['price'],
        1,
        $room_info['type'],
        $room_info['size'],
        $room_info['street'],
        $room_info['city'],
        $room_info['zipcode'],
        $room_info['housenumber']
    ]);
    $pdo->lastInsertId();
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Kamer '%s %s' is toegevoegd aan Kamernet2!", $room_info['street'], $room_info['housenumber'])
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'Er is iets fout gegaan. Probeer het opnieuw!'
        ];
    }
}

function zipcode_check($postcode)
{
    $remove = str_replace(" ","", $postcode);
    $upper = strtoupper($remove);

    if( preg_match("/^\W[1-9]{1}[0-9]{3}\W[a-zA-Z]{2}\W*$/",  $upper)) {
        return $upper;
    } else {
        return false;
    }
}

function redirect($location){
    header(sprintf('Location: %s', $location));
    die();
}

function get_error($feedback){
    $feedback = json_decode($feedback, True);
    $error_exp = '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
    return $error_exp;
}

function get_room_table($pdo, $rooms){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <th>
        <th scop="col">City</th>
        <th scope="col">Street name</th>
        <th scope="col">Price</th>
        <th scop="col">Size</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($rooms as $key => $value){
        $table_exp .= '
        <tr>
            <th scope="row">'.$value['city'].'</th>
            <th scope="row">'.$value['streetname'].'</th>
            <th scope="row">'.$value['price'].'</th>
            <th scope="row">'.$value['size'].'</th>
            <td><a href="/DDWT20-Final-Project/room/?id='.$value['id'].'" role="button" class="btn btn-primary">More info</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

function get_rooms($pdo){
    $stmt = $pdo->prepare('SELECT * FROM rooms');
    $stmt->execute();
    $rooms = $stmt->fetchAll();
    $rooms_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($rooms as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $rooms_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $rooms_exp;
}

function get_room_info($pdo, $room_id){
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = ?');
    $stmt->execute([$room_id]);
    $room_info = $stmt->fetch();
    $room_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($room_info as $key => $value){
        $room_info_exp[$key] = htmlspecialchars($value);
    }
    return $room_info_exp;
}

function update_room($pdo, $room_info){
    /* Empty check */
    if (
        empty($room_info['price']) or
        empty($room_info['type']) or
        empty($room_info['size']) or
        empty($room_info['street']) or
        empty($room_info['housenumber']) or
        empty($room_info['zipcode']) or
        empty($room_info['city'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'Vul alle velden in om door te gaan.'
        ];
    }

    /* Numeric check */
    if (!is_numeric($room_info['price'])) {
        return [
            'type' => 'danger',
            'message' => 'Vul een getal in in het veld Prijs.'
        ];
    } elseif (!is_numeric($room_info['size'])) {
        return [
            'type' => 'danger',
            'message' => 'Vul een getal in in het veld Oppervlakte.'
        ];
    } elseif (!is_numeric($room_info['housenumber'])) {
        return [
            'type' => 'danger',
            'message' => 'Vul een getal in in het veld Huisnummer.'
        ];
    }

    /* Get current room name */
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = ?');
    $stmt->execute([$room_info['id']]);
    $room = $stmt->fetch();

    /*$display_buttons = $serie_info['user'] == $_SESSION['user_id'];
    if ($display_buttons) {
        /* Update Serie
        $user_id = $_SESSION['user_id']; */
        $stmt = $pdo->prepare("UPDATE rooms SET price = ?, type = ?, size = ?, streetname = ?, city = ?, zip_code = ?, house_number = ? WHERE id = ?");
        $stmt->execute([
            $room_info['price'],
            $room_info['type'],
            $room_info['size'],
            $room_info['street'],
            $room_info['city'],
            $room_info['zipcode'],
            $room_info['housenumber'],
            $room_info['id']
        ]);
        $updated = $stmt->rowCount();
    /*}*/
    if ($updated ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Kamer informatie is gewijzigd!")
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Er is iets fout gegaan. Probeer het opnieuw!'
        ];
    }
}

function remove_room($pdo, $room_id){
    /* Get room info
    $room_info = get_serieinfo($pdo, $room_id);
    /*$display_buttons = $serie_info['user'] == $_SESSION['user_id'];*/

    /* Delete room */
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        return [
            'type' => 'success',
            'message' => 'De kamer is verwijderd!'
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Er is iets foutgegaan. Probeer het opnieuw!'
        ];
    }
}

function register_user($pdo, $form_data){
    /* Check if all fields are set */
    if (
        empty($form_data['username']) or
        empty($form_data['password']) or
        empty($form_data['birth_date']) or
        empty($form_data['biography']) or
        empty($form_data['email']) or
        empty($form_data['phonenumber']) or
        empty($form_data['occupation']) or
        empty($form_data['language']) or
        empty($form_data['full_name'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'Niet alle velden zijn ingevuld.'.$form_data['occupation']
        ];
    }

    /* Check if user already exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_exists = $stmt->rowCount();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er ging wat mis: %s', $e->getMessage())
        ];
    }
    /* Return error message for existing username */
    if ( !empty($user_exists) ) {
        return [
            'type' => 'danger',
            'message' => 'Deze gebruikersnaam bestaat al!'
        ];
    }

    /* Hash password */
    $password = password_hash($form_data['password'], PASSWORD_DEFAULT);

    /* Save user to the database */
    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password, birth_date, biography, email, phonenumber, occupation, language, role, full_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute(
            [
                $form_data['username'],
                $password,
                $form_data['birth_date'],
                $form_data['biography'],
                $form_data['email'],
                $form_data['phonenumber'],
                $form_data['occupation'],
                $form_data['language'],
                $form_data['role'],
                $form_data['full_name']
            ]
        );
        $user_id = $pdo->lastInsertId();
    } catch (PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er ging iets fout: %s', $e->getMessage())
        ];
    }

    /* Login user and redirect */
    session_start();
    $_SESSION['user_id'] = $user_id;
    $feedback = [
        'type' => 'success',
        'message' => sprintf('%s, je account is aangemaakt!', $form_data['username'])
    ];
    redirect(sprintf('/DDWT20-Final-Project/myaccount/?error_msg=%s',
        json_encode($feedback)));
}

function get_username($pdo, $user_id){
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $username_array = $stmt -> fetch();
    $username = '';
    foreach ($username_array as $key => $value){
        $username = $value;
    }
    return htmlspecialchars($username);
}

function login_user($pdo, $form_data){
    /* Check if all fields are set */
    if (
        empty($form_data['username']) or
        empty($form_data['password'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'Vul een gebruiksnaam en wachtwoord in.'
        ];
    }

    /* Check if user exists */
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$form_data['username']]);
        $user_info = $stmt->fetch();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er is iets foutgegaan: %s', $e->getMessage())
        ];
    }
    /* Return error message for wrong username */
    if (empty($user_info) ) {
        return [
            'type' => 'danger',
            'message' => 'De gebruikersnaam die je hebt ingevuld bestaat niet!'
        ];
    }

    /* Check password */
    if (!password_verify($form_data['password'], $user_info['password']) ){
        return [
            'type' => 'danger',
            'message' => 'Het wachtwoord dat je hebt ingevuld klopt niet!'
        ];
    } else {
        session_start();
        $_SESSION['user_id'] = $user_info['id'];
        $feedback = [
            'type' => 'success',
            'message' => sprintf('%s, je bent ingelogd!',
                get_username($pdo, $_SESSION['user_id']))
        ];
        redirect(sprintf('/DDWT20-Final-Project/myaccount/?error_msg=%s',
            json_encode($feedback)));
    }
}

function check_login(){
    session_start();
    if (isset($_SESSION['user_id'])){
        return True;
    } else {
        return False;
    }
}

function logout_user(){
    session_destroy();
    session_unset();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    if (isset($_SESSION['user_id'])){
        return [
            'type' => 'danger',
            'message' => 'Je bent niet uitgelogd.'
        ];
    }
    return [
        'type' => 'success',
        'message' => 'Je bent uitgelogd!'
    ];
}
function template_check($pdo, $user_id){
    $template_tenant = Array(
        1 => Array(
            'name' => 'Home',
            'url' => '/DDWT20-Final-Project/'
        ),
        2 => Array(
            'name' => 'Kamers',
            'url' => '/DDWT20-Final-Project/rooms/'
        ),
        4 => Array(
            'name' => 'Mijn account',
            'url' => '/DDWT20-Final-Project/myaccount/'
        )
    );
    $template_owner = Array(
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
        )
    );

    $role = get_role($pdo, $user_id);
    if ($role == 1){
        return $template_owner;
    }
    else{
        return $template_tenant;
    }
}
function get_role($pdo, $id){
    $stmt = $pdo->prepare("SELECT role  FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $role = $stmt->fetch();
    return htmlspecialchars($role['role']);
}