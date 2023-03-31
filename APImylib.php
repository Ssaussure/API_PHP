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