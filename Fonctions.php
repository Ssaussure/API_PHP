<?php
	
	$users = array(
		array(
			"username" => "CbrtLine",
			"password" => "iutinfo",
			"role" => ""
		),
		array(
			"username" => "AlrdFabien",
			"password" => "FabInfo",
			"role" => ""
		)
	)

	function isValidUser($username, $password){

		global $users;

		foreach ($users as $user) {
		    if ($user["username"] == $username) {
		    	if ($user["password"] == $password){
		    		return TRUE;
		    	}
		    	return False;
		    }
		    return False;
		}

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

?>