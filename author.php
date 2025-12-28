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
        echo " Connecte en tant que: " . $this->author->getUsername() . "\n\n";
        echo "1. Afficher les articles\n";
        echo "2. Ajouter un article\n";
        echo "3. Modifier un article\n";
        echo "4. Supprimer un article\n";
        echo "5. Faire un commentaire\n";
        echo "0. Logout\n\n";
    }
    
    public function run(): bool {
        while (true) {
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
                    $this->addArticles();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '3':
                    $this->modifyArticles();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '4':
                    $this->deleteArticles();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '5':
                    $this->addComment();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;
                
                case '0':
                    $this->clearScreen();
                    echo "\n✅ Deconnexion reussie !\n";
                    return false;
                default:
                    echo "\nChoix invalide. Veuillez reessayer.\n";
                    echo "Appuyez sur Entree pour continuer...";
                    fgets(STDIN);
            }
        }
    }

    public function displayArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              ARTICLES PUBLIeS                     ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";
        
        if (empty($this->articles)) {
            echo "Aucun article disponible.\n";
            return;
        }

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

       
        echo "\n Commentaire ajoute avec succès !\n";
        echo " Article: " . $selectedArticle->getTitre() . "\n";
        echo " Commentaire: " . $description . "\n";
    }    

    private function addArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║           AJOUTER UN ARTICLE                      ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        
        echo " Titre de l'article : ";
        $titre = trim(fgets(STDIN));
        
        if (empty($titre)) {
            echo "\n❌ Le titre ne peut pas être vide !\n";
            return;
        }

        
        echo " Contenu de l'article : ";
        $content = trim(fgets(STDIN));
        
        if (empty($content)) {
            echo "\n❌ Le contenu ne peut pas être vide !\n";
            return;
        }

        echo "\n Statut de l'article :\n";
        echo "1. draft \n";
        echo "2. published\n";
        echo "Choisissez (1 ou 2) : ";
        $statusChoice = trim(fgets(STDIN));
        
        switch ($statusChoice) {
            case '1':
                $status = 'draft';
                break;
            case '2':
                $status = 'published';
                break;
            default:
                $status = 'draft'; 
                break;
        }

        
        echo "\n Categories disponibles:\n";
        foreach ($this->categories as $cat) {
            echo "  [" . $cat->getIdCategorie() . "] " . $cat->getName() . " - " . $cat->getDescription() . "\n";
        }

        echo "\nEntrez les IDs des categories (ex: 1,2) : ";
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
            echo "\n❌ Vous devez selectionner une categorie!\n";
            return;
        }

        
        $newArticle = new Article(
            self::$nextArticleId++,
            $titre,
            $content,
            $status,
            $this->author,
            new DateTime(),
            new DateTime(),
            new DateTime()
        );
        $newArticle->setCategories($selectedCategories);

        $this->articles[] = $newArticle;
        echo "\n✅ Article cree avec succès !\n";
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

        
        echo "Vos articles:\n\n";
        foreach ($myArticles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . " (" . $article->getStatus() . ")\n";
        }

        echo "\ Entrez l'ID de l'article à modifier : ";
        $articleId = (int)trim(fgets(STDIN));

        $articleToModify = null;
        foreach ($myArticles as $article) {
            if ($article->getIdArticle() === $articleId) {
                $articleToModify = $article;
                break;
            }
        }

        if (!$articleToModify) {
            echo "\n❌ Article non trouve ou vous n'êtes pas l'auteur !\n";
            return;
        }

        echo "\n Titre actuel: " . $articleToModify->getTitre() . "\n";
        echo "Nouveau titre (laisser vide pour conserver) : ";
        $newTitre = trim(fgets(STDIN));

        echo "\n Contenu actuel: " . substr($articleToModify->getContent(), 0, 50) . "...\n";
        echo "Nouveau contenu (laisser vide pour conserver) : ";
        $newContent = trim(fgets(STDIN));

        echo "\n Statut actuel: " . $articleToModify->getStatus() . "\n";
        echo "Nouveau statut :\n";
        echo "1. draft (brouillon)\n";
        echo "2. published (publie)\n";
        echo "3. Conserver le statut actuel\n";
        echo "Choisissez (1, 2 ou 3) : ";
        $statusChoice = trim(fgets(STDIN));
        
        $newStatus = null;
        switch ($statusChoice) {
            case '1':
                $newStatus = 'draft';
                break;
            case '2':
                $newStatus = 'published';
                break;
            case '3':
                // Ne rien changer
                break;
            default:
                echo "\n  Choix invalide, le statut reste inchange.\n";
                break;
        }

        echo "\n  Categories actuelles: ";
        $currentCategories = $articleToModify->getCategories();
        if (!empty($currentCategories)) {
            $categoryNames = array_map(fn($cat) => $cat->getName(), $currentCategories);
            echo implode(', ', $categoryNames) . "\n";
        } else {
            echo "Aucune categorie\n";
        }
        
        echo "\nCategories disponibles:\n";
        foreach ($this->categories as $cat) {
            echo "  [" . $cat->getIdCategorie() . "] " . $cat->getName() . " - " . $cat->getDescription() . "\n";
        }

        echo "\nEntrez les nouveaux IDs des categories separes par des virgules\n";
        echo "(laisser vide pour conserver les categories actuelles) : ";
        $catInput = trim(fgets(STDIN));
        
        $newCategories = null;
        if (!empty($catInput)) {
            $categoryIds = array_map('trim', explode(',', $catInput));
            $newCategories = [];

            foreach ($categoryIds as $id) {
                foreach ($this->categories as $cat) {
                    if ($cat->getIdCategorie() == $id) {
                        $newCategories[] = $cat;
                        break;
                    }
                }
            }

            if (empty($newCategories)) {
                echo "\n Aucune categorie valide selectionnee, les categories restent inchangees.\n";
                $newCategories = null;
            }
        }

        $modified = false;
        
        if (!empty($newTitre)) {
            $articleToModify->setTitre($newTitre);
            $modified = true;
        }
        if (!empty($newContent)) {
            $articleToModify->setContent($newContent);
            $modified = true;
        }

        if ($newStatus !== null) {
            $articleToModify->setStatus($newStatus);
            $modified = true;
        }

        if ($newCategories !== null) {
            $articleToModify->setCategories($newCategories);
            $modified = true;
        }

        if ($modified) {
            $articleToModify->setUpdatedAt(new DateTime());
            echo "\n✅ Article modifie avec succès !\n";
        } else {
            echo "\n  Aucune modification apportee.\n";
        }
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

        echo "Vos articles:\n\n";
        foreach ($myArticles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . " (" . $article->getStatus() . ")\n";
        }

        echo "\n Entrez l'ID de l'article à supprimer : ";
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
            echo "\n❌ Article non trouve ou vous n'êtes pas l'auteur !\n";
            return;
        }

        echo "\n  Êtes-vous sûr de vouloir supprimer cet article ? (oui/non) : ";
        $confirmation = strtolower(trim(fgets(STDIN)));

        if ($confirmation === 'oui') {
            unset($this->articles[$articleIndex]);
            $this->articles = array_values($this->articles);
            echo "\n✅ Article supprime avec succès !\n";
        } else {
            echo "\n❌ Suppression annulee.\n";
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


