<?php

require_once 'index.php';

class EditorMenu {
    protected Moderateur $editor;
    private array $articles;
    private array $categories;
    private static int $nextCategoryId = 4; 

    public function __construct(Moderateur $editor, array $articles, array $categories) {
        $this->editor = $editor;
        $this->articles = $articles;
        $this->categories = $categories;
    }

    private function clearScreen(): void {
        echo "\033[2J\033[;H";
    }

    public function displayMenu(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              MENU EDITEUR                         ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";
        echo " Connecte en tant que: " . $this->editor->getUsername() . "\n";
        if ($this->editor instanceof Editeur) {
            echo "  Niveau: " . $this->editor->getModerationLevel() . "\n";
        }
        echo "1. Afficher les articles\n";
        echo "2. Gestion des categories\n";
        echo "3. Gestion des articles\n";
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
                    echo "Appuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;
                    
                case '2':
                    $this->manageCategoriesMenu();
                    break;

                case '3':
                    $this->manageArticlesMenu();
                    break;

                case '0':
                    $this->clearScreen();
                    echo "\n✅ Deconnexion reussie !\n";
                    return false; // Retourne false pour deconnecter

                default:
                    echo "\n❌ Choix invalide !\n";
                    echo "Appuyez sur Entree pour continuer...";
                    fgets(STDIN);
            }
        }
    }

    protected function displayArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              ARTICLES PUBLIES                     ║\n";
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
            
                echo "📄 Titre: " . $article->getTitre() . "\n";
                echo "👤 Auteur: " . $article->getAuteur()->getUsername() . "\n";
                echo "📅 Date: " . $article->getPublishedAt()->format('d/m/Y') . "\n";
                
                
                $categories = $article->getCategories();
                if (!empty($categories)) {
                    $categoryNames = array_map(fn($cat) => $cat->getName(), $categories);
                    echo "🏷️  Categories: " . implode(', ', $categoryNames) . "\n";
                }
                
                echo "📝 Contenu: " . $article->getContent() . "\n";
                echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
            }
        }

    protected function manageCategoriesMenu(): void {
        while (true) {
            $this->clearScreen();
            echo "╔═══════════════════════════════════════════════════╗\n";
            echo "║          GESTION DES CATEGORIES                   ║\n";
            echo "╚═══════════════════════════════════════════════════╝\n\n";
            echo "1. Afficher toutes les categories\n";
            echo "2. Ajouter une categorie\n";
            echo "3. Supprimer une categorie\n";
            echo "4. Retour au menu principal\n\n";
            
            echo "Votre choix : ";
            $choice = trim(fgets(STDIN));

            switch ($choice) {
                case '1':
                    $this->displayAllCategories();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '2':
                    $this->addCategory();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '3':
                    $this->deleteCategory();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '4':
                    return;

                default:
                    echo "\n❌ Choix invalide !\n";
                    echo "Appuyez sur Entree pour continuer...";
                    fgets(STDIN);
            }
        }
    }

    private function displayAllCategories(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          LISTE DES CATeGORIES                     ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->categories)) {
            echo "❌ Aucune categorie disponible.\n";
            return;
        }

        foreach ($this->categories as $cat) {
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo " ID: " . $cat->getIdCategorie() . "\n";
            echo " Nom: " . $cat->getName() . "\n";
            echo " Description: " . $cat->getDescription() . "\n";
            echo " Parent: " . ($cat->getParent() ? $cat->getParent()->getName() : ) . "\n";
            echo " Date creation: " . $cat->getCreatedAt()->format('d/m/Y H:i') . "\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        }

        echo "Total: " . count($this->categories) . " categorie\n";
    }

    private function addCategory(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          AJOUTER UNE CATEGORIE                    ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        
        echo " Nom de la categorie : ";
        $name = trim(fgets(STDIN));
        
        if (empty($name)) {
            echo "\n❌ Le nom ne peut pas etre vide !\n";
            return;
        }

        echo " Description : ";
        $description = trim(fgets(STDIN));

        echo "\n Categorie parente:\n";
        echo "Categories disponibles:\n";
        foreach ($this->categories as $cat) {
            echo "  [" . $cat->getIdCategorie() . "] " . $cat->getName() . "\n";
        }
        echo "\nID de la categorie parente : ";
        $parentId = trim(fgets(STDIN));

        $parent = null;
        if (!empty($parentId)) {
            foreach ($this->categories as $cat) {
                if ($cat->getIdCategorie() == $parentId) {
                    $parent = $cat;
                    break;
                }
            }
            if (!$parent) {
                echo "\n❌ Categorie parente introuvable !\n";
                return;
            }
        }

        // Creer la categorie
        $newCategory = new Categorie(
            self::$nextCategoryId++,
            $name,
            $description,
            $parent,
            new DateTime()
        );

        $this->categories[] = $newCategory;

        echo "\n✅ Categorie creee avec succès !\n";
        if ($parent) {
            echo "   Parent: " . $parent->getName() . "\n";
        }
    }

    private function deleteCategory(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          SUPPRIMER UNE CATeGORIE                  ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->categories)) {
            echo "❌ Aucune categorie a supprimer.\n";
            return;
        }

        echo "Categories disponibles:\n";
        foreach ($this->categories as $cat) {
            echo "  [" . $cat->getIdCategorie() . "] " . $cat->getName() . "\n";
        }

        echo "\n Entrez l'ID de la categorie a supprimer : ";
        $categoryId = (int)trim(fgets(STDIN));

        $categoryIndex = null;
        foreach ($this->categories as $index => $cat) {
            if ($cat->getIdCategorie() === $categoryId) {
                $categoryIndex = $index;
                break;
            }
        }

        if ($categoryIndex === null) {
            echo "\n❌ Categorie non trouvee !\n";
            return;
        }

        echo "\n  etes-vous sur de vouloir supprimer cette categorie ? (oui/non) : ";
        $confirmation = strtolower(trim(fgets(STDIN)));

        if ($confirmation === 'oui') {
            unset($this->categories[$categoryIndex]);
            $this->categories = array_values($this->categories);
            echo "\n✅ Categorie supprimee avec succès !\n";
        } else {
            echo "\n❌ Suppression annulee.\n";
        }
    }

    protected function manageArticlesMenu(): void {
        while (true) {
            $this->clearScreen();
            echo "╔═══════════════════════════════════════════════════╗\n";
            echo "║          GESTION DES ARTICLES                     ║\n";
            echo "╚═══════════════════════════════════════════════════╝\n\n";
            echo "1. Afficher tous les articles\n";
            echo "2. Modifier un article\n";
            echo "3. Supprimer un article\n";
            echo "4. Publier un article\n";
            echo "0. Retour au menu principal\n\n";
            
            echo "Votre choix : ";
            $choice = trim(fgets(STDIN));

            switch ($choice) {
                case '1':
                    $this->displayAllArticles();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '2':
                    $this->modifyAnyArticle();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '3':
                    $this->deleteAnyArticle();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '4':
                    $this->publishArticle();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '0':
                    return;

                default:
                    echo "\n❌ Choix invalide !\n";
                    echo "Appuyez sur Entree pour continuer...";
                    fgets(STDIN);
            }
        }
    }

    private function displayAllArticles(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          TOUS LES ARTICLES                        ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->articles)) {
            echo "❌ Aucun article disponible.\n";
            return;
        }

        foreach ($this->articles as $article) {
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo " ID: " . $article->getIdArticle() . "\n";
            echo " Titre: " . $article->getTitre() . "\n";
            echo " Auteur: " . $article->getAuteur()->getUsername() . "\n";
            echo " Statut: " . $article->getStatus() . "\n";
            echo " Date creation: " . $article->getPublishedAt()->format('d/m/Y H:i') . "\n";
            
            $categories = $article->getCategories();
            if (!empty($categories)) {
                $categoryNames = array_map(fn($cat) => $cat->getName(), $categories);
                echo "  Categories: " . implode(', ', $categoryNames) . "\n";
            }
            
            echo " Contenu: " . $article->getContent() . "\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        }

        echo "Total: " . count($this->articles) . " article\n";
    }

    private function modifyAnyArticle(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          MODIFIER UN ARTICLE                      ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->articles)) {
            echo "❌ Aucun article a modifier.\n";
            return;
        }    
        echo "Articles disponibles:\n\n";
        foreach ($this->articles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . 
                 " - Auteur: " . $article->getAuteur()->getUsername() . 
                 " (" . $article->getStatus() . ")\n";
        }

        echo "\n Entrez l'ID de l'article a modifier : ";
        $articleId = (int)trim(fgets(STDIN));

        $articleToModify = null;
        foreach ($this->articles as $article) {
            if ($article->getIdArticle() === $articleId) {
                $articleToModify = $article;
                break;
            }
        }

        if (!$articleToModify) {
            echo "\n❌ Article non trouve !\n";
            return;
        }

        echo "\n Titre actuel: " . $articleToModify->getTitre() . "\n";
        echo "Nouveau titre  : ";
        $newTitre = trim(fgets(STDIN));

        echo "\n Contenu actuel: " . $articleToModify->getContent() . "\n";
        echo "Nouveau contenu  : ";
        $newContent = trim(fgets(STDIN));


        echo "\n Statut actuel: " . $articleToModify->getStatus() . "\n";
        echo "Nouveau statut :\n";
        echo "1. draft \n";
        echo "2. published \n";
        echo "3. archived \n";
        echo "4. laisser le statut actuel\n";
        echo "Choisissez (1-4) : ";
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
                $newStatus = 'archived';
                break;
            case '4':
                
                break;
            default:
                echo "\n  Choix invalide, le statut reste inchange.\n";
                break;
        }
        
        if (!empty($newTitre)) {
            $articleToModify->setTitre($newTitre);
        }

        if (!empty($newContent)) {
            $articleToModify->setContent($newContent);
        }
        if ($newStatus !== null) {
            $articleToModify->setStatus($newStatus);
            
        }

        $articleToModify->setUpdatedAt(new DateTime());

        echo "\n Article modifie avec succès !\n";
    }

    private function deleteAnyArticle(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          SUPPRIMER UN ARTICLE                     ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->articles)) {
            echo "❌ Aucun article a supprimer.\n";
            return;
        }

        
        echo "Articles disponibles:\n\n";
        foreach ($this->articles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . 
                 " - Auteur: " . $article->getAuteur()->getUsername() . 
                 " (" . $article->getStatus() . ")\n";
        }

        echo "\n Entrez l'ID de l'article a supprimer : ";
        $articleId = (int)trim(fgets(STDIN));

        $articleIndex = null;
        foreach ($this->articles as $index => $article) {
            if ($article->getIdArticle() === $articleId) {
                $articleIndex = $index;
                break;
            }
        }

        if ($articleIndex === null) {
            echo "\n❌ Article non trouve !\n";
            return;
        }

        echo "\n etes-vous sur de vouloir supprimer cet article ? (oui/non) : ";
        $confirmation = strtolower(trim(fgets(STDIN)));

        if ($confirmation === 'oui') {
            unset($this->articles[$articleIndex]);
            $this->articles = array_values($this->articles);
            echo "\n✅ Article supprime avec succès !\n";
        } else {
            echo "\n❌ Suppression annulee.\n";
        }
    }

    private function publishArticle(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          PUBLIER UN ARTICLE                       ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        
        $unpublishedArticles = array_filter($this->articles, fn($a) => $a->getStatus() !== 'published');

        if (empty($unpublishedArticles)) {
            echo "❌ Aucun article a publier (tous sont deja publies).\n";
            return;
        }

        echo "Articles non publies:\n\n";
        foreach ($unpublishedArticles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . 
                 " - Auteur: " . $article->getAuteur()->getUsername() . 
                 " (" . $article->getStatus() . ")\n";
        }

        echo "\n Entrez l'ID de l'article a publier : ";
        $articleId = (int)trim(fgets(STDIN));

        foreach ($this->articles as $article) {
            if ($article->getIdArticle() === $articleId) {
                $article->setStatus('published');
                $article->setPublishedAt(new DateTime());
                $article->setUpdatedAt(new DateTime());
                echo "\n✅ Article publie avec succès !\n";
                return;
            }
        }

        echo "\n❌ Article non trouve !\n";
    }

    private function changeArticleStatus(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          CHANGER LE STATUT D'UN ARTICLE           ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->articles)) {
            echo "❌ Aucun article disponible.\n";
            return;
        }

        
        echo "Articles disponibles:\n\n";
        foreach ($this->articles as $article) {
            echo "[" . $article->getIdArticle() . "] " . $article->getTitre() . 
                 " - Statut actuel: " . $article->getStatus() . "\n";
        }

        echo "\n Entrez l'ID de l'article : ";
        $articleId = (int)trim(fgets(STDIN));

        $articleToModify = null;
        foreach ($this->articles as $article) {
            if ($article->getIdArticle() === $articleId) {
                $articleToModify = $article;
                break;
            }
        }

        if (!$articleToModify) {
            echo "\n❌ Article non trouve !\n";
            return;
        }

        echo "\nStatuts disponibles:\n";
        echo "1. draft \n";
        echo "2. published \n";
        echo "3. archived\n";
        echo "Choisissez le nouveau statut (1-3) : ";
        $choice = trim(fgets(STDIN));

        $newStatus = match($choice) {
            '1' => 'draft',
            '2' => 'published',
            '3' => 'archived',
            default => null
        };

        if ($newStatus) {
            $articleToModify->setStatus($newStatus);
            if ($newStatus === 'published') {
                $articleToModify->setPublishedAt(new DateTime());
            }
            $articleToModify->setUpdatedAt(new DateTime());
            echo "\n✅ Statut change en '$newStatus' avec succès !\n";
        } else {
            echo "\n❌ Choix invalide !\n";
        }
    }
    public function getArticles(): array {
        return $this->articles;
    }

    public function getCategories(): array {
        return $this->categories;
    }
}

?>