=== Easy Custom Event Tickets ===
Contributors: rock4temps
Donate link: https://rouerguecreation.fr/easy-custom-event-tickets#dons
Tags: The Events Calendar,Event Tickets,Duplicate,clone
Requires at least: 4.9.14
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 2.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Dupliquer les évènements,afficher la liste des participants à un évènement crée avec les plugins «The Events Calendar» & «Event Tickets».
Modif style du bloc RSVP, .


== Description ==
Cette extension permet facilement de **dupliquer vos évènements** qui doivent être récurrent,
avec un nouveau ticket opérationnel,crée et configurer selon les paramètres du ticket d'origine.
Ce plugin permet d'afficher la **liste des participants** ainsi que **le nombre de réservation par participant** 
à un évènement crée avec les plugins «The Events Calendar» & «Event Tickets».
Cette liste est affichée dans une table en dessous du bloc Gutenberg RSVP ou du bloc Gutenberg Tickets(pour des billets payants).
**Important!** après Réservation ou Achat d'un billet veuillez rafraichir la page qui détaille l'évènement
(touche F5 avec le navigateur chrome) pour actualiser la **table Liste Des Participants**.
**Cette extension est compatible avec les versions Free & Pro des extensions The Events Calendar & Event Tickets.**


== Personnalisation ==

Customisation du bloc Gutenberg RSVP

◆ la mention «RSVP» est masqué, le texte du bouton: 
 PARTICIPE est remplacé par RÉSERVER
◆ la mention «RSVP CLOTURÉ» est remplacé par 
RÉSERVATION CLOTURÉ 
◆ la mention en intro du formulaire «vos informations RSVP» est remplacé par: vos informations de réservation 
...


Customisation du calendrier des évènements

◆ Filtre par catégories d'évènement dans la barre de recherche
◆ Affichage du nombre de participant sur le calendrier des évènements en vue Liste & Photo.
◆ Personnalisation de l'info-bulle de la vue mois: Nom du lieu + ville de l'évènement,Nombre de places restantes 
◆ Personnalisez la vue photo du calendrier des événements version pro.
◆ Personnalisez la vue photo alternative du calendrier des événements établit avec l'extension Tribe Ext Alternative Photo View

== Duplication Évènement ==

◆ Ajout d'un lien Dupliquer Évènement pour les actions 
de l'écran qui liste les évènements.Si un ticket payant 
ou RSVP est présent dans l'évènement dupliqué il est 
supprimé car il reste lié a l'évènement que l'on a 
dupliqué.Un nouveau bloc ticket est crée en reprenant 
les paramètres du bloc ticket original(capacité, titre,
description,prix,etc....
◆ Vous pouvez modifier la date de début et de fin de vos 
évènements dans l'édition rapide de l'écran qui liste les 
évènements.Cela a l'avantage de modifier également la 
date de début & de fin du ticket en corrélation avec la 
nouvelle date de l'évènement.Ainsi en restant sur l'écran 
qui liste les évènements vous pouvez dupliquer 
facilement vos évènements qui doivent être récurrent et 
avoir pour ces évènements un ticket opérationnel. 

== Dépendances ==

Pour que l'affichage de la liste des participants au calendrier des événements fonctionnent correctement, 
vous devez installer ses plugins parent, 
[The Events Calendar](https://fr.wordpress.org/plugins/the-events-calendar/) & [Event Tickets](https://fr.wordpress.org/plugins/event-tickets/).


== Démo ==
Découvrez le plugin en action sur notre [Site De Démos](https://rouerguecreation.fr/demos/)

== Traductions ==
◆ Anglais
◆ Français
◆ Espagnol

== Decouvrez nos autres plugins ==
◆ [Easy Custom OceanWP Shop](https://wordpress.org/plugins/easy-custom-oceanwp-shop/)
◆ [Editor Custom Color Palette](https://wordpress.org/plugins/editor-custom-color-palette/)

== Screenshots ==
1. Bloc Event Tickets RSVP, Liste Participants
2. Custom Formulaire RSVP 
3. Custom Bloc RSVP: RÉSERVATION CLOTURÉ
4. Calendrier Évènement en vue liste: nbr Participant, billets restants RSVP
5. Bloc Event Tickets(billets payants) , Liste Participants
6. Calendrier Évènement en vue liste: nbr Participant, billets payants restants
7. Page de réglages du plugin vue 1
8. Page de réglages du plugin vue 2
9. Calendrier Évènement Pro vue photo
10. Info-bulle Vue Mensuelle: Nom du lieu + ville évènement, Nombre de places restantes
11. Back Office: Lien Dupliquer Évènement


== Installation ==
1. Assurez-vous que les plugins «The Events Calendar» &  «Event Tickets» de Modern Tribe soient installé et activé.
2. Depuis le tableau de bord de votre site, accédez à Plugins -> Ajouter nouveau.
3. Sélectionnez l'option Télécharger et cliquez sur "Choisir un fichier".
4. Un message contextuel apparaîtra. Téléchargez les fichiers du plugin depuis votre bureau.
5. Suivez les instructions qui s'affichent.
6. Activez le plugin à partir de la page Plugins.
7. Accédez aux réglages du plugin via le menu **ECET Réglages**.

== Changelog =

2.1.1 - 09/02/2024

Modifié
- Refonte globale du code.
- Pour toutes les fonctionnalités de l'extension,prise en compte de 
plusieurs tickets pour un même évènement,en particulier pour la 
duplication d'évènement.


2.1 - 13/01/2024

Ajouté
- Ajout d'un lien Dupliquer Évènement pour les actions de l'écran
qui liste les évènements.Si un ticket payant ou RSVP est présent dans 
l'évènement dupliqué il est supprimé car il reste lié a l'évènement 
que l'on a dupliqué.Un nouveau bloc ticket est crée en reprenant les 
paramètres du bloc ticket original(capacité, titre,description,prix,
etc....
- Vous pouvez modifier la date de début et de fin de vos évènements 
dans l'édition rapide de l'écran qui liste les évènements.Cela a 
l'avantage de modifier également la date de début & de fin du ticket
en corrélation avec la nouvelle date de l'évènement.Ainsi en restant 
sur l'écran qui liste les évènements vous pouvez dupliquer facilement 
vos évènements qui doivent être récurrent et avoir pour ces évènements 
un ticket opérationnel. 

■ Page Réglages général:
- section Table des participants: ajout d'une option pour activer 
l'affichage de la table des participants


2.0.9 - 23/09/2023

Ajouté

■ Page Réglages général:
- section Table des participants: ajout d'une option pour activer les 
tables responsives.Pour une résolution écran < 767px la colonne de 
gauche est fixe et le reste des colonnes peuvent défiler.


2.0.8 - 31/07/2023


Modifié
- Correction d'un bug pour l'affichage de la table des participants,
lorsque c'est la passerelle de paiement Stripe qui est sélectionnée.
- Centrer verticalement l'image de l'évènement pour la vue Liste.


2.0.7 - 17/06/2023


Modifié
- correction d'un bug mineur suite mise a jour The Events Calendar 6.1.1
pour la vue liste & photo concernant une régle CSS appliquée au 
nombre de billets restant.


2.0.6 - 08/05/2023


Modifié
- correction d'un bug mineur sur le filtre par catégories d'évènement 
dans la barre de recherche.


2.0.5 - 24/04/2023

Ajouté

■ Page Réglages général:
- Ajout d'un filtre par catégories d'évènement dans la barre de recherche.
- Ajout d'une option pour afficher une balise catégorie pour la vue liste.
- Ajout d'une option pour afficher une balise catégorie pour la vue photo.

Modifié

- Pour la vue liste ou photo le Nombre de participant est affiché que s'il 
y a des réservations.


2.0.4 - 15/04/2023

Ajouté

■ Page Réglages général:
- Section Personnalisez la vue photo du calendrier des événements version pro:
ajout d'une option pour déplacer la date de l'évènement à l'intérieur de la 
photo.
- Section personnalisation vue Mois: option pour ajouter dans l'info-bulle 
de la vue mois le Nom du lieu + ville de l'évènement,Nombre de places 
restantes.
- Ajout d'une section pour tenir compte des utilisateurs qui ont activé 
l'extension Tribe Ext Alternative Photo View pour une vue photo 
alternative a celle de The Events Caldendar Pro.


2.0.3 - 10/04/2023

Ajouté

■ Page Réglages général:
- Ajout d'une section pour personnalisez la vue photo du calendrier des 
événements en version pro. 

Modifié
- Les inscriptions manuelle des participants en Back Office possible avec 
la version Pro de Event Tickets, sont désormais prises en charge par 
l'extension Easy Custom Event Tickets.
- Style du bloc Gutenberg RSVP: correction du code car le style n'était
pas toujours pris en compte.
 

2.0.2 - 31/03/2023

Ajouté

■ Page Réglages général: 
- section Sélectionner la version de vos extensions: ajout de l'option 
Sélectionner la version de vos extensions,afin d'assurer une 
compatibilité de l'extension Easy Custom Event Tickets avec les 
versions free & pro des extensions The Events calendar & Event Tickets.
- section Table des participants:ajout de l'option Activer le format 
personnalisé pour le nom des participants. Permet d'afficher 
partiellement le Nom des participants à condition que les utilisateurs 
renseignent le champ Nom du formulaire d'inscription à un évènement en 
commençant par le Prénom suivit du Nom soit par exemple:
Gilles Dupont est remplacé par le format personnalisé: Gilles Du.


Modifié

■  Correction du bug: lorsqu'une même personne effectue une réservation 
a deux date différentes alors le nombre de participants affiché dans la 
table liste des participants était erroné.
Correction du bug: l'extension fait disparaître le nombre de places 
disponibles en mode vue Liste, Résumé, Photos pour la version pro The 
Events Calendar.


2.0.1 - 08/04/2022

Modifié
■  Correction du bug affichage de la liste des participants en dessous du bloc Gutenberg Tickets Pour des billets payants.


2.0 - 01/06/2021

Modifié
■  optimisation du code

1.9 - 22/05/2021

Modifié
■  Modification du style du plugin en backoffice WordPress

Ajouté
■ Page Réglages général: 
- section Paramètres par défaut: ajout de l'option supprimer les données lors de la désinstallation

1.8 - 05/04/2021

Modifié
■  Modification de la page de réglages du plugin, avec ajout:

Style du bloc Gutenberg RSVP
- texte du bouton
- texte en dessous du nombre de participants
- texte affiché pour une réservation fermé
- texte affiché en introduction du formulaire de réservation

Personnaliser la vue liste du calendrier des évènements
- texte affiché en préfixe du nombre de participants
- texte affiché en préfixe du nombre de billets restants

Plugin traduit dans les langues: Anglais, Français, Espagnol


1.7 - 02/04/2021

Modifié
■  Optimisation du code et de la traduction.
Retrait de la page de réglages du plugin de la Langue par défaut pour la personnalisation du bloc Gutenberg RSVP, maintenant directement géré par les fichiers de traduction.
correction des bugs: 
- prise en compte des paramètres de réglages pour le plugin définit par l'utilisateur au lieu des valeurs par défaut lorsqu'il désactive puis active à nouveau le plugin.
- lors d'un upgrade du plugin si nécessaire on effectue une mise à jour de la table wp-prefix_options.


1.6 - 29/03/2021

Modifié
■  Modification de la page de réglages du plugin, avec ajout:
Langue par défaut pour la personnalisation du bloc Gutenberg RSVP.
Gestion de la traduction pour l'ajout des textes «nombre de participants» & «billets restants» sur le calendrier des évènements en vue Liste.


1.5 - 28/03/2021

Modifié
■  Modification du Text Domain pour qu'il corresponde au slug de
l'extension afin que le plugin puisse être traduit en ligne sur https://translate.wordpress.org/


1.4 - 19/03/2021

Modifié
■  Modification de la page de réglages du plugin, avec ajout:
- initialiser le plugin avec les paramètres par défaut
- Balise HTML du titre
- couleur d'arrière plan ligne paire
- épaisseur de la bordure en pixel
- couleur bordure
- taille police en pixel
- couleur police


1.3 - 15/03/2021

Ajouté
■  Grosse mise a jour: Ajouté page de réglages simple du plugin & traductions
- A l'activation du plugin on ajoute dans la table wp-prefix_options les réglages par défaut du plugin
- A la désinstallation du plugin on supprime les réglages du plugin dans la table wp-prefix_options


1.2 - 13/03/2021

Ajouté
■  Affichage de la liste des participants en dessous du bloc Gutenberg Tickets Pour des billets payants


1.1 - 07/03/2021

Modifié
■  Optimisation requête pour établir la liste des participants


1.0

■  version Initiale






