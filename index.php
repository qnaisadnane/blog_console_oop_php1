<?php

 class Utilisateur{

    protected int $id_utilisateur;
    protected string $username;
    protected string $email;
    protected string $password;
    protected DateTime $createdAt;
    protected ?DateTime $last_login;


    public function __construct(int $id_utilisateur,string $username,string $email,string $password,DateTime $createdAt,?DateTime $last_login){
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

    public function getUsername(){
        return $this->username;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }
    
    public function getLastLogin(){
    return $this->last_login;
    }

    public function setCreatedAt($createdAt):void{
        $this->createdAt = $createdAt;
    }

    public function setLastLogin($last_login):void{
        $this->last_login = $last_login;
    }
}


class Moderateur extends Utilisateur{

    public function __construct($id_utilisateur,$username,$email,$password,$createdAt,$last_login){
     parent::__construct($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
    }

}

class Editeur extends Moderateur{

    private string $moderationLevel;

    public function __construct($id_utilisateur,$username,$email,$password,$createdAt,$last_login, string $moderationLevel){
     parent::__construct($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
          $this->moderationLevel = $moderationLevel;
    }

    public function getModerationLevel(){
        return $this->moderationLevel;
    }

}

class Admin extends Moderateur{

    private bool $isSuperAdmin;

    public function __construct($id_utilisateur,$username,$email,$password,$createdAt,$last_login,bool $isSuperAdmin = false){
     parent::__construct($id_utilisateur,$username,$email,$password,$createdAt,$last_login);
          $this->isSuperAdmin = $isSuperAdmin;
    }
    
    public function getIsSuperAdmin(){
        return $this->isSuperAdmin;
    }
}

class Auteur extends Utilisateur{
    private string $bio;

    public function __construct($id_utilisateur, $username, $email, $password, $createdAt, $last_login, string $bio = '') {
        parent::__construct($id_utilisateur, $username, $email, $password, $createdAt, $last_login);
        $this->bio = $bio;
    }

    public function getBio(){
        return $this->bio;
    }
 
}


class Categorie{
    private int $id_categorie;
    private string $name;
    private string $description;
    private ?categorie $parent;
    private DateTime $createdAt;

    public function __construct($id_categorie,$name,$description,$parent,$createdAt){
     $this->id_categorie = $id_categorie;
     $this->name = $name;
     $this->description = $description;
     $this->parent = $parent;
     $this->createdAt = $createdAt;  
    }

    public function getIdCategorie(){
        return $this->id_categorie;
    }

    public function getName(){
        return $this->name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getParent(){
        return $this->parent;
    }
}

class Article{
    private int $id_article;
    private string $titre;
    private string $content;
    private string $status;
    private Utilisateur $auteur;
    private array $categories = [];
    private DateTime $createdAt;
    private DateTime $publishedAt;
    private DateTime $updatedAt;

    public function __construct($id_article,$titre,$content,$status,$auteur,$createdAt,$publishedAt,$updatedAt){
     $this->id_article = $id_article;
     $this->titre = $titre;
     $this->content = $content;
     $this->status = $status;
     $this->auteur = $auteur;
     $this->createdAt = $createdAt;
     $this->publishedAt = $publishedAt;  
     $this->updatedAt = $updatedAt;  
    }

     public function getIdArticle(){
        return $this->id_article;
    }

     public function getTitre(){
        return $this->titre;
    }

     public function getContent(){
        return $this->content;
    }

    public function getStatus(){
        return $this->status;
    }
    
    public function getAuteur(){
        return $this->auteur;
    }

    public function getCategories() {
        return $this->categories;
    }
    
    public function setCategories(array $categories) {
        $this->categories = $categories;
    }

    public function getPublishedAt(){
       return $this->publishedAt;
   }
}


class commentaire{

    private int $id_commentaire;
    private string $libelle;
    private string $description;
    private DateTime $createdAt;

    public function __construct($id_commentaire,$libelle,$description,$createdAt){
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
}

?>