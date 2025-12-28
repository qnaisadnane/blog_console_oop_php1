<?php

include 'index.php';
include 'author.php';

class Collection{
    private array $users = [];
    private array $articles = [];
    private array $categories = [];
    private $current_user = null;

    public function __construct(){
    $this->initData();
}

    private function initData(): void {
        // Création des utilisateurs
        $admin = new Admin(1, 'admin', 'admin@blogcms.com', 'admin123', new DateTime(), null, true);
        $author = new Auteur(2, 'lea', 'lea@blogcms.com', 'lea123', new DateTime(), null, 'Bio de Léa');
        $editeur = new Editeur(3, 'thomas', 'thomas@blogcms.com', 'thomas123', new DateTime(), null, 'advanced');
        
        $this->users = [$admin, $author, $editeur];

        // Création des catégories
        $tech = new Categorie(1, 'Technologie', 'Articles sur la technologie', null, new DateTime());
        $prog = new Categorie(2, 'Programmation', 'Articles sur la programmation', $tech, new DateTime());
        $php = new Categorie(3, 'PHP', 'Articles sur PHP', $prog, new DateTime());

        $this->categories = [$tech, $prog, $php];

        // Création des articles
        $article1 = new Article(1, 'Introduction à PHP 8', 'PHP 8 apporte de nombreuses nouveautés passionnantes comme les attributs, le JIT compiler, et bien plus encore...', 'published', $author, new DateTime('2024-01-10'), new DateTime('2024-01-15'), new DateTime('2024-01-15'));
        $article1->setCategories([$php]);

        $article2 = new Article(2, 'Les tendances du développement web en 2025', 'Le développement web évolue rapidement. Découvrez les tendances qui façonnent l\'avenir de notre industrie...', 'published', $author, new DateTime('2024-01-12'), new DateTime('2024-01-17'), new DateTime('2024-01-17'));
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
        echo "2. Se connecter\n";
        echo "3. Quitter\n\n";
        
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
                    echo "\nAppuyez sur Entrée pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '2':
                        $this->handleLogin();
                        break;
                    
                case '3':
                    echo "\nAu revoir !\n";
                    exit(0);
                    
                default:
                    echo "\nChoix invalide. Veuillez réessayer.\n";
                    echo "Appuyez sur Entrée pour continuer...";
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
        echo "Comptes de test:\n";
        echo "  • admin / admin123 (Administrateur)\n";
        echo "  • lea / lea123 (Auteur)\n";
        echo "  • thomas / thomas123 (Éditeur)\n\n";
        
        echo "Nom d'utilisateur : ";
        $username = trim(fgets(STDIN));
        echo "Mot de passe : ";
        $password = trim(fgets(STDIN));

        if ($this->login($username, $password)) {
            $this->clearScreen();
            echo "\n✅ Connexion réussie ! Bienvenue " . $this->getCurrentUser()->getUsername() . "\n";
            echo "Rôle: " . get_class($this->getCurrentUser()) . "\n";
            sleep(2);
        } else {
            echo "\n❌ Identifiants incorrects !\n";
            echo "Appuyez sur Entrée pour continuer...";
            fgets(STDIN);
        }
    }
    private function handleUserMenu(): void {
        $user = $this->getCurrentUser();

        // Si l'utilisateur est un Auteur
        if ($user instanceof Auteur) {
            $authorMenu = new AuthorMenu($user, $this->articles, $this->categories);
            $continue = $authorMenu->run();
            
            // Récupérer les articles mis à jour
            $this->articles = $authorMenu->getArticles();
            
            if (!$continue) {
                $this->logout();
            }
        } 
        // Si l'utilisateur est un Éditeur
        elseif ($user instanceof Editeur) {
            $this->clearScreen();
            echo "╔═══════════════════════════════════════════════════╗\n";
            echo "║         MENU ÉDITEUR                              ║\n";
            echo "╚═══════════════════════════════════════════════════╝\n\n";
            echo "⚠️  Menu éditeur en cours de développement...\n\n";
            echo "Appuyez sur Entrée pour se déconnecter...";
            fgets(STDIN);
            $this->logout();
        }
        // Si l'utilisateur est un Admin
        elseif ($user instanceof Admin) {
            $this->clearScreen();
            echo "╔═══════════════════════════════════════════════════╗\n";
            echo "║         MENU ADMINISTRATEUR                       ║\n";
            echo "╚═══════════════════════════════════════════════════╝\n\n";
            echo "⚠️  Menu administrateur en cours de développement...\n\n";
            echo "Appuyez sur Entrée pour se déconnecter...";
            fgets(STDIN);
            $this->logout();
        }
        else {
            echo "❌ Type d'utilisateur non reconnu.\n";
            $this->logout();
        }
    }

    public function displayArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              ARTICLES PUBLIÉS                     ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";
        
        if (empty($this->articles)) {
            echo "Aucun article disponible.\n";
            return;
        }

        $publishedArticles = array_filter($this->articles, fn($a) => $a->getStatus() === 'published');
        
        if (empty($publishedArticles)) {
            echo "Aucun article publié pour le moment.\n";
            return;
        }

        foreach ($this->articles as $article) {
            if ($article->getStatus() === 'published') {
                echo "📄 Titre: " . $article->getTitre() . "\n";
                echo "👤 Auteur: " . $article->getAuteur()->getUsername() . "\n";
                echo "📅 Date: " . $article->getPublishedAt()->format('d/m/Y') . "\n";
                
                
                $categories = $article->getCategories();
                if (!empty($categories)) {
                    $categoryNames = array_map(fn($cat) => $cat->getName(), $categories);
                    echo "🏷️  Catégories: " . implode(', ', $categoryNames) . "\n";
                }
                
                echo "📝 Contenu: " . substr($article->getContent(), 0, 100) . "...\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
            }
        }
    }
  
}
    $collection = new Collection();
    $collection->run();
?>

