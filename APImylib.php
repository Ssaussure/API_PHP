<?php
    function connexionBd()
    {
        // informations de connection
        $SERVER = '127.0.0.1';
        $DB = 'projetphp';
        $LOGIN = 'root';
        $MDP = '';
        // tentative de connexion à la BD
        try {
            // connexion à la BD
            $linkpdo = new PDO("mysql:host=$SERVER;dbname=$DB", $LOGIN, $MDP);
        } catch (Exception $e) {
            return null;
        }
        // retourne la connection
        return $linkpdo;
    }

	
	$users = array(
		array(
			"username" => "CbrtLine",
			"password" => "iutinfo",
			"role" => "Publisher"
		),
		array(
			"username" => "AlrdFabien",
			"password" => "FabInfo",
			"role" => "Moderator"
		)
	);

	function isValidUser($username, $password){

		global $users;

		foreach ($users as $user) {
		    if ($user["username"] == $username) {
		    	if ($user["password"] == $password){
		    		return True;
		    	}
		    	return False;
		    }
		}
		return False;
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


    function getId($linkpdo, $id)
    {
        if(empty($id) || !is_numeric($id)) {
            return null;
        }
        // preparation de la Requête sql
        try{
            $req = $linkpdo->prepare('select * from articles where id = :id');
        } catch(PDOException $e) {
            return null;
        }
        
        if($req->bindParam(':id', $id, PDO::PARAM_INT)) {
            if($req->execute()) {
                return $req->fetchAll(PDO::FETCH_ASSOC);
            }
        }
        return null;
    }



    function getAll($linkpdo)
    {
        try{
            $req = $linkpdo->prepare('select * from articles');
        }catch(PDOException $e){
            return null;
        }
        
        if ($req == false) {
            die('Erreur ! GetAll');
        }
        if($req->execute()) {
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function post($publication, $linkpdo, $jwt) {
        try{
            $req = $linkpdo->prepare('insert into articles (publication,auteur,like_pub,dislike,date, signalement) value(:publication,:auteur, 0,0,NOW(),0)');
        }catch(PDOException $e) {
            return null;
        }
        if ($req -> bindParam(':publication', $publication, PDO::PARAM_STR, ':auteur',get_role_jwt($jwt), PDO::PARAM_STR)) {
            if($req->execute()) {
                return $req->fetchAll(PDO::FETCH_ASSOC);
            }
        $id = $linkpdo->lastInsertId();
        return getId($id);
    }
        }


    function put($id, $publication, $linkpdo)
    {
        if(empty($id) || !is_numeric($id)) {
            return null;
        }

        try {
            // preparation de la Requête sql
            $req = $linkpdo->prepare('update articles set publication = :publication where id = :id');
        } catch(PDOException $e) {
            return null;
        }
        if ($req -> bindParam(':publication', $publication, PDO::PARAM_STR, ':id', $id, PDO::PARAM_INT)) {
            if($req->execute()) {
                return $req->fetchAll(PDO::FETCH_ASSOC);
            }

        }
    }

    function delete($id, $linkpdo){
        if(empty($id) || !is_numeric($id)) {
            return null;
        }
        try {
            $req = $linkpdo->prepare('delete from articles where id = :id');
        } catch(PDOException $e) {
            return null;
        }
        if($req -> bindParam(':id', $id, PDO::PARAM_INT)) {
            if($req->execute()){
                return $req->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }

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

    function get_usename_jwt($jwt) {

        // Vérification de validité
        if (!is_jwt_valid($jwt)) {
            // Jeton invalide ou expiré
            return null;
        }

        $payload = base64_decode(explode('.', $jwt)[1]);
        $decoded_payload = json_decode($payload, true);

        if (!isset($decoded_payload['username'])) {
            // Rôle non trouvé dans le jeton
            return null;
        }

        return $decoded_payload['username'];
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