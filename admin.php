<?php

require_once 'index.php';
require_once 'editor.php';

class AdminMenu extends EditorMenu { 
    private Admin $admin;
    private array $users;

    public function __construct(Admin $admin, array $articles, array $categories, array $users) {
        parent::__construct($admin, $articles, $categories);
        $this->admin = $admin;
        $this->users = $users;
    }

    private function clearScreen(): void {
        echo "\033[2J\033[;H";
    }

    public function displayMenu(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║              MENU ADMINISTRATEUR                  ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";
        echo " Connecte en tant que: " . $this->admin->getUsername() . "\n";
        echo " Super Admin: " . ($this->admin->getIsSuperAdmin() ? 'Oui' : 'Non') . "\n\n";
        echo "1. Afficher les articles\n";
        echo "2. Gestion des categories\n";
        echo "3. Gestion des articles\n";
        echo "4. Gestion des utilisateurs\n";
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

                case '4':
                    $this->manageUsersMenu();
                    break;

                case '0':
                    $this->clearScreen();
                    echo "\n✅ Deconnexion reussie !\n";
                    return false;

                default:
                    echo "\n❌ Choix invalide !\n";
                    echo "Appuyez sur Entree pour continuer...";
                    fgets(STDIN);
            }
        }
    }


    private function manageUsersMenu(): void {
        while (true) {
            $this->clearScreen();
            echo "╔═══════════════════════════════════════════════════╗\n";
            echo "║          GESTION DES UTILISATEURS                 ║\n";
            echo "╚═══════════════════════════════════════════════════╝\n\n";
            echo "1. Afficher tous les utilisateurs\n";
            echo "2. Ajouter un utilisateur\n";
            echo "3. Supprimer un utilisateur\n";
            echo "0. Retour au menu principal\n\n";
            
            echo "Votre choix : ";
            $choice = trim(fgets(STDIN));

            switch ($choice) {
                case '1':
                    $this->displayAllUsers();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '2':
                    $this->addUser();
                    echo "\nAppuyez sur Entree pour continuer...";
                    fgets(STDIN);
                    break;

                case '3':
                    $this->deleteUser();
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

    private function displayAllUsers(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          LISTE DES UTILISATEURS                   ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->users)) {
            echo "❌ Aucun utilisateur disponible.\n";
            return;
        }

        foreach ($this->users as $user) {
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo " ID: " . $user->getIdUtilisateur() . "\n";
            echo " Username: " . $user->getUsername() . "\n";
            echo " Email: " . $user->getEmail() . "\n";
            echo " Type: " . get_class($user) . "\n";
            echo " Date creation: " . $user->getCreatedAt()->format('d/m/Y H:i') . "\n";
            if ($user->getLastLogin()) {
                echo " Dernière connexion: " . $user->getLastLogin()->format('d/m/Y H:i') . "\n";
            }
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        }

        echo "Total: " . count($this->users) . " utilisateur(s)\n";
    }

    private function addUser(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          AJOUTER UN UTILISATEUR                   ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        
        echo " Nom d'utilisateur : ";
        $username = trim(fgets(STDIN));
        
        if (empty($username)) {
            echo "\n❌ Le nom d'utilisateur ne peut pas etre vide !\n";
            return;
        }

        
        foreach ($this->users as $user) {
            if ($user->getUsername() === $username) {
                echo "\n❌ Ce nom d'utilisateur existe deja !\n";
                return;
            }
        }

        
        echo " Email : ";
        $email = trim(fgets(STDIN));
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "\n❌ Email invalide !\n";
            return;
        }

        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                echo "\n❌ Cet email existe deja !\n";
                return;
            }
        }

        echo " Mot de passe : ";
        $password = trim(fgets(STDIN));
        
        if (empty($password)) {
            echo "\n❌ Le mot de passe ne peut pas etre vide !\n";
            return;
        }

        echo "\n Type utilisateur:\n";
        echo "1. Auteur\n";
        echo "2. Editeur\n";
        echo "3. Administrateur\n";
        echo "Choisissez (1-3) : ";
        $typeChoice = trim(fgets(STDIN));

        $newId = max(array_map(fn($u) => $u->getIdUtilisateur(), $this->users)) + 1;

        $newUser = null;
        switch ($typeChoice) {
            case '1':
                $newUser = new Auteur($newId, $username, $email, $password, new DateTime(), null);
                break;
            case '2':
                $newUser = new Editeur($newId, $username, $email, $password, new DateTime(), null, 'standard');
                break;
            case '3':
                echo "Super Admin ? (oui/non) : ";
                $isSuperAdmin = strtolower(trim(fgets(STDIN))) === 'oui';
                $newUser = new Admin($newId, $username, $email, $password, new DateTime(), null, $isSuperAdmin);
                break;
            default:
                echo "\n❌ Choix invalide !\n";
                return;
        }

        $this->users[] = $newUser;
        echo "\n Utilisateur cree avec succès !\n";
        echo "   Username: " . $username . "\n";
        echo "   Email: " . $email . "\n";
        echo "   Type: " . get_class($newUser) . "\n";
    }

    private function deleteUser(): void {
        $this->clearScreen();
        echo "╔═══════════════════════════════════════════════════╗\n";
        echo "║          SUPPRIMER UN UTILISATEUR                 ║\n";
        echo "╚═══════════════════════════════════════════════════╝\n\n";

        if (empty($this->users)) {
            echo "❌ Aucun utilisateur a supprimer.\n";
            return;
        }

        echo "Utilisateurs disponibles:\n";
        foreach ($this->users as $user) {
            echo "  [" . $user->getIdUtilisateur() . "] " . $user->getUsername() . 
                 " (" . get_class($user) . ")\n";
        }

        echo "\n Entrer ID de utilisateur a supprimer : ";
        $userId = (int)trim(fgets(STDIN));

        if ($userId === $this->admin->getIdUtilisateur()) {
            echo "\n❌ Vous ne pouvez pas supprimer votre propre compte !\n";
            return;
        }

        $userIndex = null;
        $userToDelete = null;
        foreach ($this->users as $index => $user) {
            if ($user->getIdUtilisateur() === $userId) {
                $userIndex = $index;
                $userToDelete = $user;
                break;
            }
        }

        if ($userIndex === null) {
            echo "\n❌ Utilisateur non trouve !\n";
            return;
        }

        echo "\n  etes vous sur de vouloir supprimer utilisateur " . 
             $userToDelete->getUsername() . "' ? (oui/non) : ";
        $confirmation = strtolower(trim(fgets(STDIN)));

        if ($confirmation === 'oui') {
            unset($this->users[$userIndex]);
            $this->users = array_values($this->users);
            echo "\n✅ Utilisateur supprime avec succès !\n";
        } else {
            echo "\n❌ Suppression annulee.\n";
        }
    }

    public function getUsers(): array {
        return $this->users;
    }
}
?>