# Changelog

## [1.0.0] - 2026-05-05

### Première version

- Mise à jour automatique du statut de commande en "Livré" selon le transporteur configuré
- Configuration back-office : sélection du transporteur déclencheur via liste déroulante
- Hooks enregistrés : `actionObjectOrderAddAfter`, `actionOrderHistoryAddAfter`, `actionOrderStatusPostUpdate`
- Nettoyage automatique de la configuration à la désinstallation
- Compatible PrestaShop 8.0.0+
