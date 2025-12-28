<?php

include 'index.php';
include 'author.php';
include 'editor.php';
include 'admin.php';

class Collection{
    private array $users = [];
    private array $articles = [];
    private array $categories = [];
    private $current_user = null;

    public function __construct(){
    $this->initData();
    }

    private function initData(): void {
        
        $admin = new Admin(1, 'admin', 'admin@blogcms.com', 'admin123', new DateTime(), null, true);
        $author = new Auteur(2, 'lea', 'lea@blogcms.com', 'lea123', new DateTime(), null, 'Bio de Lea');
        $editeur = new Editeur(3, 'thomas', 'thomas@blogcms.com', 'thomas123', new DateTime(), null, 'advanced');
        
        $this->users = [$admin, $author, $editeur];

        $tech = new Categorie(1, 'Technologie', 'Articles sur la technologie', null, new DateTime());
        $prog = new Categorie(2, 'Programmation', 'Articles sur la programmation', $tech, new DateTime());
        $php = new Categorie(3, 'PHP', 'Articles sur PHP', $prog, new DateTime());

        $this->categories = [$tech, $prog, $php];

        $article1 = new Article(1, 'Introduction à PHP 8', 'PHP 8 apporte de nombreuses nouveautes passionnantes comme les attributs, le JIT compiler, et bien plus encore', 'published', $author, new DateTime('2024-01-10'), new DateTime('2024-01-15'), new DateTime('2024-01-15'));
        $article1->setCategories([$php]);

        $article2 = new Article(2, 'Les tendances du developpement web en 2025', 'Le developpement web evolue rapidement. Decouvrez les tendances qui façonnent l\'avenir de notre industrie', 'published', $author, new DateTime('2024-01-12'), new DateTime('2024-01-17'), new DateTime('2024-01-17'));
        $article2->setCategories([$tech, $prog]);
        $this->articles = [$article1, $article2];
    }

    public function login(string $username , string $password):bool {
       foreach($this->users as $user){
        if($user->getUsername() === $username && $user->getPassword() === $password ){
           $this->current_user = $user;
            return true;
        }
       }
       return false;
    }

public function logout():void{
  $this->current_user = null;
}

public function getCurrentUser() {
    return $this->current_user;
}

public function isLoggedIn(): bool {
return $this->current_user !== null;
}

private function clearScreen(): void {
        echo "\033[2J\033[;H";
    }

    public function displayMenu(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              MENU VISITEUR                        ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";  
        echo "1. Afficher les articles\n";
        echo "2. Login\n";
        echo "3. Faire un commentaire\n";
        echo "4. Quitter\n\n";
    }
    
    public function run(): void {
        while (true) {
            if (!$this->isLoggedIn()) {
            $this->displayMenu();
            echo "Votre choix : ";
            $choice = trim(fgets(STDIN));
            
            switch ($choice) {
                case '1':
                    $this->displayArticles();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '2':
                    $this->handleLogin();
                    break;

                case '3':
                        $this->addComment();
                        echo "\nAppuyez sur Entree pour continuer...";
                        fgets(STDIN);
                        break;
                    
                case '4':
                    echo "\nAu revoir !\n";
                    exit(0);
                    
                default:
                    echo "\nChoix invalide. Veuillez reessayer.\n";
                    echo "Appuyez sur Entree pour continuer...";
                    fgets(STDIN);
            }
        }else {
                $this->handleUserMenu();
            }
        }   
    }
        private function handleLogin(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              CONNEXION                            ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";
        
        echo "Nom d'utilisateur : ";
        $username = trim(fgets(STDIN));
        echo "Mot de passe : ";
        $password = trim(fgets(STDIN));

        if ($this->login($username, $password)) {
            $this->clearScreen();
            echo "\n✅ Connexion reussie ! Bienvenue " . $this->getCurrentUser()->getUsername() . "\n";
            echo "Rôle: " . $this->getCurrentUser()::class . "\n";
            
        } else {
            echo "\n❌ Identifiants incorrects !\n";
            echo "Appuyez sur Entree pour continuer...";
            fgets(STDIN);
        }
    }
    private function handleUserMenu(): void {
        $user = $this->getCurrentUser();

        
        if ($user instanceof Auteur) {
            $authorMenu = new AuthorMenu($user, $this->articles, $this->categories);
            $continue = $authorMenu->run();
            
            $this->articles = $authorMenu->getArticles();
            
            if (!$continue) {
                $this->logout();
            }
        } 
        
        elseif ($user instanceof Editeur) {
            $editorMenu = new EditorMenu($user, $this->articles, $this->categories);
            $continue = $editorMenu->run();
            
            
            $this->articles = $editorMenu->getArticles();
            $this->categories = $editorMenu->getCategories();
            
            if (!$continue) {
                $this->logout();
            }
        }
        
        elseif ($user instanceof Admin) {
            $adminMenu = new AdminMenu($user, $this->articles, $this->categories, $this->users);
            $continue = $adminMenu->run();
            
            
            $this->articles = $adminMenu->getArticles();
            $this->categories = $adminMenu->getCategories();
            $this->users = $adminMenu->getUsers();
            
            if (!$continue) {
                $this->logout();
            }
        }
       
    }

    public function displayArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              ARTICLES PUBLIeS                     ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";
        

        $publishedArticles = array_filter($this->articles, fn($a) => $a->getStatus() === 'published');
        
        if (empty($publishedArticles)) {
            echo "Aucun article publie pour le moment.\n";
            return;
        }

        foreach ($this->articles as $article) {
            
                echo " Titre: " . $article->getTitre() . "\n";
                echo " Auteur: " . $article->getAuteur()->getUsername() . "\n";
                echo " Date: " . $article->getPublishedAt()->format('d/m/Y') . "\n";
                
                
                $categories = $article->getCategories();
                if (!empty($categories)) {
                    $categoryNames = array_map(fn($cat) => $cat->getName(), $categories);
                    echo "  Categories: " . implode(', ', $categoryNames) . "\n";
                }
                
                echo " Contenu: " . $article->getContent() . "\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
            }
        }

        private function addComment(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          FAIRE UN COMMENTAIRE                     ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        
        $publishedArticles = array_filter($this->articles, fn($a) => $a->getStatus() === 'published');

        if (empty($publishedArticles)) {
            echo "❌ Aucun article publie disponible pour commenter.\n";
            return;
        }

        echo "Articles disponibles:\n\n";
        foreach ($publishedArticles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . 
                 " - par " . $article->getAuteur()->getUsername() . "\n";
        }

        echo "\n Entrez l'ID de l'article à commenter : ";
        $articleId = (int)trim(fgets(STDIN));

        
        $selectedArticle = null;
        foreach ($publishedArticles as $article) {
            if ($article->getIdArticle() === $articleId) {
                $selectedArticle = $article;
                break;
            }
        }

        if (!$selectedArticle) {
            echo "\n❌ Article non trouve !\n";
            return;
        }

        echo " Votre commentaire : ";
        $description = trim(fgets(STDIN));
        
        if (empty($description)) {
            echo "\n❌ Le commentaire ne peut pas être vide !\n";
            return;
        }

        echo "\n✅ Commentaire ajoute avec succès !\n";
        echo " Article: " . $selectedArticle->getTitre() . "\n";
        echo " Commentaire: " . $description . "\n";
    }
}
    $collection = new Collection();
    $collection->run();
?>

