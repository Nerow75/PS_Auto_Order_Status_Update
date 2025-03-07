# Module PrestaShop: Auto Order Status Update

## Description

Ce module PrestaShop permet de mettre à jour automatiquement le statut d'une commande en fonction du transporteur sélectionné. Il est particulièrement utile pour les boutiques en ligne qui souhaitent automatiser la gestion des statuts de commande en fonction des options de livraison choisies par les clients.

## Fonctionnalités

- **Mise à jour automatique du statut** : Le module met à jour le statut de la commande en "livré" lorsque le transporteur configuré est utilisé.
- **Configuration simple** : Sélectionnez facilement le transporteur à partir du back-office de PrestaShop.
- **Compatibilité** : Compatible avec PrestaShop 8.

## Installation

### Via le Back-Office

1. **Téléchargement** : Téléchargez le module depuis le dépôt GitHub.
2. **Installation via le back-office** :
   - Allez dans le back-office de votre boutique PrestaShop.
   - Naviguez vers `Modules` > `Gestion des modules`.
   - Cliquez sur `Upload a module` et sélectionnez le fichier ZIP du module.
   - Cliquez sur `Installer` pour installer le module.
3. **Configuration** :
   - Une fois installé, allez dans `Modules` > `Modules installés`.
   - Trouvez le module `Auto Order Status Update` et cliquez sur `Configurer`.
   - Sélectionnez le transporteur pour lequel vous souhaitez que le statut soit automatiquement mis à jour.

### Via FTP

1. **Téléchargement** : Téléchargez le module depuis le dépôt GitHub.
2. **Accès FTP** : Connectez-vous à votre serveur via FTP.
3. **Navigation** :
   - Naviguez vers le répertoire `modules` de votre installation PrestaShop.
   - Si le dossier `autoorderstatusupdate` n'existe pas, créez-le.
4. **Extraction** :
   - Extrayez le contenu du fichier ZIP du module dans le dossier `autoorderstatusupdate`.
5. **Installation via le back-office** :
   - Allez dans le back-office de votre boutique PrestaShop.
   - Naviguez vers `Modules` > `Gestion des modules`.
   - Trouvez le module `Auto Order Status Update` et cliquez sur `Installer`.
6. **Configuration** :
   - Suivez les mêmes étapes que pour l'installation via le back-office pour configurer le module.

## Utilisation

Après avoir configuré le module avec le transporteur souhaité, le statut des commandes utilisant ce transporteur sera automatiquement mis à jour en "livré" une fois que la commande est traitée.

## Désinstallation

Pour désinstaller le module, allez dans `Modules` > `Modules installés`, trouvez le module `Auto Order Status Update`, et cliquez sur `Désinstaller`.

## Support

Pour toute question ou support, veuillez ouvrir une issue sur ce dépôt GitHub ou contacter l'auteur du module.

## Auteur

- **Antoine**

## Remerciements

Merci à tous les contributeurs et utilisateurs qui ont aidé à améliorer ce module.

---

N'hésitez pas à contribuer en signalant des bugs, en proposant des améliorations ou en ajoutant de nouvelles fonctionnalités !
