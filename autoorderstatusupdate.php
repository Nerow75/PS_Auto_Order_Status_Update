<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class autoorderstatusupdate extends Module
{
    public function __construct()
    {
        $this->name = 'autoorderstatusupdate';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Antoine';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = ['min' => '8.0.0', 'max' => _PS_VERSION_];
        $this->bootstrap = true;

        parent::__construct();

        // Nom affiché du module
        $this->displayName = $this->l('Auto Order Status Update');
        // Description du module
        $this->description = $this->l('Mise à jour automatique du statut de la commande en fonction du transporteur.');

        // Message de confirmation de désinstallation
        $this->confirmUninstall = $this->l('Etes-vous sûr de vouloir désinstaller ce module ?');
    }

    public function install()
    {
        // Enregistrement des hooks et initialisation des valeurs de configuration
        if (
            !parent::install() ||
            !$this->registerHook('actionObjectOrderAddAfter') ||
            !$this->registerHook('actionOrderHistoryAddAfter') ||
            !$this->registerHook('actionOrderStatusPostUpdate')
        ) {
            return false;
        }
        Configuration::updateValue('ASU_NEW_ORDER_STATE', 0);
        Configuration::updateValue('ORDERSTATUSUPDATE_CARRIER_ID', 0);
        return true;
    }

    public function uninstall()
    {
        // Suppression des valeurs de configuration lors de la désinstallation
        if (
            !parent::uninstall() ||
            !Configuration::deleteByName('ASU_NEW_ORDER_STATE') ||
            !Configuration::deleteByName('ORDERSTATUSUPDATE_CARRIER_ID')
        ) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $output = '';

        // Gestion de la soumission du formulaire de configuration
        if (Tools::isSubmit('submitOrderStatusUpdateModule')) {
            $carrier_id = (int)Tools::getValue('ORDERSTATUSUPDATE_CARRIER_ID');
            if (!$carrier_id || empty($carrier_id)) {
                $output .= $this->displayError($this->l('Transporteur ID invalide'));
            } else {
                Configuration::updateValue('ORDERSTATUSUPDATE_CARRIER_ID', $carrier_id);
                $output .= $this->displayConfirmation($this->l('Paramètres mis à jour'));
            }
        }

        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        // Récupération des transporteurs disponibles
        $carriers = Carrier::getCarriers((int)$this->context->language->id, true, false, false, null, PS_CARRIERS_ONLY);
        $carrier_options = array();

        foreach ($carriers as $carrier) {
            $carrier_options[] = array(
                'id_carrier' => (int)$carrier['id_carrier'],
                'name' => $carrier['name'],
            );
        }

        // Configuration du formulaire
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Paramètres'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Transporteur'),
                        'name' => 'ORDERSTATUSUPDATE_CARRIER_ID',
                        'options' => array(
                            'query' => $carrier_options,
                            'id' => 'id_carrier',
                            'name' => 'name',
                        ),
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Sauvegarder'),
                ),
            ),
        );

        $helper = $this->initHelperForm();
        $helper->fields_value = $this->getConfigFieldsValues();

        return $helper->generateForm(array($fields_form));
    }

    private function initHelperForm()
    {
        // Initialisation de l'helper pour le formulaire
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->show_toolbar = false;
        $helper->toolbar_scroll = false;
        $helper->submit_action = 'submitOrderStatusUpdateModule';
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Sauvegarder'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Retour à la liste')
            )
        );

        return $helper;
    }

    public function getConfigFieldsValues()
    {
        // Récupération des valeurs de configuration actuelles
        return array(
            'ORDERSTATUSUPDATE_CARRIER_ID' => (int)Configuration::get('ORDERSTATUSUPDATE_CARRIER_ID'),
        );
    }

    public function hookActionObjectOrderAddAfter($params)
    {
        // Mise à jour du statut de la commande lorsque le hook est déclenché
        $this->updateOrderStatus($params);
    }

    public function hookActionOrderHistoryAddAfter($params)
    {
        // Mise à jour du statut de la commande lorsque le hook est déclenché
        $this->updateOrderStatus($params);
    }

    public function hookActionOrderStatusPostUpdate($params)
    {
        // Mise à jour du statut de la commande lorsque le hook est déclenché
        $this->updateOrderStatus($params);
    }

    private function updateOrderStatus($params)
    {
        // Récupération des configurations
        $dropOnSiteDeliveryTypeId = (int)Configuration::get('ORDERSTATUSUPDATE_CARRIER_ID');
        $orderDeliveredStatusId   = (int)Configuration::get('PS_OS_DELIVERED');

        // Vérification des paramètres et mise à jour du statut de la commande
        if (isset($params['order_history']) && isset($params['cart'])) {
            $orderHistory = $params['order_history'];
            $order = new Order((int)$orderHistory->id_order);

            if ((int)$order->id_carrier === $dropOnSiteDeliveryTypeId && (int)$order->current_state !== $orderDeliveredStatusId) {
                $order->current_state = $orderDeliveredStatusId;

                $order_state = new OrderState($orderDeliveredStatusId, $order->id_lang);
                if (Validate::isLoadedObject($order_state)) {
                    $order->setCurrentState($orderDeliveredStatusId);
                    $order->save();

                    $history = new OrderHistory();
                    $history->id_order = (int)$order->id;
                    $history->id_employee = 0;
                    $history->changeIdOrderState($orderDeliveredStatusId, $order, true);
                    $history->addWithemail(true);
                }
            }
        }
    }
}
