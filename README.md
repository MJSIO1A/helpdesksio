# Projet - L'Atelier des Jeux

<p align="center">
  <img src="https://github.com/MJSIO1A/helpdesksio/blob/main/images/support.png" width="60%">
</p>

<h2 align="center"><strong>L'Atelier des jeux</strong></h2>

L’Atelier des Jeux est une entreprise spécialisée dans la création de jeux (vidéo, plateau, rôle, cartes), comptant plus de cinquante employés. Intégré au service informatique dans le cadre d’un stage, nous avons pour mission de développer un logiciel d’assistance (Helpdesk) en PHP. Ce système permettra aux utilisateurs de soumettre des tickets en cas de problème, et aux techniciens de les consulter et de les traiter. 

**Le projet comprend un espace pour le client, une interface sécurisée pour les techniciens, la gestion des statuts des tickets, un système de connexion, de logs et un design responsive, le tout avec une mise en page soignée.**

## Les pages du projet


**Une page d'accueil**

**Une page de demande d'assistance**

**Un système de connexion pour technicien**

**Panneau d'accueil avec les tickets**

**Une consultation plus approfondi des tickets**

**La création d'un compte technicien**

**Un système de log (avec une fonctionnalité en plus)** 

**Une option de sécurité(voir dans la partie code)**

**+ du css et responsive(voir dans la partie code)**

_Tout les codes des pages ci-dessous sont après la présentation graphique des pages_

---

**- La page d'accueil :**
![PageAccueil](https://github.com/MJSIO1A/helpdesksio/blob/main/images/accueil.png)

---

**- Page de demande d'assistance :**
![Assistance](https://github.com/MJSIO1A/helpdesksio/blob/main/images/assistance.png)
 
---

**- Page connexion techinicien**
![connexion](https://github.com/MJSIO1A/helpdesksio/blob/main/images/connexion.png)

---

**- Panneau d'accueil :**
![Dashboard](https://github.com/MJSIO1A/helpdesksio/blob/main/images/dashboard.png)

---

**- Consultation plus approfondi du ticket :**
![ApprofTick](https://github.com/MJSIO1A/helpdesksio/blob/main/images/dashboardpro.png)

---

**- Création compte technicien :**
![CreateTech](https://github.com/MJSIO1A/helpdesksio/blob/main/images/inscription.png)

---

**- Système de log (fait en collaboration avec Lucas Sueur + une fonctionnalitée)**
![logs](https://github.com/MJSIO1A/helpdesksio/blob/main/images/logs.png)

## Codes du projet

_- Code Accueil :_ Comme c'est le début, il n'y a pas forcément de php, cela peut être optionnel
![CodeAccueil](https://github.com/MJSIO1A/helpdesksio/blob/main/images/code1.png)

---

_- Code de demande assistance :_ Ici nous pouvons voir du php, mais également *require_once 'includes/db.php';*. Cela va nous permettre de nous connecter à la BDD de _helpdesk_, afin ensuite de pourvoir enregistrer les demandes.
![CodeAssistance](https://github.com/MJSIO1A/helpdesksio/blob/main/images/codephp.png)

---

_- Code connexion technicien :_ De même ici, une connexion à la BDD à partir de la table _utilisateurs_.
![CodeConnexion](https://github.com/MJSIO1A/helpdesksio/blob/main/images/codephp2.png)

---

_- Code Tableau tickets :_ Un fichier de connexion différent pour une autre connexion à une autre table, la table _Tickets._
![CodeDash](https://github.com/MJSIO1A/helpdesksio/blob/main/images/code%20php3.png)

---

_- Code Aprofondissement d'un ticket :_
![CodePro](https://github.com/MJSIO1A/helpdesksio/blob/main/images/codephp4.png)

---

_- Code Création technicien :_ Dans le code, nous pouvons voir un autre fichier php pour la BDD.
![CodeRegist](https://github.com/MJSIO1A/helpdesksio/blob/main/images/coderegister.png)

---

_- Code système de logs + la fonctionnalitée vider les logs avec un bouton :_
![CodeLogs](https://github.com/MJSIO1A/helpdesksio/blob/main/images/codelog.png)

---

_- Le css est responsive grâce à media queries, intégré dans le css(fait par Yanis Tanquerel) :_

![queries](https://github.com/MJSIO1A/helpdesksio/blob/main/images/queries.png)


## Démonstration du projet

![Voir la vidéo](
