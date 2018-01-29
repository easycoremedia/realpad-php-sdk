<?php

namespace Core;

require_once 'realpadDriver.php';

class Main
{
    /**
     * @return string
     * @description this method handles front-end form
     *
     */
    public static function init()
    {
        $body = [];

        if (!empty($_POST['login'])
            &&
            !empty($_POST['password'])
        ) {
            $body = [
                'login' => $_POST['login'],
                'password' => $_POST['password']
            ];

            switch ($_POST['endpoint']) {
                case 'list-projects' :
                    break;

                case 'get-project' :
                    $body['screenid'] = $_POST['screenid-gp'];
                    $body['developerid'] = $_POST['developerid'];
                    $body['projectid'] = $_POST['projectid'];
                    break;

                case 'get-all-projects' :
                    $body['screenid'] = $_POST['screenid-gap'];
                    break;

                case 'create-lead' :
                    foreach ($_POST as $k => $v) {
                        if (isset($v) && !empty($v)) {
                            $body[$k] = $v;
                        }
                    }
                    unset($body['endpoint']);
                    break;
            }
        }
        $driver = new realpadDriver($_POST['endpoint']);
        return $driver->post($body);
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return bool|string
     * @description This function just parses XML object to display response
     *
     */
    public static function renderResponse($xml)
    {

        $projectData = '';

        if ($xml instanceof \SimpleXMLElement) {
            foreach ($xml->project as $item) {
                $projectData .= '<tr>';
                $projectData .= '<td>' . $item->attributes()->id . '</td>';
                $projectData .= '<td>' . $item->attributes()->name . '</td>';
                $projectData .= '<td>' . $item->attributes()->gps . '</td>';
                $projectData .= '</tr>';
            }
        } else {
            $projectData = '<tr><td>' .  $xml . '</td></tr>';
        }
        return $projectData;
    }
}

$xml = Main::init();
$markUp = Main::renderResponse($xml);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>RealPad API PHP SDK</title>
</head>
<body>
<style>
    body {
        background: url('https://realpad.eu/wp-content/themes/realpad/images/section-both.png');
        padding-top: 50px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-2">
            <img src="https://realpad.eu/wp-content/themes/realpad/images/logo-text.svg" alt="">
        </div>
        <div class="col-sm-10">
            <h2>Hello! Welcome to REALPAD API demo. All details you can see here. This SDK provides all information
                about queries to REALPAD API</h2>
        </div>
    </div>
    <div class="row">
        <form method="post">
            <table class="table">
                <tbody>
                <tr>
                    <td><label for="">Login<input type="text" class="form-control" name="login"
                                                  value="<?= isset($_POST['login']) ? $_POST['login'] : ''; ?>"></label>
                    </td>
                    <td><label for="">Password<input type="password" class="form-control" name="password"
                                                     value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>"></label>
                    </td>
                    <td>
                        <label for="">
                            Endpoint
                            <select name="endpoint" class="form-control" id="">
                                <option value="list-projects">list-projects</option>
                                <option value="get-project">get-project</option>
                                <option value="get-all-projects">get-all-projects</option>
                                <option value="create-lead">create-lead</option>
                            </select>
                        </label>
                    </td>
                </tr>
                <tr id="get-project" class="d-none params">
                    <td>
                        <label for="">
                            Screen id<input type="text" class="form-control" name="screenid-gp"
                                            value="<?= isset($_POST['screenid-gp']) ? $_POST['screenid-gp'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Developer id<input type="text" class="form-control" name="developerid"
                                               value="<?= isset($_POST['developerid']) ? $_POST['developerid'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Project id<input type="text" class="form-control" name="projectid"
                                             value="<?= isset($_POST['projectid']) ? $_POST['projectid'] : ''; ?>">
                        </label>
                    </td>
                </tr>
                <tr id="get-all-projects" class="d-none params">
                    <td>
                        <label for="">
                            Screen id<input type="text" class="form-control" name="screenid-gap"
                                            value="<?= isset($_POST['screenid']) ? $_POST['screenid'] : ''; ?>">
                        </label>
                    </td>
                </tr>
                <tr id="create-lead" class="d-none params">
                    <td>
                        <label for="">
                            Name<input type="text" class="form-control" name="name"
                                       value="<?= isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Surname<input type="text" class="form-control" name="surname"
                                          value="<?= isset($_POST['surname']) ? $_POST['surname'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Email<input type="email" class="form-control" name="email"
                                        value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Phone<input type="tel" class="form-control" name="phone"
                                        value="<?= isset($_POST['phone']) ? $_POST['phone'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Note<input type="text" class="form-control" name="note"
                                       value="<?= isset($_POST['note']) ? $_POST['note'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Referral<input type="text" class="form-control" name="referral"
                                           value="<?= isset($_POST['referral']) ? $_POST['referral'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Campaign<input type="text" class="form-control" name="campaign"
                                           value="<?= isset($_POST['campaign']) ? $_POST['campaign'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Language<input type="text" class="form-control" name="language"
                                           value="<?= isset($_POST['language']) ? $_POST['language'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Internal id<input type="text" class="form-control" name="internalid"
                                              value="<?= isset($_POST['internalid']) ? $_POST['internalid'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Tags<input type="text" class="form-control" name="tags"
                                       value="<?= isset($_POST['tags']) ? $_POST['tags'] : ''; ?>">
                        </label>
                    </td>
                    <td>
                        <label for="">
                            Partner<input type="text" class="form-control" name="partner"
                                          value="<?= isset($_POST['partner']) ? $_POST['partner'] : ''; ?>">
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit" class="btn btn-success">Post</button>
                    </td>
                </tr>
                </tbody>
            </table>

        </form>
        <table class="table">
            <tbody>
            <?= $markUp; ?>
            </tbody>
        </table>

    </div>
</div>
<footer>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('select[name="endpoint"]').on('change', function () {
                var value = $(this).val();
                $('.params').each(function (k, v) {
                    if (!$(v).hasClass('d-none')) {
                        $(v).addClass('d-none');
                    }
                });
                $('#' + value).toggleClass('d-none');
            });
        });
    </script>
</footer>
</body>
</html>
