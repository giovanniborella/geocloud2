<?php
/**
 * @author     Martin Høgh <mh@mapcentia.com>
 * @copyright  2013-2024 MapCentia ApS
 * @license    http://www.gnu.org/licenses/#AGPL  GNU AFFERO GENERAL PUBLIC LICENSE 3
 *
 */

use app\auth\GrantType;
use app\models\Client;
use app\models\Database;
use app\models\Session as SessionModel;
use app\inc\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);
Database::setDb("mapcentia");

if (Session::isAuth()) {
    include 'validate.php';
    // Check client id
    try {
        Database::setDb($_SESSION['parentdb']);
        (new Client())->get($_GET['client_id']);
    } catch (Exception) {
        echo "<div id='alert' hx-swap-oob='true'>Client not found</div>";
        exit();
    }
    $code = $_GET['response_type'] == 'code';
    $data = (new SessionModel())->createOAuthResponse($_SESSION['parentdb'], $_SESSION['screen_name'], $_SESSION['subuser'], $_SESSION['usergroup'], $code);
    $params = [];
    if ($code) {
        $params['code'] = $data['code'];
    } else {
        $params['access_token'] = $data['access_token'];
        $params['token_type'] = $data['token_type'];
        $params['expires_in'] = $data['expires_in'];
    }
    if ($_GET['state']) {
        $params['state'] = $_GET['state'];
    }
    $paramsStr = http_build_query($params);
    echo $paramsStr;
    header("Location: {$_GET['redirect_uri']}?$paramsStr");
    exit();
}

if ($_POST['database'] && $_POST['user'] && $_POST['password']) {
    // Start session and refresh browser
    try {
        $grantType = match ($_POST['response_type']) {
            'code' => GrantType::AUTHORIZATION_CODE,
            'access' => GrantType::PASSWORD,
            default => null,
        };
        $data = (new SessionModel())->start($_POST['user'], $_POST['password'], "public", $_POST['database']);
        header('HX-Refresh:true');

    } catch (Exception) {
        $res = (new \app\models\User())->getDatabasesForUser($_POST['user']);
        echo $twig->render('login.html.twig', [...$res, ...$_POST]);
        echo "<div id='alert' hx-swap-oob='true'>Wrong password</div>";
    }
} elseif ($user = $_POST['user']) {
    // Get database for user
    $res = [];
    try {
        $res = (new \app\models\User())->getDatabasesForUser($user);
        echo "<div id='alert' hx-swap-oob='true'></div>";
    } catch (Exception) {
        echo "<div id='alert' hx-swap-oob='true'>User doesn't exists</div>";
    }
    echo $twig->render('login.html.twig', [...$res, ...$_POST]);
} else {
    // Start
    include 'validate.php';
    ?>
    <script src="https://unpkg.com/htmx.org@1.9.11"></script>
    <form hx-post="/auth/">
        <?php
        echo $twig->render('login.html.twig', $_GET);
        ?>
    </form>
    <div id="alert"></div>
    <?php
}