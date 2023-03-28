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
        return null;
    }
    // retourne la connection
    return $linkpdo;
}

function getId($linkpdo, $id)
{
    if(empty($id) || !is_numeric($id)) {
        return null;
    }
    // preparation de la Requête sql
    try{
        $req = $linkpdo->prepare('select * from articles where id = :id');
    } catch(PDOException $e) {
        return null;
    }
    
    if($req->bindParam(':id', $id, PDO::PARAM_INT)) {
        if($req->execute()) {
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    return null;
}



function getAll($linkpdo)
{
    try{
        $req = $linkpdo->prepare('select * from articles');
    }catch(PDOException $e){
        return null;
    }
    
    if ($req == false) {
        die('Erreur ! GetAll');
    }
    if($req->execute()) {
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}

function post($publication, $auteur, $linkpdo) {
    try{
        $req = $linkpdo->prepare('insert into articles (publication,auteur,like_pub,dislike,date, signalement) value(:publication,:auteur, 0,0,NOW(),0)');
    }catch(PDOException $e) {
        return null;
    }
    if ($req -> bindParam(':publication', $publication, PDO::PARAM_STR, ':auteur', $auteur, PDO::PARAM_STR)) {
        if($req->execute()) {
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }
    $id = $linkpdo->lastInsertId();
    return getId($id);
}
    }


function put($id, $publication, $linkpdo)
{
    if(empty($id) || !is_numeric($id)) {
        return null;
    }

    try {
        // preparation de la Requête sql
         $req = $linkpdo->prepare('update articles set publication = :publication where id = :id');
    } catch(PDOException $e) {
        return null;
    }
    if ($req -> bindParam(':publication', $publication, PDO::PARAM_STR, ':id', $id, PDO::PARAM_INT)) {
        if($req->execute()) {
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }

    }
}

function delete($id, $linkpdo){
    if(empty($id) || !is_numeric($id)) {
        return null;
    }
    try {
        $req = $linkpdo->prepare('delete from articles where id = :id');
    } catch(PDOException $e) {
        return null;
    }
    if($req -> bindParam(':id', $id, PDO::PARAM_INT)) {
        if($req->execute()){
            return $req->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
