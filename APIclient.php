<?php
//! last10
if (isset($_POST["last10"])) {
    // Cas des méthodes GETALL
    $result = file_get_contents(
        'http://localhost/APIserveur.php/',
        false,
        stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
    );
    $data = json_decode($result, true);
    echo '<form action="APIclient.php" method="POST">';
    echo '<table>
        <thead>
            <th>Id</th>
            <th>Publication</th>
            <th>Like</th>
            <th>Dislike</th>
            <th>uteur</th>
            <th>Fautes</th>
            <th>Signalements</th>
            <th>Modification</th>
            <th>Suppression</th>
        </thead><tbody>';
    foreach ($data['data'] as $v) {
        echo '<tr>';
        echo '<td>' . $v['id'] . '</td>';
        echo '<td>' . $v['publication'] . '</td>';
        echo '<td>' . $v['like_pub'] . '</td>';
        echo '<td>' . $v['date_ajout'] . '</td>';
        echo '<td>' . $v['date_modif'] . '</td>';
        echo '<td>' . $v['faute'] . '</td>';
        echo '<td>' . $v['signalement'] . '</td>';
        echo '<td><button type="submit" value="' . $v['id'] . '" name="modifier">Modifier</button></td>
                                <td><button type="submit" value="' . $v['id'] . '" name="supprimer">Supprimer</button></td></tr>';
    }
    echo '</tbody></table></form>';
}

//! signalement
if (isset($_POST["signalement"])) {
    // Cas des méthodes GETALL
    $result = file_get_contents(
        'http://localhost/APIserveur.php?id=signalement',
        false,
        stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
    );
    $data = json_decode($result, true);
    echo '<form action="APIclient.php" method="POST">';
    foreach ($data['data'] as $v) {
        echo '<table>
        <thead>
            <th>Id</th>
            <th>Phrase</th>
            <th>Vote</th>
            <th>Date d\'ajout</th>
            <th>Date de modification</th>
            <th>Fautes</th>
            <th>Signalements</th>
            <th>Modification</th>
            <th>Suppression</th>
        </thead>';
        echo '<tbody><tr>';
        echo '<td>' . $v['id'] . '</td>';
        echo '<td>' . $v['publication'] . '</td>';
        echo '<td>' . $v['like_pub'] . '</td>';
        echo '<td>' . $v['date_ajout'] . '</td>';
        echo '<td>' . $v['date_modif'] . '</td>';
        echo '<td>' . $v['faute'] . '</td>';
        echo '<td>' . $v['signalement'] . '</td>';
        echo '<td><button type="submit" value="' . $v['id'] . '" name="modifier">Modifier</button></td>
                                <td><button type="submit" value="' . $v['id'] . '" name="supprimer">Supprimer</button></td></tr></tbody></table>';
    }
    echo '</form>';
}

//! like_pub
if (isset($_POST["like_pub"])) {
    // Cas des méthodes GETALL
    $result = file_get_contents(
        'http://localhost/APIserveur.php?id=like_pub',
        false,
        stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
    );
    $data = json_decode($result, true);
    echo '<form action="APIclient.php" method="POST">';
    echo '<table>
        <thead>
            <th>Id</th>
            <th>Phrase</th>
            <th>Vote</th>
            <th>Date d\'ajout</th>
            <th>Date de modification</th>
            <th>Fautes</th>
            <th>Signalements</th>
            <th>Modification</th>
            <th>Suppression</th>
        </thead><tbody>';
    foreach ($data['data'] as $v) {
        echo '<tr>';
        echo '<td>' . $v['id'] . '</td>';
        echo '<td>' . $v['publication'] . '</td>';
        echo '<td>' . $v['like_pub'] . '</td>';
        echo '<td>' . $v['date_ajout'] . '</td>';
        echo '<td>' . $v['date_modif'] . '</td>';
        echo '<td>' . $v['faute'] . '</td>';
        echo '<td>' . $v['signalement'] . '</td>';
        echo '<td><button type="submit" value="' . $v['id'] . '" name="modifier">Modifier</button></td>
                                <td><button type="submit" value="' . $v['id'] . '" name="supprimer">Supprimer</button></td></tr>';
    }
    echo '</tbody></table></form>';
}

//DELETE
if (isset($_POST['supprimer'])) {
    $result = file_get_contents(
        'http://localhost/APIserveur.php?id=' . $_POST['supprimer'],
        false,
        stream_context_create(array('http' => array('method' => 'DELETE')))
    );
}
//PUT
if (isset($_POST['validation'])) {
    $data = array("publication" => $_POST['modif']);
    $data_string = json_encode($data);
    /// Envoi de la requête
    $result = file_get_contents(
        'http://localhost/APIserveur.php?id=' . $_POST['validation'],
        false,
        stream_context_create(array(
            'http' => array(
                'method' => 'PUT', // ou PUT
                'content' => $data_string,
                'header' => array('Content-Type: application/json' . "\r\n"
                    . 'Content-Length: ' . strlen($data_string) . "\r\n")
            )
        ))
    );
    $data = json_decode($result, true);
    echo '<h2>Dernière modification :</h2>';
    echo '<form action="APIclient.php" method="POST">';
    echo '<table>
        <thead>
            <th>Id</th>
            <th>Publication</th>
            <th>Like</th>
            <th>Dislike</th>
            <th>Date d\'ajout</th>
            <th>Auteur</th>
            <th>Signalements</th>
            <th>Modification</th>
            <th>Suppression</th>
        </thead><tbody>';
    foreach ($data['data'] as $v) {
        echo '<tr>';
        echo '<td>' . $v['id'] . '</td>';
        echo '<td>' . $v['publication'] . '</td>';
        echo '<td>' . $v['like_pub'] . '</td>';
        echo '<td>' . $v['dislike'] . '</td>';
        echo '<td>' . $v['date_ajout'] . '</td>';
        echo '<td>' . $v['auteur'] . '</td>';
        echo '<td>' . $v['signalement'] . '</td>';
        echo '<td><button type="submit" value="' . $v['id'] . '" name="modifier">Modifier</button></td>
                                <td><button type="submit" value="' . $v['id'] . '" name="supprimer">Supprimer</button></td></tr>';
    }
    echo '</tbody></table></form>';
}
// POST
if (isset($_POST['add'])) {
    $data = array("publication" => $_POST['publication']);
    $data_string = json_encode($data);
    /// Envoi de la requête
    $result = file_get_contents(
        'http://localhost/APIserveur.php',
        false,
        stream_context_create(array(
            'http' => array(
                'method' => 'POST', // ou PUT
                'content' => $data_string,
                'header' => array('Content-Type: application/json' . "\r\n"
                    . 'Content-Length: ' . strlen($data_string) . "\r\n")
            )
        ))
    );
    $data = json_decode($result, true);
    echo '<h2>Dernière modification :</h2>';
    echo '<form action="APIclient.php" method="POST">';
    foreach ($data['data'] as $v) {
        echo '<table>
        <thead>
            <th>Id</th>
            <th>publication</th>
            <th>Auteur</th>
            <th>Like</th>
            <th>Dislike</th>
            <th>Date</th>
            <th>Signalements</th>
            <th>Modification</th>
            <th>Suppression</th>
        </thead>';
        echo '<tbody><tr>';
        echo '<td>' . $v['id'] . '</td>';
        echo '<td>' . $v['publication'] . '</td>';
        echo '<td>' . $v['like_pub'] . '</td>';
        echo '<td>' . $v['dislike'] . '</td>';
        echo '<td>' . $v['date'] . '</td>';
        echo '<td>' . $v['auteur'] . '</td>';
        echo '<td>' . $v['signalement'] . '</td>';
        echo '<td><button type="submit" value="' . $v['id'] . '" name="modifier">Modifier</button></td>
                                <td><button type="submit" value="' . $v['id'] . '" name="supprimer">Supprimer</button></td></tr></tbody></table>';
    }
    echo '</form>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <form action="APIclient.php" method="POST">
        <h2>Nouvelles fonctionnalités :</h2>
        <label for="signalement">Last 10 : </label><button type="submit" name="last10">Valider</button>
        <label for="signalement">Vote : </label><button type="submit" name="like_pub">Valider</button>
        <label for="signalement">Signalement : </label><button type="submit" name="signalement">Valider</button>
        <h2>Ajouter une publication :</h2>
        <input type="text" name="publication">
        <button type="submit" name="add">Add</button>
        <?php
        // GETID
        if (isset($_POST['modifier'])) {
            $result = file_get_contents(
                'http://localhost/APIserveur.php?id=' . $_POST['modifier'],
                false,
                stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
            );
            $data = json_decode($result, true);
            foreach ($data['data'] as $v) {
                echo '<h2>Modifier votre publication :</h2><tr>';
                echo '<td><input type="text" name="modif" value="' . $v['publication'] . '"></td>';
                echo '<td><button type="submit" value="' . $v['id'] . '" name="validation">Valider</button></td>';
            }
        }
        ?>
        <table>
            <thead>
                <th>Id</th>
                <th>Phrase</th>
                <th>Vote</th>
                <th>Date d'ajout</th>
                <th>Date de modification</th>
                <th>Fautes</th>
                <th>Signalements</th>
                <th>Modification</th>
                <th>Suppression</th>
            </thead>
            <tbody>
                <?php
                // Cas des méthodes GETALL
                $result = file_get_contents(
                    'http://localhost/APIserveur.php',
                    false,
                    stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
                );
                $data = json_decode($result, true);
                foreach ($data['data'] as $v) {
                    echo '<tr>';
                    echo '<td>' . $v['id'] . '</td>';
                    echo '<td>' . $v['like_pub'] . '</td>';
                    echo '<td>' . $v['dislike'] . '</td>';
                    echo '<td>' . $v['auteur'] . '</td>';
                    echo '<td>' . $v['publication'] . '</td>';
                    echo '<td>' . $v['date'] . '</td>';
                    echo '<td>' . $v['signalement'] . '</td>';
                    echo '<td><button type="submit" value="' . $v['id'] . '" name="modifier">Modifier</button></td>
                                <td><button type="submit" value="' . $v['id'] . '" name="supprimer">Supprimer</button></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </form>
</body>

</html>