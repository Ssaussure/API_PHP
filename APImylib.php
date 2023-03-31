<?php

include('Erreurs.php');

function connexionBd(){

    // informations de connection
    $SERVER = '127.0.0.1';
    $DB = 'projetphp';
    $LOGIN = 'root';
    $MDP = 'iutinfo';

    // tentative de connexion à la BD
    try {
        // connexion à la BD
        $linkpdo = new PDO("mysql:host=$SERVER;dbname=$DB", $LOGIN, $MDP);
    } catch (Exception $e) {
        die('Erreur ! Problème de connection à la base de données : ' . $e->getMessage());
    }

    // retourne la connection
    return $linkpdo;
}

$users = array(
    array(
        "username" => "CbrtLine",
        "password" => "iutinfo",
        "role" => "Publisher"
        // Droit : Poster, Consulter ses articles, Consulter les articles*, 
        // Modif ses articles, Suppr ses articles, Liker/disliker
        // *(auteur, date de publication, contenu, nombre total de like, nombre total de dislike)
    ),
    array(
        "username" => "AlrdFabien",
        "password" => "FabInfo",
        "role" => "Moderator"
        // Droit : Consulter*, Supprimer
        // *(auteur, date de publication, contenu, liste des utilisateurs ayant liké l’article, nombre
        // total de like, liste des utilisateurs ayant disliké l’article, nombre total de dislike)
    )
);
// l'autre role c'est le anonyme

function get($id){

    // Vérification de l'id
    if (!is_numeric($id)){
        return SYNTAXE;
    }

    $linkpdo = connexionBd();

    // preparation de la Requête sql
    $req = $linkpdo->prepare('select * from articles where id = :id');
    if ($req == false) {
        die('Erreur !');
    }
    // execution de la Requête sql
    $req->execute(array('id' => $id));
    if ($req == false) {
        die('Erreur !');
    }
    return $req->fetchAll();
}

function getAll()
{
    $linkpdo = connexionBd();
    // preparation de la Requête sql
    $req = $linkpdo->prepare('select * from articles');
    if ($req == false) {
        die('Erreur ! GetAll');
    }
    // execution de la Requête sql
    $req->execute();
    if ($req == false) {
        die('Erreur ! GetAll');
    }
    return $req->fetchAll();
}

function post($publication, $auteur)
{
    $linkpdo = connexionBd();

    // preparation de la Requête sql
    $req = $linkpdo->prepare('
        insert into articles 
        (publication,auteur,like_pub,dislike,date, signalement) 
        value(:publication,:auteur, 0,0,NOW(),0)
        ');

    if ($req == false) {
        die('Erreur ! Post');
    }

    //Définition des paramètres
    $param = [
        [':publication', $publication, PDO::PARAM_STR],
        [':auteur', $auteur, PDO::PARAM_STR],
    ];

    //teste des paramètre
    foreach ($param as $params) {
        if (!$req->bindParam($params[0], $params[1], $params[2])) {
            return ERREUR_SQL;
        }
    }

    if (!$req->execute()){
        $linkpdo->rollback();
        return ERREUR_SQL;
    } 

    // recuperation du dernier id
    $lastId = $linkpdo->lastInsertId();
    $linkpdo->commit();
    return getId($lastId);
}



function put($id, $publication)
{
    $linkpdo = connexionBd();

    // preparation de la Requête sql
    $req = $linkpdo->prepare('
        update articles 
        set publication = :publication 
        where id = :id
        ');

    if ($req == false) {
        die('Erreur ! Put');
    }

    //Définition des paramètres
    $param = [
        [':id', $id, PDO::PARAM_INT],
        [':publication', $publication, PDO::PARAM_STR],
    ];

    //teste des paramètre
    foreach ($param as $params) {
        if (!$req->bindParam($params[0], $params[1], $params[2])) {
            return ERREUR_SQL;
        }
    }

    if (!$req->execute()){
        $linkpdo->rollback();
        return ERREUR_SQL;
    } 

    // recuperation du dernier id
    $lastId = $linkpdo->lastInsertId();
    $linkpdo->commit();
    return getId($lastId);
}

function delete($id)
{
    $linkpdo = connexionBd();

    // preparation de la Requête sql
    $req = $linkpdo->prepare('
        delete 
        from articles 
        where id = :id
        ');

    if ($req == false) {
        die('Erreur ! Delete');
    }

    if (!is_numeric($id)){
        deliver_response(400, "Erreur de syntaxe, ID invalide", NULL);
    }

    if (!$req->execute(array('id' => $id))){
        $linkpdo->rollback();
        return ERREUR_SQL;
    }

    deliver_response(200, "Article supprimé", NULL);

}

	function isValidUser($username, $password) {
        global $users;
        foreach ($users as $user) {
            if($user['username'] == $username) {
                if($user['password'] == $password) {
                    return true;
                }
                return false;
            }
        }
        return false;
    }

	/// Permet de récuperer le rôle d'un utilisateur
	/// Cette fonction n'est appelée qu'une fois que l'identification est vérifiée
	function get_user_role($username) {

		global $users;

		foreach ($users as $user) {
		    if ($user["username"] == $username) {
		    	return $user["role"];
		    }
		}

		// Si aucun utilisateur n'est trouvé avec le nom d'utilisateur spécifié, retourner null
		return null;
	}

		/// Envoi de la réponse au Client
	function deliver_response($status, $status_message, $data)
	{
	    /// Paramétrage de l'entête HTTP, suite
	    header("HTTP/1.1 $status $status_message");
	    /// Paramétrage de la réponse retournée
	    $response['status'] = $status;
	    $response['status_message'] = $status_message;
	    $response['data'] = $data;
	    /// Mapping de la réponse au format JSON
	    $json_response = json_encode($response);
	    echo $json_response;
	}

	// Permet de récupérer le role d'un utilisateur dans le payload
	function get_role_jwt($jwt) {

		// Vérification de validité
	    if (!is_jwt_valid($jwt)) {
	        // Jeton invalide ou expiré
	        return null;
	    }

	    $payload = base64_decode(explode('.', $jwt)[1]);
	    $decoded_payload = json_decode($payload, true);

	    if (!isset($decoded_payload['role'])) {
	        // Rôle non trouvé dans le jeton
	        return null;
	    }

	    return $decoded_payload['role'];
	}

	function is_id_exist($id) {
	    // Connexion à la BD
	    $linkpdo = connexionBd();
	    
	    // Requête pour vérifier si l'id existe dans la table appropriée
	    $req = $linkpdo->prepare("SELECT id FROM articles WHERE id=:id");
	    $req->bindParam(':id', $id);
	    $req->execute();
	    
	    // Si l'id est trouvé, renvoie true, sinon false
	    if ($req->rowCount() > 0) {
	        return true;
	    } else {
	        return false;
	    }
	}

?>
?>