# language: fr

Fonctionnalité: La gestion des comptes est fonctionnelle

  Scénario: Il est possible de s'inscrire
    Quand je vais sur la page d'inscription
    Alors la page d'inscription s'affiche correctement
    Quand je me créé un compte "test_behat"/"password"/"test_behat@test.fr"
    Alors imprimer la dernière réponse
    Alors la page de connexion s'affiche correctement
    Et le message de succès "Votre compte a été créé avec succès." s'affiche

  # Scénario: Il est possible de se connecter
  #   Quand je vais sur la page de connexion
  #   Alors la page de connexion s'affiche correctement
  #   Quand je me connecte avec les identifiants "test_behat"/"password"
  #   Alors je suis sur la page d'accueil
  #   Et je suis connecté en tant que "test_behat"
