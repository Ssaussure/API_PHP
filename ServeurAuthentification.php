<?php
	include('jwt_utils.php');
	include('Fonctions.php');

	///pour récupérer le role, faire un fonction qui utilise is_jwt_valid

	$http_method = $_SERVER['REQUEST_METHOD'];

	switch($http_method){
		case "POST":

            /// Récupération des données envoyées par le Clients
            $data = json_decode(file_get_contents('php://input'), TRUE);

			if (!isValidUser($data['username'], $data['password'])){
                deliver_response(200, "identifiant invalide", NULL);
			}else{
				$username = $data['username'];
				$role = get_user_role($username);
				$headers = array('alg' => 'HS256', 'typ' => 'JWT');
			    $payload = array('id' => $username, 'role' => $role, 'exp' => (time() + 3600)); // Récupérer le rôle de l'utilisateur
			    $jwt = generate_jwt($headers, $payload);
			    echo $jwt;
			}

		break;
	}
?>