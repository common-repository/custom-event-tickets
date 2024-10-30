<?php

/*
Plugin Name: Easy Custom Event Tickets
Description: Custom block Event Tickets RSVP, add participants list,duplicate event
Author: BLADOU Alain
Version: 2.1.1
Author URI: https://rouerguecreation.fr/
License:GPL v2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: custom-event-tickets
Domain Path: /languages
*/




/***********************************************************************************************************
*
*  Easy Custom Event Tickets [REV:2.1.1] 

*  1. On charge le fichier de traduction
*  2. On inclut les fichiers relatif a la page de réglages du plugin
*  3. Actions a réaliser si l'utilisateur choisi d'initialiser les paramètres du plugin
*  4. Actions a réaliser à l'activation du Plugin
*  4.1  Actions a réaliser aprés la mise à jour du plugin
*  5. Uninstall Plugin
*  6. Chargement feuille de style du plugin
*  7. chargement scripts du plugin
*	7.1 Chargement script pour le color picker
*	7.2 Chargement script pour le bouton retour vers le haut en admin
*	7.3 Chargement script pour une table responsive
*	7.4 Chargement script pour la mise a jour des champs selon des métas de publication sur l'écran Modification Rapide
*  8. Lien Paramètres pour le plugin sur la page des extensions 
*  9. Fonction Principale Pour Afficher La Table Des Participants
*  	9.1 Afficher le nom des participants & nbr de réservation par participant aprés le ticket RSVP
*  	9.2 Afficher le nom des participants & nbr de réservation par participant aprés le bloc Tickets
*  10. Afficher pour la vue photo le nom du lieu et la ville sous le titre de l'événement 
*  11. Ajout balise de catégorie a la vue photo
*  12. Ajout balise de catégorie a la vue liste
*  13. Fonction Principale Pour Afficher sur le calendrier des évènements en vue Liste & Photo le nbr de participant ET/OU nbr de billet restant
*  	13.1 Afficher sur le calendrier des évènements en vue Liste ou photo le nbr de participant & nbr de billet restant
*  14. Définir le chemin des modèles personnalisés pour The Events Calendar
*	14.1 chemin de remplacement du modèle pour l'info-bulle de la vue mois
*	14.2 chemin de remplacement du modèle pour la vue photo
*  15. Afficher un filtre de catégories pour les évènements dans la barre de recherche
*  16. CSS dynamique de la table des participants pour les blocs Gutenberg RSVP & Tickets
*  17. CSS Dynamique relatif aux versions Pro
*	17.1 Calendrier des évènements pro pour la vue liste
*	17.2 Calendrier des évènements pro pour la vue photo
*  18. CSS Dynamique relatif a la vue photo alternative établi par Tribe Ext Alternative Photo View
*************************************************************************************************************/



/* Empêche l'utilisateur public d'accéder directement aux fichiers .php via l'URL
et garantit que les fichiers du plugin seront exécutés uniquement dans l'environnement WordPress.*/
defined( 'ABSPATH' ) || die();


define( 'ECET_EASY_CUSTOM_EVENTS_TICKETS_URL', plugin_dir_url( __FILE__ ) );

// plugin_basename( __FILE__ ) donne le chemin relatif du fichier principal du plugin 
// par rapport au répertoire plugins de WordPress et sans le  slash de début et de fin soit:
// custom_event_tickets/easy-custom-event-tickets.php
define( 'ECET_PLUGIN_PATH_NAME', plugin_basename( __FILE__ ) );


/*----------------------------------------------------------------------------*
 *           1. On charge le fichier de traduction
 *----------------------------------------------------------------------------*/

add_action( 'init', 'ecet_settings_load_textdomain' );

/* On charge le fichier de traduction .mo,  ECET_PLUGIN_PATH_NAME ==> custom-event-tickets/easy-custom-event-tickets.php
   Paramètres:  le domaine , Obsolète par défaut false,a partir du réperoire plugins de WordPress Chemin relatif vers le répertoire de langue où réside le fichier .mo : easy-custom-event-tickets/languages */
function ecet_settings_load_textdomain() {
    load_plugin_textdomain( 'custom-event-tickets', FALSE, dirname( ECET_PLUGIN_PATH_NAME ) . '/languages' );
}


/*----------------------------------------------------------------------------*
 *     2. On inclut les fichiers relatif a la page de réglages du plugin
 *----------------------------------------------------------------------------*/

include 'inc/admin-menu.php';
include 'inc/multiple-settings.php';
include 'inc/ecet-duplicate-tec-event.php';
include 'inc/ecet-quick-edit.php';

/*---------------------------------------------------------------------------------------------------*
 *           3. Actions a réaliser si l'utilisateur choisi d'initialiser les paramètres du plugin
 *--------------------------------------------------------------------------------------------------*/


add_action("admin_init", function () {
	
	/* 
	 On récupère les réglages du plugin sous forme d'un tableau avec pour index:
	ecet_radio_default_setting_field: initialiser le plugin aux paramètres par défaut
	ecet_radio_delete_data_uninstallation_field: choix de supprimer les données lors de la désinstallation
	ecet_default_version_extension_events_radio_field: renseigner la version de l'extension: The Event Calendar 
	ecet_enable_participants_table_radio_field: activer la table des participants
	ecet_text_field: pour le titre de la table des participants
	ecet_select_title_tag_field : pour la balise HTML du titre
	ecet_select_column_field:  pour les nombre de colonne choisis pour la table des participants
	ecet_participant_name_format_radio_field: activer un format personnalisé pour le nom des participants afin d'afficher partiellement le nom
	ecet_color_line_field:  couleur de fond des lignes paires
	ecet_select_border_width_field : épaisseur bordure table
	ecet_color_border_field: couleur bordure
	ecet_font_size_field: taille police
	ecet_font_color_field: couleur police
	ecet_rsvp_button_text_field: pour le texte affiché sur le bouton du Block Gutenberg RSVP
	ecet_text_below_number_of_participants_text_field: pour le texte affiché en dessous du nbr de participant sur le block Gutenberg RSVP
	ecet_closed_reservation_text_field: pour le texte affiché RÉSERVATION CLÔTURÉ sur le block Gutenberg RSVP
	ecet_form_reservation_textarea_field: pour le texte affiché en intro du formulaire de réservation
	ecet_enable_custom_events_list_view_style_radio_field: activer le style personnalisé pour la vue liste
	ecet_event_number_participants_text_field: pour le texte affiché en préfixe du nombre de participants pour le calendrier des évènements en vue liste
	ecet_event_remaining_tickets_text_field: pour le texte affiché en préfixe du nombre de billets restant pour le calendrier des évènements en vue liste
	ecet_enable_list_view_category_tag_radio_field: activer une balise catégorie pour la vue liste
	ecet_enable_custom_events_photo_view_style_radio_field: activer le style personnalisé pour la vue photo du calendrier des évènements
	ecet_add_effect_hover_photo_view_event_radio_field: ajouter un effet au survol pour la vue photo de l'évènement
	ecet_moving_event_date_inside_photo_radio_field: déplacer la date de l'évènement a l'intérieur de la photo
	ecet_venue_details_photo_view_event_radio_field: ajouter le Nom du lieu & la ville pour la vue photo de l'évènement
	ecet_add_attendees_number_photo_view_event_radio_field: Ajouter le nombre de participants a la vue photo de l'évènement
	ecet_enable_photo_view_category_tag_radio_field: activer une balise catégorie pour la vue photo
	ecet_enable_events_alternative_photo_view_style_radio_field: activer la personnalisation de la vue photo établit par l'extension:Tribe Ext Alternative Photo View 
	ecet_enable_tooltip_customization_month_view_radio_field: activer la personnalisation de l'info-bulle pour la vue Mois
	*/
	$settings  = get_option( 'ecet_multiple_setting' );
	
	/*if ( empty( $settings ) ) {
		
	   //Paramètres :  option_name , option_value , Description (plus utilisé) , Autoload(charger l'option au démarrage de WordPress)
	   update_option( 'ecet_multiple_setting', $settings, '', 'yes' );
	   exit;
	   
	}*/
	
	// L'utilisateur a choisi sur la page de réglages d'initialiser les valeurs 
	// de réglages par défaut du plugin
	if ($settings['ecet_radio_default_setting_field'] == 'yes') {
	
		// Attention! le champ radio ne prend pas de valeur avec majuscule
		$settings['ecet_radio_default_setting_field'] = 'no';
		$settings['ecet_radio_delete_data_uninstallation_field'] = 'no';
		$settings['ecet_default_version_extension_events_radio_field'] = 'free';
		$settings['ecet_enable_participants_table_radio_field'] = 'yes';
		$settings['ecet_text_field'] = 'List of participants';
		$settings['ecet_select_title_tag_field'] = 'h3';
		$settings['ecet_select_column_field'] = '4';
		$settings['ecet_participant_name_format_radio_field'] = 'no';
		$settings['ecet_participant_responsive_tables_radio_field'] = 'no';
		$settings['ecet_color_line_field'] = '#EFF8FE';
		$settings['ecet_select_border_width_field'] = '1';
		$settings['ecet_color_border_field'] = '#e4e4e4';
		$settings['ecet_select_font_size_field'] = '18';
		$settings['ecet_font_color_field'] = '#1E2226';
		$settings['ecet_rsvp_button_text_field'] = 'BOOKING';
		$settings['ecet_text_below_number_of_participants_text_field'] = 'Booking';
		$settings['ecet_closed_reservation_text_field'] = 'BOOKING CLOSED';
		$settings['ecet_form_reservation_textarea_field'] = 'Please submit your BOOKING information, including the total number of guests.';
		$settings['ecet_enable_custom_events_list_view_style_radio_field'] = 'no';
		$settings['ecet_event_number_participants_text_field'] = 'number of participants:';
		$settings['ecet_event_remaining_tickets_text_field'] = 'remaining tickets:';
		$settings['ecet_enable_list_view_category_tag_radio_field'] = 'no';
		$settings['ecet_enable_custom_events_photo_view_style_radio_field'] = 'no';
		$settings['ecet_add_effect_hover_photo_view_event_radio_field'] = 'no';
		$settings['ecet_moving_event_date_inside_photo_radio_field'] = 'no';
		$settings['ecet_venue_details_photo_view_event_radio_field'] = 'no';
		$settings['ecet_add_attendees_number_photo_view_event_radio_field'] = 'no';
		$settings['ecet_enable_photo_view_category_tag_radio_field'] = 'no';
		$settings['ecet_enable_events_alternative_photo_view_style_radio_field'] = 'no';
		$settings['ecet_enable_tooltip_customization_month_view_radio_field'] = 'no';
		
		
		//Paramètres :  option_name , option_value , Description (plus utilisé) , Autoload(charger l'option au démarrage de WordPress)
	    update_option( 'ecet_multiple_setting', $settings, '', 'yes' );
		
	}
	
});

/*----------------------------------------------------------------------------*
 *           4. Actions a réaliser à l'activation du Plugin
 *----------------------------------------------------------------------------*/
 

/* A l'activation du plugin on ajoute dans la table wp_options pour le champ option_name = ecet_multiple_setting 
  les réglages par défaut du plugin sous forme d'un tableau avec pour index:
   ecet_radio_default_setting_field: initialiser le plugin aux paramètres par défaut
   ecet_radio_delete_data_uninstallation_field: choix de supprimer les données lors de la désinstallation
   ecet_default_version_extension_events_radio_field: renseigner la version de l'extension: The Event Calendar 
   ecet_enable_participants_table_radio_field: activer la table des participants
   ecet_text_field: pour le titre de la table des participants
   ecet_select_title_tag_field : pour la balise HTML du titre
   ecet_select_column_field:  pour les nombre de colonne choisis pour la table des participants
   ecet_participant_name_format_radio_field: activer un format personnalisé pour le nom des participants afin d'afficher partiellement le nom
   ecet_participant_responsive_tables_radio_field: activer une table responsive
   ecet_color_line_field:  couleur de fond des lignes paires
   ecet_select_border_width_field : épaisseur bordure table
   ecet_color_border_field: couleur bordure
   ecet_font_size_field: taille police
   ecet_font_color_field: couleur police
   ecet_rsvp_button_text_field: pour le texte affiché sur le bouton du Block Gutenberg RSVP
   ecet_text_below_number_of_participants_text_field: pour le texte affiché en dessous du nbr de participant sur le block Gutenberg RSVP
   ecet_closed_reservation_text_field: pour le texte affiché RÉSERVATION CLÔTURÉ sur le block Gutenberg RSVP
   ecet_form_reservation_textarea_field: pour le texte affiché en intro du formulaire de réservation
   ecet_enable_custom_events_list_view_style_radio_field: activer le style personnalisé pour la vue liste
   ecet_event_number_participants_text_field: pour le texte affiché en préfixe du nombre de participants pour le calendrier des évènements en vue liste
   ecet_event_remaining_tickets_text_field: pour le texte affiché en préfixe du nombre de billets restant pour le calendrier des évènements en vue liste
   ecet_enable_list_view_category_tag_radio_field: activer une balise catégorie pour la vue liste
   ecet_enable_custom_events_photo_view_style_radio_field: activer le style personnalisé pour la vue photo du calendrier des évènements
   ecet_add_effect_hover_photo_view_event_radio_field: ajouter un effet au survol pour la vue photo de l'évènement
   ecet_moving_event_date_inside_photo_radio_field: déplacer la date de l'évènement a l'intérieur de la photo
   ecet_venue_details_photo_view_event_radio_field: ajouter le Nom du lieu & la ville pour la vue photo de l'évènement
   ecet_add_attendees_number_photo_view_event_radio_field: Ajouter le nombre de participants a la vue photo de l'évènement
   ecet_enable_photo_view_category_tag_radio_field: activer une balise catégorie pour la vue photo
   ecet_enable_events_alternative_photo_view_style_radio_field: activer la personnalisation de la vue photo établit par l'extension:Tribe Ext Alternative Photo View 
   ecet_enable_tooltip_customization_month_view_radio_field: activer la personnalisation de l'info-bulle pour la vue Mois
 */
 
function ecet_easy_custom_event_tickets_activate() {
	
   $settings = array();
   
   $settings  = get_option( 'ecet_multiple_setting' );
   
   // on n'initialise pas le plugin aux valeurs de réglages par défaut 
   // si l'utilisateur a déjà paramétré le plugin
   // cas ou l'utilisateur a désactivé puis réactivé le plugin
   if ( empty( $settings ) ) {
	   
	   // Attention! le champ radio ne prend pas de valeur avec majuscule
	   $settings['ecet_radio_default_setting_field'] = 'no';
	   $settings['ecet_radio_delete_data_uninstallation_field'] = 'no';
	   $settings['ecet_default_version_extension_events_radio_field'] = 'free';
	   $settings['ecet_enable_participants_table_radio_field'] = 'yes';
	   $settings['ecet_text_field'] = 'List of participants';
	   $settings['ecet_select_title_tag_field'] = 'h3';
	   $settings['ecet_select_column_field'] = '4';
	   $settings['ecet_participant_name_format_radio_field'] = 'no';
	   $settings['ecet_participant_responsive_tables_radio_field'] = 'no';
	   $settings['ecet_color_line_field'] = '#EFF8FE';
	   $settings['ecet_select_border_width_field'] = '1';
	   $settings['ecet_color_border_field'] = '#e4e4e4';
	   $settings['ecet_select_font_size_field'] = '18';
	   $settings['ecet_font_color_field'] = '#1E2226';
	   $settings['ecet_rsvp_button_text_field'] = 'BOOKING';
	   $settings['ecet_text_below_number_of_participants_text_field'] = 'Booking';
	   $settings['ecet_closed_reservation_text_field'] = 'BOOKING CLOSED';
	   $settings['ecet_form_reservation_textarea_field'] = 'Please submit your BOOKING information, including the total number of guests.';
	   $settings['ecet_enable_custom_events_list_view_style_radio_field'] = 'no';
	   $settings['ecet_event_number_participants_text_field'] = 'number of participants:';
	   $settings['ecet_event_remaining_tickets_text_field'] = 'remaining tickets:';
	   $settings['ecet_enable_list_view_category_tag_radio_field'] = 'no';
	   $settings['ecet_enable_custom_events_photo_view_style_radio_field'] = 'no';
	   $settings['ecet_add_effect_hover_photo_view_event_radio_field'] = 'no';
	   $settings['ecet_moving_event_date_inside_photo_radio_field'] = 'no';
	   $settings['ecet_venue_details_photo_view_event_radio_field'] = 'no';
	   $settings['ecet_add_attendees_number_photo_view_event_radio_field'] = 'no';
	   $settings['ecet_enable_photo_view_category_tag_radio_field'] = 'no';
	   $settings['ecet_enable_events_alternative_photo_view_style_radio_field'] = 'no';
	   $settings['ecet_enable_tooltip_customization_month_view_radio_field'] = 'no';
	   
	   //Paramètres :  option_name , option_value , Description (plus utilisé) , Autoload(charger l'option au démarrage de WordPress)
	   add_option( 'ecet_multiple_setting', $settings, '', 'yes' );
   }
   
}
register_activation_hook( __FILE__, 'ecet_easy_custom_event_tickets_activate' ); 

/*----------------------------------------------------------------------------*
 *          4.1  Actions a réaliser aprés la mise à jour du plugin
 *----------------------------------------------------------------------------*/
 
 
// on utilise le hook  'upgrader_process_complete' qui se déclenche lorsque
// le processus de mise à jour est terminé
// Cette méthode sera appelée lors de l'exécution de la version actuelle de ce plugin, 
// pas de la nouvelle qui vient d'être mise à jour.
// Par exemple: vous exécutez la version 1.0 et venez de passer à la version 2.0. 
// La version 2.0 ne fonctionnera pas encore ici mais la 1.0 fonctionne.
// Donc attention!, aucun code ici ne fonctionnera pour la nouvelle version.  
// Cette action ajoutera une valeur transitoire pour dire que le plugin à été mis à jour
// c'est avec l'action plugins_loaded que l'on va mettre a niveau la base de données du plugin

add_action( 'upgrader_process_complete', 'ecet_upgrade',10, 2);
 
// WP_Upgrader: Classe principale utilisée pour la mise à niveau ou l'installation 
function ecet_upgrade(\WP_Upgrader $upgrader_object, $options ) {
 
   // si le processus en cours est une mise à jour de plugins
   if ( $options['action'] == 'update' && $options['type'] == 'plugin' ) {
	   
       // $options['plugins'] est un tableau des chemins relatif uniquement des plugins mis à jour
	   foreach( $options['plugins'] as  $index => $plugin ) {
		   
          if ( $plugin == ECET_PLUGIN_PATH_NAME ) {
			  
				// ajout d'une valeur transitoire dans la table options
				// pour spécifier que le plugin à été mis à jour
				set_transient('ecet_plugin_updated', 1);
				
				break;
 
          }
		  
       }
	   
	   unset($index, $plugin);  
	 
    }
	
}


// on effectue la mise à niveau de la base de données
// si la valeur transitoire ecet_plugin_updated qui indique qu'une mise a jour
// du plugin a été effectué existe et si l'utilisateur est admin.

add_action('plugins_loaded', 'ecet_updated_plugin');

function ecet_updated_plugin()	{
	
	$settings = array();
	
	// si le plugin à été mis à jour et que l'utilisateur actuel est admin
	if ( get_transient('ecet_plugin_updated') && current_user_can('manage_options') ) {
		
		// on récupère les paramètres de réglages renseignés par l'utilisateur
		// pour la version antérieure du plugin
		$settings  = get_option( 'ecet_multiple_setting' );

		// On renseigne les paramètres de réglages 
		// introduit par cette mise à jour à la version 2.1.1
		//$settings['ecet_enable_participants_table_radio_field'] = 'yes';
		
		// On met a jour la table option avec notamment ce nouveau réglage
		//Paramètres :  option_name , option_value , Description (plus utilisé) , Autoload(charger l'option au démarrage de WordPress)
		update_option( 'ecet_multiple_setting', $settings, '', 'yes' );
		
		// On supprime la valeur transitoire
		delete_transient('ecet_plugin_updated');
	
	}

}



/*----------------------------------------------------------------------------*
 *                      5. Uninstall Plugin
 *----------------------------------------------------------------------------*/
 
 
 
// a la désinstallation du plugin on supprime les réglages du plugin dans la table wp_options
function ecet_easy_custom_event_tickets_uninstall() {
   delete_option( 'ecet_multiple_setting' ); 
}
// on récupère les paramètres de réglages renseignés par l'utilisateur
$settings  = get_option( 'ecet_multiple_setting' );

if ($settings['ecet_radio_delete_data_uninstallation_field'] == 'yes') {
	register_uninstall_hook( __FILE__, 'ecet_easy_custom_event_tickets_uninstall' );
}

/*---------------------------------------------------------------------------------------*
 *                  6. Chargement feuille de style du plugin
 *--------------------------------------------------------------------------------------*/

// On récupère dans la table wp_options pour le champ option_name = ecet_multiple_setting 
// un tableau qui comporte les valeurs de réglages du plugin renseignés 
// dans les champs correspondants de la page de réglage: ECET Réglage
$settings  = get_option( 'ecet_multiple_setting' );
	
	
// On charge la feuille de style du plugin
// pour bien déclarer le style on utilise le hook: wp_enqueue_scripts
// le tableau array() indique les dépendances autrement dit indiquer à 
// WordPress que notre style devrait absolument être chargé après les styles indiqués en dépendances 
// le numéro de version est important car il va permettre d’invalider le cache du navigateur 
// lorsqu'on fera évoluer le style.
// par défaut le dernier paramètre est à false et le script est chargé dans le head
function ecet_chargement_style() {
	
	
	wp_enqueue_style( 
		'ecet_chargement_style', 
		ECET_EASY_CUSTOM_EVENTS_TICKETS_URL . 'css/easy-custom-event-tickets.css',
		array(), 
		'2.1.1'
	);
	
}

add_action( 'wp_enqueue_scripts', 'ecet_chargement_style','15' );

	
// chargement feuille de style pour les pages de réglages du plugin en back office
function ecet_custom_admin_styling() {
		
		wp_enqueue_style( 
			'ecet-admin-style', 
			ECET_EASY_CUSTOM_EVENTS_TICKETS_URL . 'css/ecet-admin-style.css',
			array(), 
			'2.1.1'
		);
		
}

add_action( 'admin_enqueue_scripts', 'ecet_custom_admin_styling','11' );


/*---------------------------------------------------------------------------------------*
 *                  7. chargement scripts du plugin
 *--------------------------------------------------------------------------------------*/


/*---- Chargement script pour le color picker -----*/

// Mettre en file d'attente le script et le style wp-color-picker 
// le fichier javascript dans lequel on écrit ces lignes se trouve dans le sous-dossier js du plugin
// On utilise donc pour l'URL du script: ECET_EASY_CUSTOM_EVENTS_TICKETS_URL . 'js/ecet-script.js' => plugin_dir_url( __FILE__ ) . 'js/ecet-script.js'  
// car cette fonction de chargement de script est dans le fichier principal du plugin
// si cette fonction était dans un sous dossier du plugin l'URL du script aurait été:
// plugins_url('js/eccp-script.js', dirname(__FILE__) )
// Mon plugin a maintenant un ecet-script.js fichier qui a été déclaré en tant que dépendance de wp-color-picker
add_action( 'admin_enqueue_scripts', 'ecet_enqueue_color_picker' );

function ecet_enqueue_color_picker( $hook_suffix ) {
    
	/*---- Chargement style & script pour le color picker -----*/
    wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	// paramètre: handle,URL script, dépendance, Version , True: script inséré dans le footer
    wp_enqueue_script( 'ecet-color-picker-script-handle', esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL) . 'js/ecet-color-picker.js', array( 'wp-color-picker' ), false, true );

}


/*---- Chargement script pour le bouton retour vers le haut en admin -----*/

// cache le bouton retour vers le haut en position haute < 500
// apparition du bouton retour vers le haut avec un fading pour une position > 500
// permet un défilement fluide vers le haut

add_action('admin_enqueue_scripts', 'ecet_enqueue_scroll_to_top');

function ecet_enqueue_scroll_to_top() {
	
	// chargement script seulement sur la page de réglages du plugin
   if (isset($_GET['page']) && $_GET['page'] == 'ecet_settings_page') {
	
		// wp_enqueue_script : met en file d'attente notre script
		// paramètre: handle,URL script, dépendance, Version , True: script inséré dans le footer
		wp_enqueue_script( 'ecet-scroll-to-top', esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL) . 'js/ecet-scroll-to-top.js', array('jquery'), false, true );
		
	
	}
	
}


/*---- Chargement script pour une table responsive -----*/

if( $settings['ecet_participant_responsive_tables_radio_field'] == 'yes' ) {
	// script pour des tables responsives
	add_action('wp_enqueue_scripts', 'ecet_responsive_tables');
}

function ecet_responsive_tables() {
	
		// wp_enqueue_script : met en file d'attente notre script
		// paramètre: handle,URL script, dépendance, Version , True: script inséré dans le footer
		wp_enqueue_script( 'ecet-responsive-tables', esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL) . 'js/ecet-responsive-tables.js', array('jquery'), false, true );
	
}

/*---- Chargement script pour la mise a jour des champs selon des métas de publication sur l'écran Modification Rapide -----*/

// script pour mettre a jour les champs date de l'edition rapide
// en fonction de la valeur des métas de publication:
// _EventStartDate & _EventEndDate
// affichées dans les lignes de publications pour l'écran qui listent 
// les évènements
add_action('admin_enqueue_scripts', 'ecet_update_fields_quick_edit');

function ecet_update_fields_quick_edit() {
	
		// wp_enqueue_script : met en file d'attente notre script
		// paramètre: handle,URL script, dépendance, Version , True: script inséré dans le footer
		wp_enqueue_script( 'ecet-update-fields-quick-edit', esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL) . 'js/ecet-update-fields-quick-edit.js', array('jquery'), false, true );
	
}

/*---------------------------------------------------------------------------------------*
 *      8.  Lien Paramètres pour le plugin sur la page des extensions 
 *--------------------------------------------------------------------------------------*/
 
function ecet_plugin_settings_link( $links ) : array {
	
    $label_settings = esc_html__( 'Settings', 'custom-event-tickets' );
	$label_demo = esc_html__('Demo');
    $slug  = esc_html__('ecet_settings_page');

	// on utilise la fonction array_unshift() pour mettre les liens Settings & Demo
	// avant celui de Désactiver
    array_unshift( $links, 
	               "<a href='options-general.php?page=$slug'>$label_settings</a> |
				    <a href='https://rouerguecreation.fr/demos/' target='_blank'>$label_demo</a>" 
				 );

    return $links;
	
}

// le hook de base est plugin_action_links actif pour tous les plugins installés
// pour éviter une vérification conditionnelle du plugin
// on ajoute simplement le chemin relatif du plugin au hook.
// soit: plugin_action_links_editor-custom-color-palette/editor-custom-color-palette.php
add_action( 'plugin_action_links_' . ECET_PLUGIN_PATH_NAME, 'ecet_plugin_settings_link', 10 );



/*----------------------------------------------------------------------------------------------------*
 *   9. Fonction Principale Pour Afficher La Table Des Participants
 *----------------------------------------------------------------------------------------------------*/
	
function ecet_afficher_table_participant() {

	$all_attendees = array();
	$participant = array();
	$reservation = array();
	$index = 0;
	$nbr_reservation = 0;
	$nbr_participant = 0;
	
	// On récupère dans la table wp_options pour le champ option_name = ecet_multiple_setting un tableau qui comporte
	// les valeurs de réglages du plugin renseignés dans les champs correspondants de la page de réglage: ECET Réglages
	// le tableau ayant pour index:
	// ecet_text_field: pour le titre de la table des participants
	// ecet_select_column_field:  pour les nombre de colonne choisis pour la table des participants
	$settings  = get_option( 'ecet_multiple_setting' );
	//print_r($settings); '<br>';
	
	// ID de l'évènement
	$event_id = get_the_ID();

	// On récupère le nombre de participant a l'èvènement
	// get_event_attendees_count() étant une méthode statique on n'a pas besoin
	// d'instancier la classe
	$nbr_participant = Tribe__Tickets__Tickets::get_event_attendees_count($event_id);
	
	// s'il n'ya pas de participant
	// on n'affiche rien on sort
	if ($nbr_participant == 0){
		return;
	}
	
	// On récupère un tableau multidimensionnel contennant tous les paramètres
	// des participants: Nom, E-mail, Nom du billet,etc..
	$all_attendees = Tribe__Tickets__Tickets::get_event_attendees($event_id);
	
	/* On récupère un tableau indexé numériquement contenant le nom des participants avec doublons
	   array_column() permet de filter le tableau multidimensionnel $all_attendees
	   selon la colonne purchaser_name et retourne le résultat dans 
	   un simple tableau indexé numériquement
		Array(
			[0] => Jerome Macouin
			[1] => Fabrice Neauleau
			[2] => Fabrice Neauleau
			[3] => Dominique Lemoucheux
			[4] => Dominique Lemoucheux
			[5] => Dominique Lemoucheux
			[6] => Thierry Barré
			[7] => Franck Josselin
			[8] => Christophe Gosselin
			[9] => Nathalie Lejeune
			[10] => Alain Bladou
		)
	*/
	$duplicate_attendees = array_column($all_attendees, 'purchaser_name');
	
	/*	 array_count_values() retourne un tableau associatif des participants sans doublons 
		 avec le nom des participants et pour valeur le nombre de réservation par participant
		 Array(
			[Jerome Macouin] => 1
			[Fabrice Neauleau] => 2
			[Dominique Lemoucheux] => 3
			[Thierry Barré] => 1
			[Franck Josselin] => 1
			[Christophe Gosselin] => 1
			[Nathalie Lejeune] => 1
			[Alain Bladou] => 1
		)
	*/
	$attendees = array_count_values($duplicate_attendees);
	
	$nbr_reservation = count($attendees);
	
	$index_liste = 0;
	
	// on re-indexe numériquement le tableau des participants
	foreach( $attendees as  $key => $value ) {
		
		$reservation[$index_liste]['nom'] = $key;
		$reservation[$index_liste]['nbr_participant'] = $value;
		$index_liste++;
	}
	
	// tri du tableau multidimensionnel dans l'ordre croissant selon la colonne 'nom'
	// le tableau est "re-indexé" à partir de 0
	// option: SORT NATURAL pour tenir compte des participants 
	// qui renseigne aussi leur prénom ;
	// option: SORT_FLAG_CASE pour ne pas tenir compte de la casse
	array_multisort( array_column($reservation, 'nom'), SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE, $reservation );
	
	
	/* Pour Debug 
	echo'<pre>';
		echo'<strong>ID Évènement = </strong>'.$event_id.'<br>';
		echo'<strong>Nombre Participants: </strong>'.$nbr_participant.'<br>';
		//echo'<strong> Tableau des participants avec tous les paramètres: </strong>'.'<br>';
		//print_r($all_attendees);
		echo'<strong>Tableau des noms des participants avec doublon: </strong>'.'<br>';
		print_r($duplicate_attendees).'<br>';
		echo'<strong>Tableau avec le nbr de réservation par participants: </strong>'.'<br>';
		print_r($attendees).'<br>';
		echo'<strong>Tableau participants re-indexé numériquement: </strong>'.'<br>';
		print_r($reservation).'<br>';
	echo'</pre>';
	*/
	
	
	
	/* ----- --- si on a activé le format personnalisé pour le nom des participants	--------*/	

	
	if($settings['ecet_participant_name_format_radio_field'] == 'yes') {
	
		// on formate Prénom + Nom des participant avec 
		// Prénom + deux premières lettre du Nom + un point
		for ( $index=0;$index <=$nbr_reservation - 1;$index++ ) { 
			
		
			// on supprime les espaces en début et fin de chaine
			$reservation[$index]['nom'] = trim($reservation[$index]['nom']);
			// position de l'espace qui sépare le Nom du Prénom
			// le 1er caractère ayant la position zéro
			$pos_space = strpos($reservation[$index]['nom'],' ');
			

			// s'il y a un espace qui sépare le Prénom du Nom
			// sinon cas de figure ou l'utilisateur n'a renseigné que le Nom
			// alors on n'affiche pas le format personnalisé
			if($pos_space) {
				
				// strstr() retourne une sous-chaine de la 1ère occurence de l'espace jusqu'a
				// la fin de la chaine(si jamais il y a plusieurs espaces qui séparent le Prénom 
				// du Nom alors on les supprime avec ltrim)	
				$name = ltrim(strstr($reservation[$index]['nom'],' '));
				// substr() permet d'extraire une sous chaine a partir de la position 0
				// et de longueur = $pos_space 
				$first_name= substr($reservation[$index]['nom'],0,$pos_space);
				// on retourne les initiales du nom
				// on utilise mb_substr au lieu de substr sinon problème
				// d'encodage des caractères spéciaux				
				$name = mb_substr($name,0,2).'.';
				
				
				$reservation[$index]['nom'] = $first_name.' '.$name;
			
			}
			
		}
		
	}
		
		
	// On affiche le titre de la table des participants encadré 
	// par sa balise HTML récupérés en base de données
	// selon les valeurs entrées pour les champs texte & select correspondant 
	// de la page de réglage: ECET Réglages
	if ( !empty( $settings['ecet_text_field'] ) ) {
	
		// si on a renseigné une balise HTML
		if ( !empty( $settings['ecet_select_title_tag_field'] ) ) {
			
			echo '<'.$settings['ecet_select_title_tag_field'].'>'. htmlspecialchars_decode( esc_html__( $settings['ecet_text_field'],'custom-event-tickets' ) ).'</'.$settings['ecet_select_title_tag_field'].'>';
		
		// si on n'a pas renseigné de balise HTML on encadre par défaut le titre par une balise de paragraphe
		}else{
			echo '<p>'.$settings['ecet_text_field'].'</p>';
		}
	}
		
		
	// On affiche le nombre de participants
	echo esc_html__('Attendees number: ','custom-event-tickets').$nbr_participant;
	
	
	$index_liste=0;
	
	// On récupère le nombre de colonne sélectionnée dans la page de réglages du plugin
	$nbr_col = intval( $settings['ecet_select_column_field'] );
				
	// nbr de ligne du tableau arrondi à l'entier supérieur
	$nbr_ligne_tab = ceil($nbr_reservation/$nbr_col);
	
	
	// Affichage de la table des participants
	echo '<table class="table-participant">';
	
		echo'<tbody>';
	
			for ( $index_ligne=1;$index_ligne<=$nbr_ligne_tab;$index_ligne++ ) { 
			
				echo '<tr>';
				
					for ( $index_colonne=1;$index_colonne<=$nbr_col ;$index_colonne++ ) {
						
						echo '<td>'				
						.$reservation[$index_liste]['nom']
						.' ('.$reservation[$index_liste]['nbr_participant'].')'
						.'</td>';
						
						$index_liste++ ; 
						
						// l'affichage de la table est terminée si la condition est vrai
						if ( $index_liste > $nbr_reservation - 1 ){
							$index_colonne = $nbr_col + 1;
						} 
						
					}
				
				echo '</tr>';
			}
	
		echo'</tbody>';
		
	echo '</table>';
	

}


/*----------------------------------------------------------------------------------------------------*
 *   9.1 Afficher le nom des participants & nbr de réservation par participant aprés le ticket RSVP
 *----------------------------------------------------------------------------------------------------*/
	
	function ecet_afficher_liste_participant_rsvp() {
		ecet_afficher_table_participant();
	}
	
	// si on a activé la table des participants
	if ( $settings['ecet_enable_participants_table_radio_field'] == 'yes') {
		add_action( 'tribe_template_after_include:tickets/v2/rsvp', 'ecet_afficher_liste_participant_rsvp',10,3 );
	}

/*--------------------------------------------------------------------------------------------------*
 *    9.2 Afficher le nom des participants & nbr de réservation par participant aprés le bloc Tickets
 *--------------------------------------------------------------------------------------------------*/
	
	function ecet_afficher_liste_participant() {
		
		// ID de l'évènement 
		$event_id = get_the_ID() ;
		
		/*
		Tableau retourné par la méthode get_ticket_counts()
				Array
		(
			[rsvp] => Array
				(
					[count] => 0
					[stock] => 0
					[unlimited] => 0
					[available] => 0
				)

			[tickets] => Array
				(
					[count] => 2
					[stock] => 133
					[global] => 0
					[unlimited] => 0
					[available] => 117
				)

		)
		*/
		$all_tickets = Tribe__Tickets__Tickets::get_ticket_counts($event_id);
		
		$has_ticket_RSVP = intval($all_tickets['rsvp']['count']);
		
		// si on a activé la table des participants et qu'il n'y a pas de ticket RSVP
		// on affiche la table car sinon doublon avec l'affichage de la table
		// sous le ticket RSVP
		if (!$has_ticket_RSVP) {
			ecet_afficher_table_participant();
		}
		
	}

	// si on a activé la table des participants 
	if ( $settings['ecet_enable_participants_table_radio_field'] == 'yes' ) {
		// modif version 2.0.1 
		//tribe_template_after_include:tickets/blocks/tickets => tickets/v2/tickets
		add_action( 'tribe_template_after_include:tickets/v2/tickets', 'ecet_afficher_liste_participant',10,3 );
	}
	
	
/*------------------------------------------------------------------------------------------*
 * 10.  Afficher pour la vue photo le nom du lieu et la ville sous le titre de l'événement
 *------------------------------------------------------------------------------------------*/
	
	
	function ecet_insert_venue_details_photo_view() {
		
		// toutes les fonctions pour le lieu de l'èvènement sont dans le fichier:
		// the-events-calendar\src\functions\template-tags\venue.php
		// Exemple de retour pour la fonction tribe_get_venue_single_line_address: 
		// La Ferme Du Four, DIGOSVILLE, 50110
		// tribe_get_address: le nom de la rue ex: Rue de la Ferme du Four
		// etc..
		
		// ID de l'évènement
		$event_id = get_the_ID();
		
		if ( !tribe_address_exists($event_id) ) {
			return;
		}
		
		// Nom du lieu de l'évènement ex: La Ferme Du Four 
		$venue = tribe_get_venue($event_id);
		
		// Ville de l'évènement
		$city = tribe_get_city($event_id);
		
		// on Affiche le lieu et la ville de l'évènement
		echo '<div class="tribe-common-b3 ecet-venue-details">'  .'<strong>' . $venue .'</strong>' . ' ' . $city . '</div>';
		
	}

	if( $settings['ecet_venue_details_photo_view_event_radio_field'] == 'yes') {
		add_action( 'tribe_template_after_include:events-pro/v2/photo/event/title', 'ecet_insert_venue_details_photo_view' );
	}	
	

/*-----------------------------------------------------------------------------------------------------------------------*
 *  11. Ajout balise de catégorie a la vue photo
 *-----------------------------------------------------------------------------------------------------------------------*/	

	if($settings['ecet_enable_photo_view_category_tag_radio_field'] == 'yes') {
		add_action( 'tribe_template_after_include:events-pro/v2/photo/event/title', 'ecet_list_catgories_after_title_event_view_photo' );
	}
	
	function ecet_list_catgories_after_title_event_view_photo() {
		
		$event_id = ecet_get_event_pro_id();
		
		?>
		<ul class='tribe-event-categories'>
			<?php echo tribe_get_event_taxonomy( $event_id  ); ?>
		</ul>
		<?php
	} 


/*-----------------------------------------------------------------------------------------------------------------------*
 *  12. Ajout balise de catégorie a la vue liste
 *-----------------------------------------------------------------------------------------------------------------------*/	
	
    if ($settings['ecet_enable_list_view_category_tag_radio_field'] == 'yes') {
		add_action( 'tribe_template_before_include:events/v2/list/event/venue', 'ecet_list_catgories_after_title_event_view_list');
	}
	
	function ecet_list_catgories_after_title_event_view_list() {
		
		global $post;
		?>
		<ul class='tribe-event-categories'>
			<?php echo tribe_get_event_taxonomy( $post->ID ); ?>
		</ul>
		<?php
	} 	
	
	
/*-----------------------------------------------------------------------------------------------------------------------------------------------*
 *  13. Fonction Principale Pour Afficher sur le calendrier des évènements en vue Liste & Photo le nbr de participant ET/OU nbr de billet restant 
 *-----------------------------------------------------------------------------------------------------------------------------------------------*/	


/*
	retourne l'ID d'un événement en particulier pour la vue photo
	The Evens Calendar Pro car bizarrement la fonction
	get_the_ID() ne retourne pas la vrai valeur de l'ID pour 
	l'évènement en cours
*/
function ecet_get_event_pro_id(){
	
	//la classe wpdb est toujours instanciée au chargement de l’application 
	//et stockée dans une variable globale appelée $wpdb
	global $wpdb;
	
	// permet d'afficher les erreurs
	$wpdb->show_errors();
	
	// on récupère l'objet évènement en cours 
	$event = tribe_get_event( get_the_ID() );
	
	// on récupère le titre de l'évènement en cours
	$post_title = $event->post_title;
	
	// pour debug
	//echo'titre: '.$post_title;
	
	// la valeur du champ post_type d'un évènement gratuit ou payant
	// pour la table wp_posts
	$post_type = 'tribe_events';
	
	/*$requete = "SELECT ID 
	  FROM {$wpdb->prefix}posts
	  WHERE post_title = $post_title  
	  AND post_type = $post_type ";*/


	// on récupère l'ID de l'évènement en cours:
	// en fonction de son titre et du champ post_type = 'tribe_events'
	// car bizarement $event_id = get_the_ID()  ne permet pas d'obtenir
	// l'id de l'évènement en cours pour la vue photo lorsque 
	// The Event Calendar Pro est activé
	$requete = $wpdb->prepare( "SELECT ID 
								FROM {$wpdb->prefix}posts
								WHERE post_title = %s " , $post_title  );
					
	$requete = $wpdb->prepare( $requete. " AND post_type = %s" , $post_type  );			
					
	$event_id  = $wpdb->get_var($requete);
	
	// pour debug
	//echo'ID événement: '.$event_id.'<br>';
	
	return $event_id;
	
}
	

function ecet_nbr_attendee_view_list_photo_events() {
	
	// on récupère les paramètres de la page de réglages
	$settings  = get_option( 'ecet_multiple_setting' );	
	
	// ID de l'évènement pour The Events Calendar version free
	$event_id = get_the_ID() ;
	
	// si on a la version pro de l'extension The Events Calendar 
	if ($settings['ecet_default_version_extension_events_radio_field'] == 'pro') {
		$event_id = ecet_get_event_pro_id();
	}
	
	/*
	Tableau retourné par la méthode get_ticket_counts()
			Array
	(
		[rsvp] => Array
			(
				[count] => 0
				[stock] => 0
				[unlimited] => 0
				[available] => 0
			)

		[tickets] => Array
			(
				[count] => 2
				[stock] => 133
				[global] => 0
				[unlimited] => 0
				[available] => 117
			)

	)
	*/
	$all_tickets = Tribe__Tickets__Tickets::get_ticket_counts($event_id);
	
	
	$nbr_tickets = intval($all_tickets['rsvp']['count']) + intval($all_tickets['tickets']['count']);
	
	
	// s'il n'y a pas de tickets on sort
	if ( $nbr_tickets == 0){
		return;
	}
	
	$nbr_billet_restant = intval($all_tickets['rsvp']['stock']) + intval($all_tickets['tickets']['stock']);

	/* pour debug 
	echo'<pre>';
		echo'ID évènement: '.$event_id.'<br>';
		echo'Toutes les infos des billets a vendre: '.'<br>';
		print_r($all_tickets).'<br>';
	echo'</pre>';
	*/
	
	// Nombre de participants
	// get_event_attendees_count() étant une méthode statique on n'a pas besoin
	// d'instancier la classe
	$nbr_attendee = Tribe__Tickets__Tickets::get_event_attendees_count($event_id);

	// si on a la version free de l'extension The Events Calendar 
	if ($settings['ecet_default_version_extension_events_radio_field'] == 'free') {
		
		// si on a des participants on affiche leur nombre
		if ($nbr_attendee) {
			// autre classe possible: tribe-common-b2 pour une police de 14px		
			// on Affiche le nombre de participants
			echo '<span class="tribe-common-h7">' . esc_html__( $settings['ecet_event_number_participants_text_field'],'custom-event-tickets' ) . ' ' . $nbr_attendee . '</span><br>';
		}
		
		// on Affiche le nombre de billets restant
		echo '<span class="tribe-common-h7">' . esc_html__( $settings['ecet_event_remaining_tickets_text_field'],'custom-event-tickets' ) . ' ' . $nbr_billet_restant . '</span>';
	
	// sinon c'est la version pro
	// on n'affiche que le nombre de participants
	}else {
		
		// si on a des participants on affiche leur nombre
		if ($nbr_attendee) {
			echo '<span class="tribe-common-b2 ecet-attendees-number">' . esc_html__( $settings['ecet_event_number_participants_text_field'],'custom-event-tickets' ) . ' ' . $nbr_attendee . '</span><br>';
		}
	}
	
}
	
/*---------------------------------------------------------------------------------------------------------------------------*
 *  13.1 Afficher sur le calendrier des évènements en vue Liste ou photo le nbr de participant & nbr de billet restant 
 *---------------------------------------------------------------------------------------------------------------------------*/
	
	function ecet_nbr_participant_vue_liste_photo() {
		ecet_nbr_attendee_view_list_photo_events();
	}
	
	// si on a la version free de l'extension The Events Calendar
	if ($settings['ecet_default_version_extension_events_radio_field'] == 'free') {
		
		// si on a activé la personnalisation de la vue Liste
		if( $settings['ecet_enable_custom_events_list_view_style_radio_field'] == 'yes'){
			// Vue Liste
			add_action( 'tribe_template_after_include:events/v2/list/event/description', 'ecet_nbr_participant_vue_liste_photo');
		}
	}
	
	// si on a la version pro de l'extension The Events Calendar 
	if ($settings['ecet_default_version_extension_events_radio_field'] == 'pro') {
		
		// si on activé l'ajout du nombre de participant
		if($settings['ecet_add_attendees_number_photo_view_event_radio_field'] == 'yes') {
			// Vue Photo
			add_action( 'tribe_template_after_include:events-pro/v2/photo/event/title', 'ecet_nbr_participant_vue_liste_photo');
		}
		
		// si on a activé la personnalisation de la vue Liste
		if( $settings['ecet_enable_custom_events_list_view_style_radio_field'] == 'yes'){
			// Vue Liste
			add_action( 'tribe_template_after_include:events/v2/list/event/description', 'ecet_nbr_participant_vue_liste_photo' );
		}
		
	}
	
/*------------------------------------------------------------------------------------------*
 * 14. Définir le chemin des modèles personnalisés pour The Events Calendar
 *------------------------------------------------------------------------------------------*/


/**
 * 
 * chemin de remplacement du modèle pour l'info-bulle de la vue mois.
 *
 * Donc, par exemple si l'emplacement du calendrier des événements pour le titre 
 * de la vue tooltip(info-bulle)est:
 * wp-content/plugins/the-events-calendar/src/views/v2/month/calendar-body/day/calendar-events/calendar-event/tooltip/title.php
 *
 * Alors l'emplacement de remplacement du plugin ECET est:
 * /wp-content/plugins/easy-custom-event-tickets/tribe-customizations-tooltip/v2/month/calendar-body/day/calendar-events/calendar-event/tooltip/title.php
 *
 * Référence: https://theeventscalendar.com/knowledgebase/k/custom-additional-template-locations/
 *			  https://wordpress.org/plugins/display-event-locations-tec/
 *
 * @param array $folders:  Tableau de données pour les emplacements de chargement.
 *
 * @return array
 */	
function ecet_tribe_custom_template_paths_v2_view_tooltip( $folders ) {
	
		/**
		 * Quel ordre de priorité pour charger nos fichiers modèle a partir du plugin. 
		 * pour Events Pro = 25 , The Events Calendar = 20 , Event Tickets = 17
		 */
		$priority = 5;

		// Dossier du plugin ou sont stockés les modèles personnalisés
		$custom_folder = 'tribe-customizations-tooltip/v2';
		
		// trailingslashit() permet d'ajouter un slash en fin du chemin du plugin
		$plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) ).$custom_folder ;

		/*
		 * Emplacement de chargement personnalisé pour écraser 
		 * le chargement du fichier modèle initial The Events Calendar.
		 */
		$folders[ 'ecet_tooltip' ] = [
			'id'        => 'ecet_tooltip',
			//'namespace' => $plugin_name,  Ne définissez ceci que si vous souhaitez écraser l'espace de noms de thème
			'priority'  => $priority,
			'path'      => $plugin_path,
		];

		return $folders;
	
}

// si on a activé la personnalisation de l'info-bulle pour la vue mois
if( $settings['ecet_enable_tooltip_customization_month_view_radio_field'] == 'yes'){
	add_filter( 'tribe_template_theme_path_list', 'ecet_tribe_custom_template_paths_v2_view_tooltip', 10, 1 );
}


/**
 * 
 * chemin de remplacement du modèle pour la vue photo.
 *
 * On remplace deux modèles
 * wp-content/plugins/the-events-calendar/src/views/v2/photo/event.php
 * wp-content/plugins/the-events-calendar/src/views/v2/photo/event/featured-image.php
 *
 * Alors l'emplacement de remplacement du plugin ECET est:
 * /wp-content/plugins/easy-custom-event-tickets/tribe-customizations-photo/v2/photo/event.php
 * /wp-content/plugins/easy-custom-event-tickets/tribe-customizations-photo/v2/photo/event/featured-image.php
 *
 * Référence: https://theeventscalendar.com/knowledgebase/k/how-to-customize-templates-and-css-in-a-view/
 *			  https://theeventscalendar.com/knowledgebase/k/custom-additional-template-locations/
 *
 * @param array $folders:  Tableau de données pour les emplacements de chargement.
 *
 * @return array
 */	
function ecet_tribe_custom_template_paths_v2_view_photo( $folders ) {
	
		// nom du plugin ou seront chargé les modèles personnalisés
		$plugin_name = 'easy-custom-event-tickets';

		/**
		 * Quel ordre de priorité pour charger nos fichiers modèle a partir du plugin. 
		 * pour Events Pro = 25 , The Events Calendar = 20 , Event Tickets = 17
		 */
		$priority = 5;

		// Dossier du plugin ou sont stockés les modèles personnalisés
		$custom_folder = 'tribe-customizations-photo/v2';
		
		// trailingslashit() permet d'ajouter un slash en fin du chemin du plugin
		$plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) ).$custom_folder ;

		/*
		 * Emplacement de chargement personnalisé pour écraser 
		 * le chargement du fichier modèle initial The Events Calendar.
		 */
		$folders[ $plugin_name ] = [
			'id'        => $plugin_name,
			//'namespace' => $plugin_name,  Ne définissez ceci que si vous souhaitez écraser l'espace de noms de thème
			'priority'  => $priority,
			'path'      => $plugin_path,
		];

		return $folders;
	
}

// si on a activé le déplacement de la date à l'intérieur de la photo
if( $settings['ecet_moving_event_date_inside_photo_radio_field'] == 'yes') {
	add_filter( 'tribe_template_theme_path_list', 'ecet_tribe_custom_template_paths_v2_view_photo', 10, 1 );
}
	
	
/*------------------------------------------------------------------------------------------*
 * 15. Afficher un filtre de catégories pour les évènements dans la barre de recherche
 *------------------------------------------------------------------------------------------*/

// Ajouter un contrôle select pour les catégories dans la barre de recherche 
add_action( 'tribe_template_after_include:events/v2/components/events-bar/search','ecet_select_categories_events_bar' );

function ecet_select_categories_events_bar() {
	
	$index=0;
	$category_list = array();
	
	if ( !function_exists( 'tribe_get_events' ) ) {
	   return '';	           
	}
	
	// on récupère un tableau d'objets des événements listés
	$events = tribe_get_events();
	
	// on récupère dans tableau les catégories sous forme de liens ex:
	// <a href="http://localhost/rouerguecreation/demos/categorie/cours-rock/" rel="tag">Cours Rock 4 Temps</a>
	foreach ( $events as $events_index => $event ) {
		$event_id = $event->ID;
		if (!empty(tribe_get_event_taxonomy($event_id))) {
			++$index;
			$category_list[$index] = tribe_get_event_taxonomy($event_id);
		}
	}
	
	// on supprime les doublons du tableau
	// on conserve l'index du 1er doublon trouvé
	$category_list = array_unique($category_list);

	// on trie le tableau dans l'ordre croissant
	// on assigne de nouvelles clés aux éléments
	sort($category_list);
	
	/* Pour debug 
	echo'<pre>';
		print_r($category_list);
	echo'</pre>';
	
	*/
	
	// si pas de catégories on sort
	if(empty($category_list)) {
		return '';	
	}
	
	?>
	
		<!--- 
		On utilise javascript avec l'évènement onchange
		pour attribuer à la fenêtre une nouvelle URL
		grace a la valeur de l'option sélectionné
		qui n'est autre que l'url de la catégorie sélectionné
		--->	
		<select class="ecet_select_event_categories" name="ecet_select_event_categories" onChange="location = this.options[this.selectedIndex].value;">
	
			
			<option value=""><?php esc_html_e( 'select a category', 'custom-event-tickets' ); ?></option>
			
			<option value="<?php echo esc_url(home_url( '/?post_type=tribe_events'));?>"><?php esc_html_e( 'All','custom-event-tickets' ); ?></option>
			
			<?php foreach ( $category_list as $index => $value ) :
				
				// On extrait le lien de la balise href
				// avec href = "lien" ou href='lien'
				// la valeur de retour est un tableau $link
				preg_match_all("`href\h*=\h*(?:'|\")(.+)(?:'|\")`iU", $value, $link);
				
				// on récupère le libellé de la categorie
				// qui est entre les balises <a> </a>
				// la valeur de retour est un tableau $category
				preg_match_all("|<[^>]+>(.*)</[^>]+>|U",$value,$category);
			
			?>
				<option value="<?php echo esc_url($link[1][0]) ?>" ><?php echo $category[1][0]; ?></option>
			
			<?php endforeach; ?>
		
		</select>
			
		
	<?php
	
}
		
	
	
	
/*------------------------------------------------------------------------------------------*
 * 16. CSS dynamique de la table des participants pour les blocs Gutenberg RSVP & Tickets
 *------------------------------------------------------------------------------------------*/
	
add_action( 'wp_head','ecet_style_plugins_event_all_version',1 );


// style pour les version Pro & Free des extensions
// The Events Calendar & Event Tickets
function ecet_style_plugins_event_all_version() {
	
	
	// On récupère dans la table wp_options pour le champ option_name = ecet_multiple_setting 
	// un tableau qui comporte les valeurs de réglages du plugin renseignés 
	// dans les champs correspondants de la page de réglage: ECET Réglage
	$settings  = get_option( 'ecet_multiple_setting' );
	
	
	echo '<style id="easy-custom-event-tickets-all-version" type="text/css">';
	
	
		/*--------------- CSS Table des participants ------------------*/
		
		echo'.table-participant td {';
			echo'border-width:'.$settings['ecet_select_border_width_field'].'px;';
			echo'border-color:' .$settings['ecet_color_border_field'].';';
			echo'font-size:'.$settings['ecet_select_font_size_field'].'px;';
			echo'color:'.$settings['ecet_font_color_field'].';';
		echo'}';

		echo'.table-participant tbody > tr:nth-child(2n) td {';
			echo'background:'.$settings['ecet_color_line_field'].';';
		echo'}';
		
		
		// si on a activé les tables responsives
		if( $settings['ecet_participant_responsive_tables_radio_field'] == 'yes' ) {
		
			echo'@media only screen and (max-width: 767px) {';
		
				/* Container de la table responsive */
				echo'div.table-wrapper { ';
					echo'position: relative;';
					echo'margin-bottom: 20px;'; 
					echo'overflow: hidden;'; 
				echo'}';
				
				
				echo'table.ecet-responsive td, table.ecet-responsive th {';
					echo'position: relative;'; 
					echo'white-space: nowrap;'; 
					echo'overflow: hidden;'; 
				echo'}';
				
				echo'table.ecet-responsive th:first-child, 
				     table.ecet-responsive td:first-child, 
					 table.ecet-responsive td:first-child, 
					 table.ecet-responsive.pinned td {'; 
					 echo'display: none;'; 
				echo'}';
				
				echo'table.ecet-responsive { ';
					echo'margin-bottom: 0; ';
				echo'}';
				
				
				/*on définit ici avec margin-left la largeur a partir 
				  de laquelle la table est scrollable*/
				echo'div.table-wrapper div.scrollable {';  
					echo'margin-left: 50%;';  
				echo'}';
				
				echo'div.table-wrapper div.scrollable {';
					echo'overflow: scroll;'; 
					echo'overflow-y: hidden;'; 
				echo'}';	
				
				
				/*on définit ici avec width la largeur a partir 
				  de laquelle la table est scrollable*/
				echo'.pinned { ';
					echo'position: absolute;'; 
					echo'left: 0; top: 0;'; 
					echo'background: #fff;'; 
					echo'width: 50%;'; 
					echo'overflow: hidden;'; 
					echo'overflow-x: scroll;'; 
				echo'}';
				
				echo'.pinned table {'; 
					echo'border-right: none;'; 
					echo'border-left: none;'; 
					echo'width: 100%;'; 
				echo'}';
				
				echo'.pinned table th, .pinned table td {'; 
					echo'white-space: nowrap;'; 
				echo'}';
				
				echo'.pinned td:last-child {'; 
					echo'border-bottom: 0;'; 
				echo'}';
				
				
			echo'}';
		
		}
		
		/*----------- CSS Block Gutenberg RSVP ------------------*/
		
		// On remplace le titre du bouton GOING par BOOKING et on décode a l'affichage l'échappement des caractères spéciaux excepté les ""
		echo'body.single-tribe_events .event-tickets .tribe-tickets__rsvp-actions .tribe-common-c-btn:after {';
			echo'content:"' . htmlspecialchars_decode( esc_html__( $settings['ecet_rsvp_button_text_field'],'custom-event-tickets' ) ) . '";';
		echo'}';

		// pour le nombre de participants On remplace le texte Going par Booking 
		echo'body.single-tribe_events .event-tickets .tribe-tickets__rsvp-attendance-going:after {';
			echo'content:"' . htmlspecialchars_decode( esc_html__( $settings['ecet_text_below_number_of_participants_text_field'],'custom-event-tickets' ) ) . '";';
		echo'}';
	
		//on remplace le texte RSVP CLOSED par BOOKING CLOSED
		echo'body.single-tribe_events .event-tickets .tribe-tickets__rsvp-actions-full-text:after {';
			echo'content:"' . htmlspecialchars_decode( esc_html__( $settings['ecet_closed_reservation_text_field'],'custom-event-tickets' ) ) . '";';
		echo'}';

		//On remplace le texte en intro du formulaire de Réservation et on décode a l'affichage l'échappement des caractères spéciaux excepté les ""
		echo'body.single-tribe_events .event-tickets .tribe-tickets__rsvp-form-title h3:after {';
			echo'content:"' . htmlspecialchars_decode( __( $settings['ecet_form_reservation_textarea_field'],'custom-event-tickets' ) ). '";';
		echo'}';
		
		
		/* ------------- Calendrier des évènements pour la vue liste  -----------------*/

		// si on a activé la personnalisation de la vue Liste
		if( $settings['ecet_enable_custom_events_list_view_style_radio_field'] == 'yes'){
			
			/* régles pour le texte(espace restant: au lieu de billets restants)afin de le masquer 
			   de l'évènement dans la vue liste */
			echo'.tribe-events .tribe-events-c-small-cta__stock{';
				echo'display:none;';
			echo'}';

			/* marge description de l' évènement par rapport a l'affichage du nbr de participant & billets restants */
			echo'.tribe-events .tribe-events-calendar-list__event-description{';
				echo'margin-bottom:16px;';
			echo'}';
			
			// marge pour le nombre d'espace restant vue liste & photo
			echo'body.post-type-archive-tribe_events .tribe-events .tribe-events-c-small-cta__stock{';
				echo'margin: 5px 5px 5px 5px;';
			echo'}';

		}
		
		
	echo '</style>';
	
	
}



/*----------------------------------------------------------------------------*
 *     17. CSS Dynamique relatif aux versions Pro
 *----------------------------------------------------------------------------*/


// si on a la version pro de l' extension The Events Calendar 
if ($settings['ecet_default_version_extension_events_radio_field'] == 'pro') {	
	add_action( 'wp_head','ecet_style_plugins_event_pro',1 );
}

// style pour les version Pro des extensions
// The Events Calendar & Event Tickets
function ecet_style_plugins_event_pro() {
	
	
	// On récupère dans la table wp_options pour le champ option_name = ecet_multiple_setting 
	// un tableau qui comporte les valeurs de réglages du plugin renseignés 
	// dans les champs correspondants de la page de réglage: ECET Réglage
	$settings  = get_option( 'ecet_multiple_setting' );
	
	
	echo '<style id="easy-custom-event-tickets-pro" type="text/css">';
	
	
		/* -----------  Calendrier des évènements pro pour la vue liste ou Photo  -------------------------*/
		
		// on inhibe la régle display:none établi par la feuille 
		// de style pour Event Tickets & The Events Calendar Version Free
		echo'.tribe-events .tribe-events-c-small-cta__stock{';
			echo 'display:inline-block!important;';
			echo 'margin:5px 5px 5px 0px;';
			echo 'font-weight:600;';
		echo '}';
		
		// nombre de participants
		echo'body.post-type-archive-tribe_events .tribe-events .ecet-attendees-number{';
			echo 'color: var(--tec-color-text-secondary);';
			echo 'font-weight:600;';
		echo '}';
		
		
		/* -------------------------- Calendrier des évènements pro pour la vue photo -------------------------*/

		// https://demo.theeventscalendar.com/events/photo/
		// source: wp-content/plugins/events-calendar-pro/src/resources/css/tribe-events-pro-mini-calendar-block.min.css?ver=6.0.10
		
		// si on activé la personnalisation du style pour la vue Photo
		if($settings['ecet_enable_custom_events_photo_view_style_radio_field'] =='yes'){
			
			/* Marge basse de la photo par rapport au descriptif */
			echo'.tribe-events-pro .tribe-events-pro-photo__event-featured-image-wrapper {';
				echo'margin-bottom:0!important;';
				echo'box-shadow:0px 0px 4px 1px rgba(0,0,0,0.2);';
				echo'border-radius:10px 10px 0px 0px;';
			echo '}';

			/* border radius pour l'image */
			echo'.tribe-events-pro .tribe-events-pro-photo__event-featured-image-wrapper img {';
				echo'border-radius:10px 10px 0px 0px;';
			echo '}';
			
			/* descriptif sous la photo */
			echo'.tribe-events-pro .tribe-events-pro-photo__event-details-wrapper{';
				echo'background:#FFFFFF;';
				echo'box-shadow:0px 0px 4px 1px rgba(0,0,0,0.2);';
				echo'padding:5px;';
				echo'border-radius:0px 0px 10px 10px;';
			echo '}';

			// si on a activé un effet au survol de la vue photo de l'évènement
			if($settings['ecet_add_effect_hover_photo_view_event_radio_field'] =='yes') {

				/* Décalage vertical vers le haut et vers la gauche au survol de l'évènement */
				echo'.tribe-common--breakpoint-medium.tribe-common .tribe-common-g-row--gutters>.tribe-common-g-col:hover{';
					echo 'transform: translateY(-6px) translateX(-1px);';
					echo 'transition: transform 0.3s ease-in-out;';
				echo '}';
			
			}
			
			// Titre de l'évènement
			echo'body.post-type-archive-tribe_events .tribe-events-pro .tribe-events-pro-photo__event-title{';
				echo'margin:0;';
			echo '}';
			
			
			/* Régles mobiles pour un thème par défaut des pages
			   et non celui des évènements
			echo'@media (max-width:499px){';
			
				// padding left colonne
				echo'.tribe-common .tribe-common-g-row--gutters>.tribe-common-g-col {';
					echo'padding-left: 10px;';
				echo '}';
				
				// largeur colonne
				echo'.tribe-common .tribe-common-g-col {';
					echo'width: 95%;';
				echo '}';
				
			echo '}';
			*/
			
			/*
			echo'@media (min-width:500px) and (max-width:1240px){';
				
				// largeur container
				echo'.tribe-common .tribe-events-pro-photo {';
					echo'width: 98%;';
				echo '}';
				
			echo '}';
			*/
						
		}
		
	
	echo '</style>';
	
	
}


/*---------------------------------------------------------------------------------------------------------*
 * 18. CSS Dynamique relatif a la vue photo alternative établi par Tribe Ext Alternative Photo View
 *-------------------------------------------------------------------------------------------------------*/

// si on a activé l'extension Tribe Ext Alternative Photo View
// mais pas le style personnalisé pour la vue photo pro 
// et que l'on n'a pas déplacé la date a l'intérieur de la photo
if ( ($settings['ecet_enable_events_alternative_photo_view_style_radio_field'] == 'yes') && 
	 ($settings['ecet_enable_custom_events_photo_view_style_radio_field'] =='no') &&  
	 ($settings['ecet_moving_event_date_inside_photo_radio_field'] =='no')) {	
	add_action( 'wp_head','ecet_style_alternative_photo_view',1 );
}

// style pour les version Pro des extensions
// The Events Calendar & Event Tickets
function ecet_style_alternative_photo_view() {

	echo '<style id="ecet-alternative-photo-view" type="text/css">';
	
	
		// Titre de l'évènement 
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .tribe-events-pro-photo__event-title {';
			echo'padding: 0em 0.5em;';
			echo'margin-top: -10px;';
		echo '}';

		// Lien du titre de l'évènement 
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .tribe-events-pro-photo__event-title a{';
			echo'color:#FFFFFF;';
		echo '}';

		// Date de l'èvènement
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .tribe-events-pro-photo__event-date-tag{';
			echo'padding-left:5px;';
			echo'padding-right:5px;';
		echo '}';

		// Date de l'èvènement
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .tribe-events-pro-photo__event-date-tag-daynum{';
			echo'font-size:var(--tec-font-size-5);';
		echo '}';
		 
		// espace restant
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .tribe-events-c-small-cta__stock{';
			echo'margin-right:5px;';
		echo '}';

		// marge gauche pour Affichage au bas de la photo: réservation Maintenant gratuit
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .tribe-events-c-small-cta>:first-child{';
			echo'margin-left:5px;';
		echo '}';	

		// Affichage au bas de la photo: réservation Maintenant gratuit  55 espaces restant 
		echo'body.post-type-archive-tribe_events .tribe-common--breakpoint-medium.tribe-events-pro .tribe-events-pro-photo__event-cost{';
			echo'margin-top: var(--tec-spacer-0);';
		echo '}';


		// Affichage Nom du lieu et ville de l'évènement 
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .ecet-venue-details{';
			echo'color:#FFFFFF;';
			echo'margin-left:5px;';
			echo'text-align:center;';
			echo'text-shadow: 1px 1px 2px #666;';
			echo'margin-top: -10px;';
		echo '}';

		// Affichage Nombre de participant à l'évènement 
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .ecet-attendees-number{';
			echo'color:#FFFFFF!important;';
			echo'margin-left:5px;';
			echo'text-align:center;';
			echo'text-shadow: 1px 1px 2px #666;';
		echo '}';

		// Affichage de la balise catégorie
		echo'body.post-type-archive-tribe_events .tribe-events-pro-photo__event .tribe-event-categories{';
			echo'margin-left:5px;';
		echo '}';
	
	echo '</style>';

}
