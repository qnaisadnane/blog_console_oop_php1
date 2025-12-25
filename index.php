<?php

 class Utilisateur{

    protected int $id_utilisateur;
    protected string $username;
    protected string $email;
    protected string $password;
    protected DateTime $createdAt;
    protected string $last_login;


    public function __constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login){
     $this->id_utilisateur = $id_utilisateur;
     $this->username = $username;
     $this->email = $email;
     $this->password = $password;
     $this->createdAt = $createdAt; 
     $this->last_login = $last_login; 
    }

    public function getIdUtilisateur(){
        return $this->id_utilisateur;
    }
    
    public function setIdUtilisateur($id_utilisateur){
        $this->id_utilisateur = $id_utilisateur;
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setIdUtilisateur($id_utilisateur){
        $this->id_utilisateur = $id_utilisateur;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

    public function getLastLogin(){
        return $this->last_login;
    }

    public function setLastLogin($last_login){
        $this->last_login = $last_login;
    }

    public function toString(){
        return echo $this->last_login \n;
        
    }

    // public  login(email: string, password: string){
    // }
}

   $utilisateur1 = new Utilisateur(...);



class Moderateur extends Utilisateur{

    public function __constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login){
     parent::__constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
    }

}

class Editeur extends Moderateur{

    private string $moderationLevel;

    public function __constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login){
     parent::__constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
     this->moderationLevel = $moderationLevel;
    }

    public function getModerationLevel(){
        return $this->moderationLevel;
    }

    public function setModerationLevel($moderationLevel){
        $this->moderationLevel = $moderationLevel;
    }

}

class Admin extends Moderateur{

    private string $isSuperAdmin;

    public function __constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login){
     parent::__constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
     this->isSuperAdmin = $isSuperAdmin;
    }
    
    public function getIsSuperAdmin(){
        return $this->moderationLevel;
    }

    public function setIsSuperAdmin($isSuperAdmin){
        $this->isSuperAdmin = $isSuperAdmin;
    }

}

class Admin extends Moderateur{

    public function __constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login){
     parent::__constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
    }

}

class Auteur extends Utilisateur{
    private string $bio;

    public function __constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login,$bio){
        parent::__constructor($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
        this->bio = $bio;
    }
    
    public function getBio(){
        return $this->bio;
    }

    public function setBio($bio){
        $this->bio = $bio;
    }
    
}

class commentaire{

    private int $id_commentaire;
    private string $libelle;
    private string $description;
    private DateTime $createdAt;

    public function __constructor($id_commentaire,$libelle,$description,$createdAt){
     $this->id_commentaire = $id_commentaire;
     $this->libelle = $libelle;
     $this->description = $description;
     $this->createdAt = $createdAt;  
    }

    public function getIdUtilisateur(){
        return $this->id_commentaire;
    }

    public function setIdUtilisateur($id_commentaire){
        $this->id_commentaire = $id_commentaire;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function setLibelle($libelle){
        $this->libelle = $libelle;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

    // public  login(email: string, password: string){
    // }
}

class categorie{
    private int $id_categorie;
    private string $name;
    private string $description;
    private ?categorie $parent;
    private DateTime $createdAt;

    public function __constructor($id_categorie,$name,$description,$parent,$createdAt){
     $this->id_categorie = $id_categorie;
     $this->name = $name;
     $this->description = $description;
     $this->parent = $parent;
     $this->createdAt = $createdAt;  
    }

    public function getIdCategorie(){
        return $this->id_categorie;
    }

    public function SetIdCategorie($id_categorie){
        $this->id_categorie = $id_categorie;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getParent(){
        return $this->parent;
    }

    public function setParent($parent){
        $this->parent = $parent;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }
}

class article{
    private int $id_article;
    private string $titre;
    private string $content;
    private string $status;
    private Utilisateur $auteur;
    private DateTime $createdAt;
    private DateTime $publishedAt;
    private DateTime $updatedAt;


    public function __constructor($id_article,$titre,$content,$status,$auteur,$createdAt,$publishedAt,$updatedAt){
     $this->id_article = $id_article;
     $this->titre = $titre;
     $this->content = $content;
     private ?categorie $parent;
     $this->createdAt = $createdAt;
     $this->publishedAt = $publishedAt;  
     $this->updatedAt = $updatedAt;  
    }

     public function getIdArticle(){
        return $this->id_article;
    }

    public function setIdArticle($id_article){
        $this->id_article = $id_article;
    }

     public function getTitre(){
        return $this->titre;
    }

    public function setTitre($titre){
        $this->titre = $titre;
    }

     public function getContent(){
        return $this->content;
    }

    public function setContent($content){
        $this->content = $content;
    }

     public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

     public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

     public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

     public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

     public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

     public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

}



?>