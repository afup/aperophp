# language: fr

Fonctionnalité: La homepage fonctionne correctement

  Scénario: La page s'affiche correctement
    Quand je vais sur la page d'accueil
    Alors la page "/" s'affiche correctement

  Scénario: Le menu est correctement affiché
    Quand je vais sur la page d'accueil
    Alors le menu est affiché
    Et le menu contient ces éléments:
      | libellé            | lien                 |
      | Accueil            | /                    |    
      | Organiser un apéro | /new.html            |
      | Liste des apéros   | /list.html           |
      | S'inscrire         | /member/signup.html  |
      | Se connecter       | /member/signin.html  |

  Scénario: La liste des apéritifs à venir est affichée
    Quand je vais sur la page d'accueil
    Alors le bloc "A venir" est visible
    Et l'apéritif du "19 Juillet" à "Paris" est visible
    Et l'apéritif du "19 Juillet" à "Lyon" n'est pas visible

  Scénario: Le bloc de création d'un apéritif est disponible
    Quand je vais sur la page d'accueil
    Alors le bloc "Les apéros PHP" est visible
    Et il est possible de créer un apéro
