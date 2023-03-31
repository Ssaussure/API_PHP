<?php
	
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