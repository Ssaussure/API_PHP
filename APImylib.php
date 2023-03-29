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
        die('Erreur ! Problème de connexion à la base de données : ' . $e->getMessage());
    }
    // retourne la connection
    return $linkpdo;
}

function getId($id)
{
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

function getBySignalement()
{
    $linkpdo = connexionBd();
    // preparation de la Requête sql
    $req = $linkpdo->prepare('select * from articles where signalement > 0');
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

function getByVote()
{
    $linkpdo = connexionBd();
    // preparation de la Requête sql
    $req = $linkpdo->prepare('select * from articles where like_pub > 3 order by like_pub desc');
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

function getByLast10()
{
    $linkpdo = connexionBd();
    // preparation de la Requête sql
    $req = $linkpdo->prepare('select * from articles order by id desc limit 10');
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
    $req = $linkpdo->prepare('insert into articles (publication,auteur,like_pub,dislike,date, signalement) value(:publication,:auteur, 0,0,NOW(),0)');
    if ($req == false) {
        die('Erreur ! Post');
    }
    // execution de la Requête sql
    $req->execute(array(':publication' => $publication, ':auteur' => $auteur));
    if ($req == false) {
        die('Erreur ! Post');
    }
    // recuperation du dernier id
    $lastId = $linkpdo->lastInsertId();
    return getId($lastId);
}

function put($id, $publication)
{
    $linkpdo = connexionBd();
    // preparation de la Requête sql
    $req = $linkpdo->prepare('update articles set publication = :publication where id = :id');
    if ($req == false) {
        die('Erreur ! Put');
    }
    // execution de la Requête sql
    $req->execute(array('id' => $id, ':publication' => $publication));
    if ($req == false) {
        die('Erreur ! Put');
    }
    // recuperation du dernier id
    return getId($id);
}

function delete($id)
{
    $linkpdo = connexionBd();
    // preparation de la Requête sql
    $req = $linkpdo->prepare('delete from articles where id = :id');
    if ($req == false) {
        die('Erreur ! Delete');
    }
    // execution de la Requête sql
    $req->execute(array('id' => $id));
    if ($req == false) {
        die('Erreur ! Delete');
    }
}
