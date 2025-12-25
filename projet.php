<?php
 
  class Utilisateur{

    public string $username;
    public string $email;
    public string $role;


    public function __construct($username,$email,$role){
     $this->username = $username;
     $this->email = $email;
     $this->role = $role;
    }

     public function affiche(){
        return "Username : $this->username  Email : $this->email "; 
    }
     
     public function estAuteur(){
        return $this->role === 'auteur';
    }
  }

//    $utilisateur1 = new Utilisateur("adnane", "adnane@gmail.com","visiteur");
//    echo $utilisateur1->affiche();
   $userAuteur = new Utilisateur("lea", "lea@blog.com", "auteur");
   $userVisiteur = new Utilisateur("visiteur", "v@blog.com", "visiteur");

//    echo "Lea peut-elle créer un article ? " . ($userAuteur->estAuteur() ? "OUI" : "NON") . "\n";
//    echo "Le visiteur peut-il créer un article ? " . ($userVisiteur->estAuteur() ? "OUI" : "NON") . "\n";


   class Article{

    private string $title;
    private string $content;
    private string $status;

    public function __construct($title,$content){
     $this->title = $title;
     $this->content = $content;
     $this->status = "brouillon";
    }

    public function getTitle(){
    return $this->title;   
    }
    public function getcontent(){
    return $this->content;   
    }
    public function getstatus(){
    return $this->status;   
    }

    
    public function publier(){
     $this->status ='publié';
     return $this->getstatus();
    }

}
   $userAuteu = new Utilisateur("lesda", "lea@blog.com", "auteur");
   $article1 = new Article("lerferfeea", "sdfsdfsd");

   echo $article1->publier();



?>