# Install

    rm -rf vendors/*
    bin/vendors install

## Vhost example


    <VirtualHost *:80>
        DocumentRoot "/path/to/"
        ServerName www.aperophp.dev

        <Directory /path/to/web/>
            Options Indexes Includes FollowSymLinks -MultiViews
            AllowOverride All
            Order allow,deny
            Allow from all
 
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ index.php [QSA,L]    
        </Directory>
    </VirtualHost>

# TODO

## Minimal Viable Product

* [DONE] créer un apéro (ville, lieu de l'apéro, date et heure)
* [DONE] modifier un apéro
* [DONE] avoir une carte (genre Google Maps) du lieu
* [DONE] dire qu'on participe à un apéro (j'aime bien le %age de présence :))
* nombre de personnes donc
* [DONE] enlever sa participation à l'apéro
* [DONE]pouvoir poster des commentaires
* pouvoir poster deslien pré et post-apéros (genre les photos)
* une interface plus jolie que l'acutelle !!! => graphiste alcoolique ami ? twitter bootstrap ?
* système d'auth:
 * un utilisateur s'inscrit à un apéro (pas au site !) avec juste son mail + nom
 * le site lui envoie un mail avec une url perso qui contient un token (par exemple un md5)
 * l'utilisateur peut modifier son inscription à l'apéro via cette url
 * si il perd l'url, on peut facilement lui régénérer un token et lui renvoyer un mail

## Nice to have

* authentification avec des services tierces (Openid, Twitter, Google, Facebook, etc.) oui 
* mise en avant des membres AFUP
* mini-système de news pour le site
* lien avec les antennes locales de l'AFUP (pour Lyon, Nantes, Orléans, par exemple)
* accès et gestion directe depuis le back-office de l'AFUP
* définir certains apéros comme des Mini confs : intervenants et sujet / lightning talks (slides si dispos) pour les non-membres AFUP qui veulent copier les "Rendez-Vous AFUP"


## Inutile

* mais je ne suis pas sûre qu'il faille avoir du postage de photo sur le site web (service déporté comme flickR ira bien) +1, ça fait toujours ça de moins à gérer

# Backlog

* 2012-03-18 - Depuis Twig 1.6.2, tout écran avec un formulaire affiche une liste de notices