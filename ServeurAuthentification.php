<?php
	include('APImylib.php');
	include('APIserveur.php');
	///pour récupérer le role, faire un fonction qui utilise is_jwt_valid
	$http_method = $_SERVER['REQUEST_METHOD'];
	switch($http_method){
		case "POST":
            /// Récupération des données envoyées par le Clients
			$postedata = file_get_contents('php://input');
            $data = json_decode($postedata);
			if (!isValidUser($data->username, $data->password)){
                deliver_response(401, "identifiant invalide", null);
				break;
			}
			$username = $data->username;
			$role = get_user_role($username);
			$headers = array('alg' => 'HS256', 'typ' => 'JWT');
		    $payload = array('id' => $username, 'role' => $role, 'exp' => (time() + 60)); // Récupérer le rôle de l'utilisateur
		    $jwt = generate_jwt($headers, $payload);
			deliver_response(200, "connexion reussie", $jwt);

		break;
	}
?>