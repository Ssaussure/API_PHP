<?php
	include('jwt_utils.php');	
	/// Librairies éventuelles (pour la connexion à la BDD, etc.)
	include('APImylib.php');
	include('Erreurs.php');

	/// Paramétrage de l'entête HTTP (pour la réponse au Client)
	header("Content-Type:application/json");

	$bearer_token = '';
	$bearer_token = get_bearer_token();

	$linkedPDO = connectionBD()

	/// Identification du type de méthode HTTP envoyée par le client
	$http_method = $_SERVER['REQUEST_METHOD'];	

	if(is_jwt_valid($bearer_token)){

		switch ($http_method) {

		    /// Cas de la méthode GET
		    case "GET":
		        try {
		            //! Traitement
		            if (isset($_GET['id'])) {
		                //! Exception
		                if (empty($_GET['id'])) {
		                    throw new Exception("Missing id", 400);
		                }
		                if ($_GET['id'] == "signalement") {
		                    $matchingData = getBySignalement();
		                } elseif ($_GET['id'] == "vote") {
		                    $matchingData = getByVote();
		                } elseif ($_GET['id'] == "last10") {
		                    $matchingData = getByLast10();
		                } else {
		                    $matchingData = getId($_GET['id']);
		                }
		            } else {
		                $matchingData = getALL();
		            }
		            $RETURN_CODE = 200;
		            $STATUS_MESSAGE = "modification réussi";
		        } catch (\Throwable $th) {
		            $RETURN_CODE = $th->getCode();
		            $STATUS_MESSAGE = $th->getMessage();
		        } finally {
		            //! Envoi de la réponse au Client
		            deliver_response($RETURN_CODE, $STATUS_MESSAGE, $matchingData);
		        }
		        break;


		    /// Cas de la méthode POST
		    case "POST":
		        try {
		            /// Récupération des données envoyées par le Client
		            $postedData = file_get_contents('php://input');
		            $postedData = json_decode($postedData, true);
		            /// Traitement
		            $matchingData = post($postedData['phrase']);
		            $RETURN_CODE = 200;
		            $STATUS_MESSAGE = "modification réussi";
		        } catch (\Throwable $th) {
		            $RETURN_CODE = $th->getCode();
		            $STATUS_MESSAGE = $th->getMessage();
		        } finally {
		            //! Envoi de la réponse au Client
		            deliver_response($RETURN_CODE, $STATUS_MESSAGE, $matchingData);
		        }
		        break;

		    /// Cas de la méthode PUT
		    case "PUT":
		        try {
		            /// Exception
		            if (empty($_GET['id'])) {
		                throw new Exception("Missing id", 400);
		            }
		            /// Récupération des données envoyées par le Client
		            $postedData = file_get_contents('php://input');
		            $postedData = json_decode($postedData, true);
		            /// Traitement
		            if (isset($_GET['id'])) {
		                $matchingData = put($_GET['id'], $postedData['phrase']);
		            }
		            $RETURN_CODE = 200;
		            $STATUS_MESSAGE = "modification réussi";
		        } catch (\Throwable $th) {
		            $RETURN_CODE = $th->getCode();
		            $STATUS_MESSAGE = $th->getMessage();
		        } finally {
		            /// Envoi de la réponse au Client
		            deliver_response($RETURN_CODE, $STATUS_MESSAGE, $matchingData);
		        }
		        break;

		    /// Cas de la méthode DELETE
		    case "DELETE":
		        try {
		            /// Exception
		            if (empty($_GET['id'])) {
		                throw new Exception("Missing id", 400);
		            }
		            /// Récupération de l'identifiant de la ressource envoyé par le Client
		            if (isset($_GET['id'])) {
		                /// Traitement
		                $matchingData = delete($_GET['id']);
		            }
		            $RETURN_CODE = 200;
		            $STATUS_MESSAGE = "modification réussi";
		        } catch (\Throwable $th) {
		            $RETURN_CODE = $th->getCode();
		            $STATUS_MESSAGE = $th->getMessage();
		        } finally {
		            //! Envoi de la réponse au Client
		            deliver_response($RETURN_CODE, $STATUS_MESSAGE, $matchingData);
		        }
		        break;

		    default:
		        $matchingData = null;
		        $RETURN_CODE = 404;
		        $STATUS_MESSAGE = "Méthode introuvable";
		        deliver_response($RETURN_CODE, $STATUS_MESSAGE, $matchingData);
		        break;
		}
		
		

	}
	
?>