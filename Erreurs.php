<?php

	/// Création des constante d'erreurs 
	define("SYNTAXE", -1);
	define("EXECUTION_REQUETE", -2);
	define("ERREUR_SQL", -3);

	define("CORRECT", -10);
	/// 8
	/// Création des constante d'erreurs avec un chiffre ex : -1
	/// define("blabla", code)

	//if (function_exists('get_erreur')){
		function get_erreur($code, $Message, $ValARetourner){
			if ($code == SYNTAXE){
				deliver_response(400, "Erreur de syntaxe", NULL);
			}elseif ($code == EXECUTION_REQUETE){
				deliver_response(403, "Echec d'exécution de la requête", NULL);
			}elseif ($code == ERREUR_SQL){
				deliver_response(500, "Erreur SQL", NULL);
			}elseif ($code == CORRECT){
				deliver_response(200, "Exécution réussie", NULL);
			}else{
				deliver_response(200, $Message, $ValARetourner);
			}
		}
	
	/// Créer un fonction qui prends en paramètre : la constante, mess perso si réussite (par défault)
	/// if "CONSTANTE"
	/// 	 deliver_reponse(code, "phrase", null)
	/// elseif "CONSTANTE"
	/// 	 deliver_reponse(code, "phrase", null)
	/// 
	/// Dans ma page APImylib je traite les erreurs et renvoie la constante qui correspond à l'erreur OU renvoie la réponse attendu
	/// Dans mon serveur je récupère le code envoyé par ma fonction dans la variable $code
	/// j'appelle ma fonction test_erreur avec le code récupéré en mettant les bons param en cas de réussite

	/// Penser aux commit après fonctions

?>