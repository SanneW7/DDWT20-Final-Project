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
    $navigation_exp = '
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