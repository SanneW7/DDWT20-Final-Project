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
 * creates navigation bar
 * @param array $template containing all possible paths and id's
 * @param int $active_id id used to identify the current path
 * @return string html code containing the navigation bar
 */
function get_navigation($template, $active_id){
    $navigation_exp = '
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand">Vesta</a>
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

/**
 * adds a room instance to the database
 * @param $pdo object
 * @param $room_info array containing the room information to be added
 * @param $user_id int the user_id of the current user
 * @return array|string[] containing feedback information
 */
function add_room($pdo, $room_info, $user_id){
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
    }

    /* Special character check */
    if (!preg_match("/^[a-zA-Z-.' ]*$/", $room_info['street'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Straat gebruikt karakters die niet zijn toegestaan'
        ];
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $room_info['city'])){
        return [
            'type' => 'danger',
            'message' => 'Het veld Stad gebruikt karakters die niet zijn toegestaan'
        ];
    }

    /* House number check */
    if (!preg_match("/^[0-9]{1,5}$/", $room_info['housenumber'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Huisnummer is niet in het gewenste formaat'
        ];
    }

    /* Zip code check */
    if (!preg_match("/^[1-9]{1}[0-9]{3}[A-Za-z]{2}$/", $room_info['zipcode'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Postcode is niet in het juiste formaat'
        ];
    } else {
        $zipcode = strtoupper($room_info['zipcode']);
    }

    /* Add room to database */
    $stmt = $pdo->prepare("INSERT INTO rooms (price, owner, type, size, streetname, city, zip_code, house_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $room_info['price'],
        $user_id,
        $room_info['type'],
        $room_info['size'],
        $room_info['street'],
        $room_info['city'],
        $zipcode,
        $room_info['housenumber']
    ]);
    $pdo->lastInsertId();

    /* Return feedback */
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

/**
 * redirects the user to another page
 * @param $location string string containing the new route to redirect to
 */
function redirect($location){
    header(sprintf('Location: %s', $location));
    die();
}

/**
 * creates html entity containing error message
 * @param $feedback array containing feedback error
 * @return string html code of the error message
 */
function get_error($feedback){
    $feedback = json_decode($feedback, True);
    $error_exp = '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
    return $error_exp;
}

/**
 * creates a table with all room instances in the database
 * @param $rooms array containing information of all rooms
 * @return string html code containing a table
 */
function get_room_table($rooms){
    $table_exp = '
    <table class="table table-hover">
    <thead
    <th>
        <th scop="col">Stad</th>
        <th scope="col">Straat</th>
        <th scope="col">Prijs</th>
        <th scop="col">Oppervlakte</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($rooms as $key => $value){
        $table_exp .= '
        <tr>
            <th scope="row">'.$value['city'].'</th>
            <th scope="row">'.$value['streetname'].'</th>
            <th scope="row">&euro;'.$value['price'].'</th>
            <th scope="row">'.$value['size'].'m&sup2;</th>
            <td><a href="/DDWT20-Final-Project/room/?id='.$value['id'].'" role="button" class="btn btn-primary">Meer informatie</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

/**
 * function which retrieves information of all rooms out of the database
 * @param $pdo object
 * @return array containing information of all rooms
 */
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

/**
 * function which returns room information of a specific room
 * @param $pdo object
 * @param $room_id int which is the id of the room you want to retrieve
 * @return array containing information of the requested room
 */
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

/**
 * function which updates the information of a specific room in the database
 * @param $pdo object
 * @param $room_info array containing the updated information of the room
 * @return array|string[] containing feedback
 */
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
    }

    /* Special character check */
    if (!preg_match("/^[a-zA-Z-.' ]*$/", $room_info['street'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Straat gebruikt karakters die niet zijn toegestaan'
        ];
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $room_info['city'])){
        return [
            'type' => 'danger',
            'message' => 'Het veld Stad gebruikt karakters die niet zijn toegestaan'
        ];
    }

    /* House number check */
    if (!preg_match("/^[0-9]{1,5}$/", $room_info['housenumber'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Huisnummer is niet in het gewenste formaat'
        ];
    }

    /* Zip code check */
    if (!preg_match("/^[1-9]{1}[0-9]{3}[A-Za-z]{2}$/", $room_info['zipcode'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Postcode is niet in het juiste formaat'
        ];
    } else {
        $zipcode = strtoupper($room_info['zipcode']);
    }

        $stmt = $pdo->prepare("UPDATE rooms SET price = ?, type = ?, size = ?, streetname = ?, city = ?, zip_code = ?, house_number = ? WHERE id = ?");
        $stmt->execute([
            $room_info['price'],
            $room_info['type'],
            $room_info['size'],
            $room_info['street'],
            $room_info['city'],
            $zipcode,
            $room_info['housenumber'],
            $room_info['id']
        ]);

    /* Return feedback */
    $updated = $stmt->rowCount();
    if ($updated ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Kamer informatie is gewijzigd!")
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Er is niks gewijzigd.'
        ];
    }
}

/**
 * function which deletes a room instance out of the database
 * @param $pdo object
 * @param $room_id int containing the id of the room you want to delete
 * @return array|string[] containing feedback
 */
function remove_room($pdo, $room_id){
    /* Check if there are opt-ins */
    try {
        $stmt = $pdo->prepare('SELECT * FROM opt_in WHERE room_id = ?');
        $stmt->execute([$room_id]);
        $room_info = $stmt->fetch();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er is iets foutgegaan: %s', $e->getMessage())
        ];
    }

    /* Delete all opt-in instances */
    if (!empty($room_info) ) {
        $stmt = $pdo->prepare("DELETE FROM opt_in WHERE room_id = ?");
        $stmt->execute([$room_id]);
    }

    /* Delete room */
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $deleted = $stmt->rowCount();

    /* Return feedback */
    if ($deleted == 1) {
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

/**
 * function which adds a new user instance to the database
 * @param $pdo object
 * @param $form_data array containing user information to be added to the db
 * @return array|string[] containing feedback
 */
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
            'message' => 'Niet alle velden zijn ingevuld.'
        ];
    }

    /* Special character check */
    if (!ctype_alpha($form_data['occupation'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Studie of Beroep kan alleen letters bevatten'
        ];
    } elseif (!ctype_alpha($form_data['language'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Taal kan alleen letters bevatten'
        ];
    }

    /* Date check */
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$form_data['birth_date'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Geboortedatum is niet in het gewenste formaat'
        ];
    }

    /* Email check */
    if (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Email is niet in het gewenste formaat.'
        ];
    }

    /* Phone number check */
    if (!preg_match("/^(06)[0-9]{8}$/", $form_data['phonenumber'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Telefoonnummer is niet in het gewenste formaat.'
        ];
    }

    /* Full name check */
    if (!preg_match("/^[a-zA-Z-' ]*$/", $form_data['full_name'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Volledige Naam is niet in het gewenste formaat (gebruik alleen letters, koppeltekens, spaties en apostrof\'s)'
        ];
    }

    /* Bio length */
    if (strlen($form_data['biography']) > 255){
        return [
            'type' => 'danger',
            'message' => 'Er ging iets fout, je bio is te lang!'
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

/**
 * function which retrieves a specific username from the db
 * @param $pdo object
 * @param $user_id int id of the username to be retrieved
 * @return string the username retrieved
 */
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

/**
 * function which checks the login information by matching the given username with password
 * if correct it logs the user in by starting a session
 * @param $pdo object
 * @param $form_data array containing login information
 * @return array|string[] containing feedback
 */
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

/**
 * check if the user is logged in by checking the session status
 * @return boolean telling whether or not the user is logged in
 */
function check_login(){
    if (session_status() !=2) {
        session_start();
    }
    if (isset($_SESSION['user_id'])){
        return True;
    } else {
        return False;
    }
}

/**
 * function which logs out a user by ending the session
 * @return string[] containing feedback
 */
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

    /* Return feedback */
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

/**
 * function which chooses the right template based on the users role
 * @param $pdo object
 * @param $user_id int containing id of the active user
 * @return string[][] containing the fitting template
 */
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
            'name' => 'Inbox',
            'url' => '/DDWT20-Final-Project/inbox/'
        ),
        5 => Array(
            'name' => 'Mijn account',
            'url' => '/DDWT20-Final-Project/myaccount/'
        ),
        6 => Array(
            'name' => 'Uitloggen',
            'url' => '/DDWT20-Final-Project/logout/'
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
            'name' => 'Inbox',
            'url' => '/DDWT20-Final-Project/inbox/'
        ),
        5 => Array(
            'name' => 'Mijn account',
            'url' => '/DDWT20-Final-Project/myaccount/'
        ),
        6 => Array(
            'name' => 'Uitloggen',
            'url' => '/DDWT20-Final-Project/logout/'
        )
    );

    /* Return correct template */
    $role = get_role($pdo, $user_id);
    if ($role == 1){
        return $template_owner;
    }
    else{
        return $template_tenant;
    }
}

/**
 * function which returns the role of a given user
 * @param $pdo object
 * @param $id int of the user who's rol is to be checked
 * @return string the role of the user
 */
function get_role($pdo, $id){
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $role = $stmt->fetch();
    return htmlspecialchars($role['role']);
}

/**
 * function which returns the owner of a room
 * @param $pdo object
 * @param $room_id int id of the room who's owner is to be retrieved from the database
 * @return string containing the id of the owner
 */
function get_owner($pdo, $room_id){
    $stmt = $pdo->prepare("SELECT owner FROM rooms WHERE id = ?");
    $stmt->execute([$room_id]);
    $owner_id = $stmt->fetch();
    return htmlspecialchars($owner_id['owner']);
}

/**
 * function which retrieves the id of the current user
 * @return int|mixed id of the current user, if no current user it returns 0
 */
function get_current(){
    if (check_login()){
        return $_SESSION['user_id'];
    } else {
        return 0;
    }
}

/**
 * function which returns the full name of a given user
 * @param $pdo object
 * @param $user_id int of the user who's full name is to be retrieved
 * @return string containing the full name
 */
function get_name($pdo, $user_id){
    $stmt = $pdo->prepare('SELECT full_name FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $name = $stmt->fetch();
    return htmlspecialchars($name['full_name']);
}

/**
 * function which retrieves all information of a given user
 * @param $pdo object
 * @param $user_id int id of the user who's information is to be retrieved
 * @return array containing the user information
 */
function get_user_info($pdo, $user_id){
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
    $user_info_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($user_info as $key => $value){
        $user_info_exp[$key] = htmlspecialchars($value);
    }
    return $user_info_exp;
}

/**
 * function which updates user information of a given user
 * @param $pdo object
 * @param $user_info array containing updated user information
 * @return array|string[] containing feedback
 */
function update_user($pdo, $user_info){
    /* Empty check */
    if (
        empty($user_info['username']) or
        empty($user_info['full_name']) or
        empty($user_info['email']) or
        empty($user_info['phonenumber']) or
        empty($user_info['birth_date']) or
        empty($user_info['language']) or
        empty($user_info['occupation']) or
        empty($user_info['biography'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'Vul alle velden in om door te gaan.'
        ];
    }

    /* Special character check */
    if (!ctype_alpha($user_info['occupation'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Studie of Beroep kan alleen letters bevatten'
        ];
    } elseif (!ctype_alpha($user_info['language'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Taal kan alleen letters bevatten'
        ];
    }

    /* Date check */
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$user_info['birth_date'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Geboortedatum is niet in het gewenste formaat'
        ];
    }

    /* Email check */
    if (!filter_var($user_info['email'], FILTER_VALIDATE_EMAIL)) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Email is niet in het gewenste formaat.'
        ];
    }

    /* Phone number check */
    if (!preg_match("/^(06)[0-9]{8}$/", $user_info['phonenumber'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Telefoonnummer is niet in het gewenste formaat.'
        ];
    }

    /* Full name check */
    if (!preg_match("/^[a-zA-Z-' ]*$/", $user_info['full_name'])) {
        return [
            'type' => 'danger',
            'message' => 'Het veld Volledige Naam is niet in het gewenste formaat (gebruik alleen letters, koppeltekens, spaties en apostrof\'s)'
        ];
    }

    $stmt = $pdo->prepare("UPDATE users SET username = ?, full_name = ?, email = ?, phonenumber = ?, birth_date = ?, language = ?, occupation = ?, biography = ? WHERE id = ?");
    $stmt->execute([
        $user_info['username'],
        $user_info['full_name'],
        $user_info['email'],
        $user_info['phonenumber'],
        $user_info['birth_date'],
        $user_info['language'],
        $user_info['occupation'],
        $user_info['biography'],
        $user_info['id']
    ]);
    $updated = $stmt->rowCount();

    /* Return feedback */
    if ($updated ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Account informatie is gewijzigd!")
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Er is niks gewijzigd.'
        ];
    }
}

/**
 * function which deletes and instance of a user
 * @param $pdo object
 * @param $user_id int id of the user to be deleted
 * @return array|string[] containing feedback
 */
function remove_user($pdo, $user_id){
    /* Check if there are opt-ins */
    try {
        $stmt = $pdo->prepare('SELECT * FROM opt_in WHERE user_id = ?');
        $stmt->execute([$user_id]);
        $user_info = $stmt->fetch();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er is iets foutgegaan: %s', $e->getMessage())
        ];
    }

    /* Delete all opt_in instances */
    if (!empty($user_info) ) {
        $stmt = $pdo->prepare("DELETE FROM opt_in WHERE user_id = ?");
        $stmt->execute([$user_id]);
    }

    /* Check if there are any rooms the user owns */
    try {
        $stmt = $pdo->prepare('SELECT * FROM rooms WHERE owner = ?');
        $stmt->execute([$user_id]);
        $user_info = $stmt->fetch();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er is iets foutgegaan: %s', $e->getMessage())
        ];
    }

    /* Delete all rooms the user owns */
    if (!empty($user_info) ) {
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE owner = ?");
        $stmt->execute([$user_id]);
    }

    /* Check if there are messages */
    try {
        $stmt = $pdo->prepare('SELECT * FROM messages WHERE sender_id = ? OR receiver_id = ?');
        $stmt->execute([$user_id, $user_id]);
        $user_info = $stmt->fetch();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er is iets foutgegaan: %s', $e->getMessage())
        ];
    }

    /* Delete all message instances */
    if (!empty($user_info) ) {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE receiver_id = ? OR sender_id = ?");
        $stmt->execute([$user_id, $user_id]);
    }

    /* Delete user */
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $deleted = $stmt->rowCount();

    /* Return feedback */
    if ($deleted ==  1) {
        logout_user();
        return [
            'type' => 'success',
            'message' => 'Je account is verwijderd!'
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Er is iets foutgegaan. Probeer het opnieuw!'.$user_id
        ];
    }
}

/**
 * function which checks if an opt-in exists between the active user and a room
 * @param $pdo object
 * @param $user_id int id of current user
 * @param $room_id int id of the room
 * @return array containing feedback or containing appropriate html code
 */
function check_opt_in($pdo, $user_id, $room_id){
    try {
        $stmt = $pdo->prepare('SELECT * FROM opt_in WHERE user_id = ? AND room_id = ?');
        $stmt->execute([$user_id, $room_id]);
        $info = $stmt->fetch();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er is iets foutgegaan: %s', $e->getMessage())
        ];
    }

    /* Return correct button */
    if (empty($info)) {
        return [
            'opt_in' => True,
            'button' => '<button type="submit" class="btn btn-warning">Inschrijven</button>'
        ];
    }
    else{
        return [
            'opt_in' => False,
            'button' => '<button type="submit" class="btn btn-danger">Uitschrijven</button>'
        ];
    }
}

/**
 * function which deletes an opt-in instance from the database
 * @param $pdo object
 * @param $user_id int user id of the opt-in to be deleted, part of the PK
 * @param $room_id int room id of the opt-in to be deleted, part of the PK
 * @return string[] containing feedback
 */
function opt_out($pdo, $user_id, $room_id){
    $room_id = intval($room_id);
    $user_id = intval($user_id);
    $stmt = $pdo->prepare("DELETE FROM opt_in WHERE user_id = ? AND room_id = ?");
    $stmt->execute([intval($user_id), intval($room_id)]);

    /* Return feedback */
    $deleted = $stmt->rowCount();
    if ($deleted ==  1) {
        return [
            'type' => 'success',
            'message' => 'Je hebt je uitgeschreven voor deze kamer'
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Er is iets foutgegaan. Probeer het opnieuw!'
        ];
    }
}

/**
 * function which creates an opt-in instance in the database
 * @param $pdo object
 * @param $user_id int user id of the opt-in to be added, part of the PK
 * @param $room_id int room id of the opt-in to be added, part of the PK
 * @return string[] containing feedback
 */
function opt_in($pdo, $user_id, $room_id){
    $room_id = intval($room_id);
    $user_id = intval($user_id);
    $stmt = $pdo->prepare('INSERT INTO opt_in (user_id, room_id) VALUES (?,?)');
    $stmt->execute(
        [
            $user_id,
            $room_id
        ]
    );

    /* Return feedback */
    $added = $stmt->rowCount();
    if ($added ==  1) {
        return [
            'type' => 'success',
            'message' => 'Je hebt je ingeschreven voor deze kamer'
        ];
    }
    else {
        return [
            'type' => 'warning',
            'message' => 'Er is iets foutgegaan. Probeer het opnieuw!'
        ];
    }
}

/**
 * function which returns in which rooms the given user has opted-in or owns
 * @param $pdo object
 * @param $user_id int id of the user who's opt-in are to be retrieved
 * @return array containing feedback or the opt-ins
 */
function get_user_rooms($pdo, $user_id){
    if (get_role($pdo, $user_id) == 0) {
        $query = 'SELECT * FROM rooms R, opt_in OI WHERE R.id = OI.room_id AND OI.user_id = ?';
    } else {
        $query = 'SELECT * FROM rooms WHERE owner = ?';
    }
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
        $user = $stmt->fetchAll();
    } catch (\PDOException $e) {
        return [
            'type' => 'danger',
            'message' => sprintf('Er is iets foutgegaan: %s', $e->getMessage())
        ];
    }

    /* Create array with htmlspecialchars */
    $user_exp = Array();
    foreach ($user as $key => $value){
        foreach ($value as $user_key => $user_input) {
            $user_exp[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $user_exp;
}

/**
 * function which creates a table containing information of user related rooms
 * @param $rooms array containing information of the rooms the given user has opted-in or owns
 * @return string containing html code with the table
 */
function get_account_table($rooms){
    $table_exp = '
    <table class="table table-hover">
    <thead>
    <tr>
        <th scop="col">Stad</th>
        <th scope="col">Straat</th>
        <th scope="col">Prijs</th>
        <th scop="col">Oppervlakte</th>
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
            <td><a href="/DDWT20-Final-Project/room/?id='.$value['id'].'" role="button" class="btn btn-primary">Meer informatie</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    ';
    return $table_exp;
}

/**
 * function which creates a table which shows to the owner who has opted-in to a room
 * @param $pdo object
 * @param $room_id int id of the room which opt-ins we want to retrieve
 * @return string containing html code for the table
 */
function get_opt_in_table($pdo, $room_id) {
    $stmt = $pdo->prepare('SELECT user_id FROM opt_in WHERE room_id = ?');
    $stmt->execute([$room_id]);
    $opt_in = $stmt->fetchAll();
    $opt_in_exp = Array();

    /* Create array with htmlspecialchars */
    foreach ($opt_in as $key => $value){
        foreach ($value as $opt_in_key => $opt_in_input) {
            $opt_in_exp[$key][$opt_in_key] = htmlspecialchars($opt_in_input);
        }
    }
    $table_exp = '<div class="opt-ins">
    <table class="table table-hover table-bordered">
    <thead
    </thead>
    <tbody>';
    foreach($opt_in_exp as $key => $value) {
        $name = get_name($pdo, $value['user_id']);
        $table_exp .= '
        <tr>
            <td><a href="/DDWT20-Final-Project/account_details/?id='.$value['user_id'].'">'.$name.'</a></td>
        </tr>
        ';
    }
    $table_exp .= '
    </tbody>
    </table>
    </div>
    ';
    return $table_exp;
}

/**
 * function which returns the number of opt-ins for a room
 * @param $pdo object
 * @param $room_id int id of the room for which we want the number of opt-in
 * @return mixed contains the number of opt-ins
 */
function get_number_opt_in($pdo, $room_id) {
    $stmt = $pdo->prepare('SELECT * FROM opt_in WHERE room_id = ?');
    $stmt->execute([$room_id]);
    $number_opt_ins = $stmt->rowCount();
    return $number_opt_ins;
}

/**
 * function which creates a message instance in the database
 * @param $pdo object
 * @param $message_info array containing message information to be added to the database
 * @return array|string[] containing feedback
 */
function send_message($pdo, $message_info) {
    if (
        empty($message_info['message']) or
        empty($message_info['receiver']) or
        empty($message_info['sender']) or
        empty($message_info['date'])
    ) {
        return [
            'type' => 'danger',
            'message' => 'Vul alle velden in om door te gaan.'
        ];
    }

    if (strlen($message_info['message']) > 255) {
        return [
            'type' => 'danger',
            'message' => 'Er ging iets fout, je bericht is te lang!'
        ];
    }

    /* Add message to database */
    $stmt = $pdo->prepare("INSERT INTO messages (text, date_and_time, receiver_id, sender_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $message_info['message'],
        $message_info['date'],
        $message_info['receiver'],
        $message_info['sender'],
    ]);
    $pdo->lastInsertId();

    /* Return feedback */
    $inserted = $stmt->rowCount();
    if ($inserted ==  1) {
        return [
            'type' => 'success',
            'message' => sprintf("Het bericht is verstuurd!")
        ];
    }
    else {
        return [
            'type' => 'danger',
            'message' => 'Er is iets fout gegaan. Probeer het opnieuw!'
        ];
    }
}

/**
 * function which retrieves all messages between two user
 * @param $pdo object
 * @param $receiver_id int user id of the receiver of the message, part of the PK
 * @param $sender_id int user id  of the sender of the message, part of the PK
 * @return string containing html code for a table of the messages
 */
function get_message_history($pdo, $receiver_id, $sender_id)
{
    $stmt = $pdo->prepare('SELECT * FROM messages WHERE (receiver_id = ? AND sender_id = ?) OR (receiver_id = ? AND sender_id = ?)');
    $stmt->execute([$receiver_id, $sender_id, $sender_id, $receiver_id]);
    $messages = $stmt->fetchAll();
    $messages_array = Array();

    /* Create array with htmlspecialchars */
    foreach ($messages as $key => $value){
        foreach ($value as $message_key => $message_input) {
            $messages_array[$key][$message_key] = htmlspecialchars($message_input);
        }
    }

    $message_history = '';
    foreach ($messages_array as $key => $value) {
        if ($value['sender_id'] == get_current()) {
            $class = "chat_container darker";
        } else {
            $class = "chat_container";
        }
        $date_time = date('d-m-Y H:i', strtotime($value['date_and_time']));
        $message_history = '
    <div class="'.$class.'">
    <p class="bericht">'.$value['text'].'</p>
    <b><span>'.get_name($pdo, $value['sender_id']).'</span></b>
    <span class="time-right">'.$date_time.'</span>
    </div>
    '.$message_history;
    }

    return $message_history;
}

/**
 * function which retrieves all conversations the current user has
 * @param $pdo object
 * @param $user_id int id of the user who's conversations to be retrieved
 * @return string containing html code of a table with all conversations
 */
function get_message_table($pdo, $user_id) {
    $stmt = $pdo->prepare('SELECT DISTINCT receiver_id, sender_id
                           FROM messages m1
                           WHERE (receiver_id = ? OR sender_id = ?) AND NOT EXISTS (
                                             SELECT receiver_id, sender_id
                                             FROM messages m2
                                             WHERE m2.receiver_id = m1.sender_id AND m2.sender_id = m1.receiver_id AND m2.sender_id > m2.receiver_id)
                                             ORDER BY receiver_id, sender_id');
    $stmt->execute([$user_id, $user_id]);
    $messages = $stmt->fetchAll();
    $messages_array = Array();

    /* Create array with htmlspecialchars */
    foreach ($messages as $key => $value){
        foreach ($value as $message_key => $message_input) {
            $messages_array[$key][$message_key] = htmlspecialchars($message_input);
        }
    }

    $inbox = '
    <table class="table table-hover">
    <thead
    <th>
        <th scop="col">Naam</th>
        <th scope="col"></th>
    </tr>
    </thead>
    <tbody>';
    foreach($messages_array as $key => $value){
        if (get_current() == $value['sender_id']) {
            $id = $value['receiver_id'];
        } else {
            $id = $value['sender_id'];
        }
        $inbox .= '
        <tr>
            <th scope="row">'.get_username($pdo, $id).'</th>
            <td><a href="/DDWT20-Final-Project/send_message/?id='.$id.'" role="button" class="btn btn-primary">Zie berichtengeschiedenis</a></td>
        </tr>
        ';
    }
    $inbox .= '
    </tbody>
    </table>
    ';
    return $inbox;
}

/**
 * function which retrieves the number of user in the database
 * @param $pdo object
 * @return mixed number of users
 */
function get_amount_users($pdo){
    $stmt = $pdo->prepare('SELECT * FROM users');
    $stmt->execute();
    $users = $stmt->rowCount();
    return $users;
}

/**
 * function which retrieves the number of rooms in the database
 * @param $pdo object
 * @return mixed number of rooms
 */
function get_amount_rooms($pdo){
    $stmt = $pdo->prepare('SELECT * FROM rooms');
    $stmt->execute();
    $rooms = $stmt->rowCount();
    return $rooms;
}

/**
 * function which returns information of the latest room added to the database
 * @param $pdo object
 * @return array information of the latest room
 */
function get_latest_room($pdo){
    $stmt = $pdo->prepare('SELECT * FROM rooms ORDER BY id DESC LIMIT 0, 1');
    $stmt->execute();
    $room_info = $stmt->fetch();
    $room_info_exp = Array();
    if (empty($room_info_exp)){
        return $room_info_exp;
    }
    /* Create array with htmlspecialchars */
    foreach ($room_info as $key => $value){
        $room_info_exp[$key] = htmlspecialchars($value);
    }
    return $room_info_exp;
}

/**
 * function which checks if a given id exists in the database
 * @param $pdo object
 * @param $id int of the id we want to check
 * @return bool which tells if the id exists or not
 */
function check_id($pdo, $id) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id=?");
    $stmt->execute([$id]);
    $result = $stmt->rowCount();
    if($result == 0) {
        return False;
    } else {
        return True;
    }
}