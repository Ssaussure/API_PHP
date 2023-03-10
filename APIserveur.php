    <?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
include('APImylib.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method) {

        //? Cas de la méthode GET
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


        //! Cas de la méthode POST
    case "POST":
        try {
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            $postedData = json_decode($postedData, true);
            /// Traitement
            $matchingData = post($postedData['publication'], $postedData['auteur']);
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

        //! Cas de la méthode PUT
    case "PUT":
        try {
            //! Exception
            if (empty($_GET['id'])) {
                throw new Exception("Missing id", 400);
            }
            /// Récupération des données envoyées par le Client
            $postedData = file_get_contents('php://input');
            $postedData = json_decode($postedData, true);
            /// Traitement
            if (isset($_GET['id'])) {
                $matchingData = put($_GET['id'], $postedData['publication']);
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

        //! Cas de la méthode DELETE
    case "DELETE":
        try {
            //! Exception
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
