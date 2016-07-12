atexo-cards
===========

### Description de la solution

- Récupérer les cartes depuis l'API via la librairie Guzzle
- Ordonner les cartes
	- Pour chaque carte non ordonnée
        - Récupérer l'ordre de la catégorie et de la valeur selon le tableau d'ordres
        - Créer un tableau de 2 dimensions contenant la valeur et l'ordre de la carte,
        - les clés de ce tableau sont la catégorie et la valeur de la carte
    - Ordonner le tableau récursivement selon les clés
    - Transformer le tableau en une seule dimension
- Envoyer le résultat à l'API
- Afficher le résultat au format JSON

### Installation

```sh
$ git clone https://github.com/akramfares/atexo-cards.git
$ cd atexo-cards/
$ composer install
```

### Lancement des tests

```sh
$ phpunit -c app/
```

A Symfony 2.8 project created on July 11, 2016, 11:23 am.
