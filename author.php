<?php

require_once 'index.php';


class AuthorMenu{
    private Auteur $author;
    private array $articles;
    private array $categories;
    private static int $nextArticleId = 3;

    public function __construct(Auteur $author,array $articles,array $categories){
    $this->author = $author;    
    $this->articles = $articles;
    $this->categories = $categories;
}

private function clearScreen(): void {
        echo "\033[2J\033[;H";
    }


    public function displayMenu(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              MENU Auteur                          ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";
        echo "👤 Connecté en tant que: " . $this->author->getUsername() . "\n\n";
        echo "1. Afficher mes articles\n";
        echo "2. Ajouter un article\n";
        echo "3. Modifier un article\n";
        echo "4. Supprimer un article\n";
        echo "5. Se déconnecter\n\n";
    }
    
    public function run(): bool {
        while (true) {
            $this->displayMenu();
            echo "Votre choix : ";
            $choice = trim(fgets(STDIN));
            
            switch ($choice) {
                case '1':
                    $this->displayMyArticles();
                    echo "\nAppuyez sur Entrée pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '2':
                    $this->addArticles();
                    echo "\nAppuyez sur Entrée pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '3':
                    $this->modifyArticles();
                    echo "\nAppuyez sur Entrée pour continuer...";
                    fgets(STDIN);
                    break;

                case '4':
                    $this->deleteArticles();
                    echo "\nAppuyez sur Entrée pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '5':
                    $this->clearArticles();
                    echo "\n✅ Déconnexion réussie !\n";
                    return false;
                    break;   
                    
                default:
                    echo "\nChoix invalide. Veuillez réessayer.\n";
                    echo "Appuyez sur Entrée pour continuer...";
                    fgets(STDIN);
            }
        }
    }

    public function displayMyArticles(): void {
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              MES ARTICLES                         ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        $myArticles = $this->getMyArticles();
        
        if (empty($this->articles)) {
            echo "Aucun article disponible.\n";
            return;
        }
        
        foreach ($myArticles as $article) {
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "🆔 ID: " . $article->getIdArticle() . "\n";
            echo "📄 Titre: " . $article->getTitre() . "\n";
            echo "📊 Statut: " . $article->getStatus() . "\n";
            echo "📅 Date création: " . $article->getPublishedAt()->format('d/m/Y H:i') . "\n";
            
            $categories = $article->getCategories();
            if (!empty($categories)) {
                $categoryNames = array_map(fn($cat) => $cat->getName(), $categories);
                echo "🏷️  Catégories: " . implode(', ', $categoryNames) . "\n";
            }
            
            echo "📝 Contenu: " . substr($article->getContent(), 0, 80) . "...\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        }

        echo "Total: " . count($myArticles) . " article(s)\n";
    }

    private function addArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║           AJOUTER UN ARTICLE                      ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        // Titre
        echo "📄 Titre de l'article : ";
        $titre = trim(fgets(STDIN));
        
        if (empty($titre)) {
            echo "\n❌ Le titre ne peut pas être vide !\n";
            return;
        }

        // Contenu
        echo "📝 Contenu de l'article : ";
        $content = trim(fgets(STDIN));
        
        if (empty($content)) {
            echo "\n❌ Le contenu ne peut pas être vide !\n";
            return;
        }

        // Afficher les catégories disponibles
        echo "\n🏷️  Catégories disponibles:\n";
        foreach ($this->categories as $cat) {
            echo "  [" . $cat->getIdCategorie() . "] " . $cat->getName() . " - " . $cat->getDescription() . "\n";
        }

        echo "\nEntrez les IDs des catégories séparés par des virgules (ex: 1,2) : ";
        $catInput = trim(fgets(STDIN));
        
        $categoryIds = array_map('trim', explode(',', $catInput));
        $selectedCategories = [];

        foreach ($categoryIds as $id) {
            foreach ($this->categories as $cat) {
                if ($cat->getIdCategorie() == $id) {
                    $selectedCategories[] = $cat;
                    break;
                }
            }
        }

        if (empty($selectedCategories)) {
            echo "\n❌ Vous devez sélectionner au moins une catégorie valide !\n";
            return;
        }

        // Créer l'article
        $newArticle = new Article(
            self::$nextArticleId++,
            $titre,
            $content,
            'draft', // Par défaut en brouillon
            $this->author,
            new DateTime(),
            new DateTime(),
            new DateTime()
        );
        $newArticle->setCategories($selectedCategories);

        $this->articles[] = $newArticle;

        echo "\n✅ Article créé avec succès ! (Statut: brouillon)\n";
    }

    private function modifyArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║           MODIFIER UN ARTICLE                     ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        $myArticles = $this->getMyArticles();

        if (empty($myArticles)) {
            echo "❌ Vous n'avez pas d'articles à modifier.\n";
            return;
        }

        // Afficher les articles de l'auteur
        echo "Vos articles:\n\n";
        foreach ($myArticles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . " (" . $article->getStatus() . ")\n";
        }

        echo "\n🆔 Entrez l'ID de l'article à modifier : ";
        $articleId = (int)trim(fgets(STDIN));

        $articleToModify = null;
        foreach ($myArticles as $article) {
            if ($article->getIdArticle() === $articleId) {
                $articleToModify = $article;
                break;
            }
        }

        if (!$articleToModify) {
            echo "\n❌ Article non trouvé ou vous n'êtes pas l'auteur !\n";
            return;
        }

        echo "\n📄 Titre actuel: " . $articleToModify->getTitre() . "\n";
        echo "Nouveau titre (laisser vide pour conserver) : ";
        $newTitre = trim(fgets(STDIN));

        echo "\n📝 Contenu actuel: " . substr($articleToModify->getContent(), 0, 50) . "...\n";
        echo "Nouveau contenu (laisser vide pour conserver) : ";
        $newContent = trim(fgets(STDIN));

        // Appliquer les modifications
        if (!empty($newTitre)) {
            $articleToModify->setTitre($newTitre);
        }

        if (!empty($newContent)) {
            $articleToModify->setContent($newContent);
        }

        $articleToModify->setUpdatedAt(new DateTime());

        echo "\n✅ Article modifié avec succès !\n";
    }

    private function deleteArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║           SUPPRIMER UN ARTICLE                    ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        $myArticles = $this->getMyArticles();

        if (empty($myArticles)) {
            echo "❌ Vous n'avez pas d'articles à supprimer.\n";
            return;
        }

        // Afficher les articles de l'auteur
        echo "Vos articles:\n\n";
        foreach ($myArticles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . " (" . $article->getStatus() . ")\n";
        }

        echo "\n🆔 Entrez l'ID de l'article à supprimer : ";
        $articleId = (int)trim(fgets(STDIN));

        $articleIndex = null;
        foreach ($this->articles as $index => $article) {
            if ($article->getIdArticle() === $articleId && 
                $article->getAuteur()->getIdUtilisateur() === $this->author->getIdUtilisateur()) {
                $articleIndex = $index;
                break;
            }
        }

        if ($articleIndex === null) {
            echo "\n❌ Article non trouvé ou vous n'êtes pas l'auteur !\n";
            return;
        }

        echo "\n⚠️  Êtes-vous sûr de vouloir supprimer cet article ? (oui/non) : ";
        $confirmation = strtolower(trim(fgets(STDIN)));

        if ($confirmation === 'oui') {
            unset($this->articles[$articleIndex]);
            $this->articles = array_values($this->articles); // Réindexer le tableau
            echo "\n✅ Article supprimé avec succès !\n";
        } else {
            echo "\n❌ Suppression annulée.\n";
        }
    }
     
    private function getMyArticles(): array {
        $myArticles = [];
        foreach ($this->articles as $article) {
            if ($article->getAuteur()->getIdUtilisateur() === $this->author->getIdUtilisateur()) {
                $myArticles[] = $article;
            }
        }
        return $myArticles;
    }

    public function getArticles(): array {
        return $this->articles;
    }
}
     
?>


