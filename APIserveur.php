<?php
	include('jwt_utils.php');
	include('APImylib.php');
	include('Fonctions.php');

	/// Paramétrage de l'entête HTTP (pour la réponse au Client)
	header("Content-Type:application/json");

	$linkedPDO = connexionBd();

	/// Identification du type de méthode HTTP envoyée par le client
	$http_method = $_SERVER['REQUEST_METHOD'];


	switch ($http_method) {

	    /// Cas de la méthode GET
	    case "GET":

		    $bearer_token = '';
			$bearer_token = get_bearer_token();	

			echo $bearer_token;

			// Vérification de la validation du jeton
			if(!is_jwt_valid($bearer_token)){
				deliver_response(500, "erreur de validité de jeton", NULL);
				break;
			//Si jeton valide, on récupère le rôle de l'utilisateur
			}else{
				$role = get_role_jwt($bearer_token);
			}
			
            if (isset($_GET['id'])) {

                // Vérification syntaxe de l'id
                if (!is_numeric($_GET['id'])) {
                	//erreur de syntaxe ID invalide
                	deliver_response(400, "Erreur de syntaxe, ID invalide", NULL);
                	break;
                } 

                // Vérifie que l'id existe
                if (!is_id_exist($_Get['id'])) {
                	//L'identifiant n'existe pas 
                	deliver_response(404, "ID inconnu", NULL);
                	break;
                }

                //Pour savoir quel Get on traîte, on regarde le contenu du body/url

                $matchingData = get($_GET['id']);

            } else {
                $matchingData = getALL();
            }

            get_erreur($matchingdata, "Article trouvé", "Article : ".$matchingData);

	        break;
	        

	    /// Cas de la méthode POST
	    case "POST":

	    	$bearer_token = '';
			$bearer_token = get_bearer_token();	

			// Vérification de la validation du jeton
			if(!is_jwt_valid($bearer_token)){
				deliver_response(500, "erreur de validité de jeton", NULL);
				break;

			//Si jeton valide, on récupère le rôle de l'utilisateur
			}else{
				$role = get_role_jwt($bearer_token);
			}

	    	if ($role == 'Publisher'){

	    		/// Récupération des données envoyées par le Client
	            $postedData = file_get_contents('php://input');
	            $postedData = json_decode($postedData, true);

	            /// Traitement
	            $matchingData = post($postedData['phrase']);

		    	get_erreur($matchingdata, "Article ajouté", "Article : ".$matchingData);

		        break;
		    }

	    /// Cas de la méthode PUT
	    case "PUT":

	    	$bearer_token = '';
			$bearer_token = get_bearer_token();	

			// Vérification de la validation du jeton
			if(!is_jwt_valid($bearer_token)){
				deliver_response(500, "erreur de validité de jeton", NULL);
				break;

			//Si jeton valide, on récupère le rôle de l'utilisateur
			}else{
				$role = get_role_jwt($bearer_token);
			}

	    	if ($role == 'Publisher'){

	    		if (isset($_GET['id'])) {

	                // Vérification syntaxe de l'id
	                if (!is_numeric($_GET['id'])) {
	                	//erreur de syntaxe ID invalide
	                	deliver_response(400, "Erreur de syntaxe, ID invalide", NULL);
	                	break;
	                } 

	                // Vérifie que l'id existe
	                if (!is_id_exist($_Get['id'])) {
	                	//L'identifiant n'existe pas 
	                	deliver_response(404, "ID inconnu", NULL);
	                	break;
	                }

		            /// Récupération des données envoyées par le Client
		            $postedData = file_get_contents('php://input');
		            $postedData = json_decode($postedData, true);

		            /// Traitement
		            $matchingData = put($_GET['id'], $postedData['phrase']);

			    	get_erreur($matchingdata, "Article modifié", "Article : ".$matchingData);
	            
	            }else{
                	//ID non renseigné
                	deliver_response(400, "Erreur, identifiant manquant", NULL);
                	break;
	            }

		        break;
		    }

	    /// Cas de la méthode DELETE
	    case "DELETE":

	    	$bearer_token = '';
			$bearer_token = get_bearer_token();	

			// Vérification de la validation du jeton
			if(!is_jwt_valid($bearer_token)){
				deliver_response(500, "erreur de validité de jeton", NULL);
				break;

			//Si jeton valide, on récupère le rôle de l'utilisateur
			}else{
				$role = get_role_jwt($bearer_token);
			}

    		if (isset($_GET['id'])) {

                // Vérification syntaxe de l'id
                if (!is_numeric($_GET['id'])) {
                	//erreur de syntaxe ID invalide
                	deliver_response(400, "Erreur de syntaxe, ID invalide", NULL);
                	break;
                } 

                // Vérifie que l'id existe
                if (!is_id_exist($_Get['id'])) {
                	//L'identifiant n'existe pas 
                	deliver_response(404, "ID inconnu", NULL);
                	break;
                }

                //On traite les différent cas par rôle
                if ($role == 'Publisher'){
	                /// Traitement pour un publisher
	                $matchingData = deleteByPublisher($_GET['id']);
	            }else {
	                /// Traitement pour tout autre rôle
	                $matchingData = delete($_GET['id']);
		        }
            }

			get_erreur($matchingdata, "Article supprimé", NULL);

	        break;

	    default:

	        deliver_response(404, "Méthode introuvable", null);
	        break;
	}
	
?>