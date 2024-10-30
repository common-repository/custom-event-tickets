<?php

/************************************************************
*
*  Easy Custom Event Tickets [REV:2.1.1] 
*
* 
*  I  Ajout d'un groupe de champs de paramètres à la page de réglages du plugin
*
*  1. 1ère SECTION:  Paramètres par défaut
*
*  2. 2ème SECTION:  Choix des versions pour les extensions The Event Calendar & Event Tickets
*
*  3. 3ème SECTION:  Table des participants
*		
*  4. 4ème SECTION:  Style table des participants
*				
*  5. 5ème SECTION:  Style Block Gutenberg RSVP
*		
*  6. 6ème SECTION:  The Events Calendar Vue Liste
*       
*  7. 7ème SECTION:  The Events Calendar Vue Photo
*
*  8. 8ème SECTION:  Vue Photo personnalisé par extension:Tribe Ext Alternative Photo View
*
*  9. 9ème SECTION:  The Events Calendar Vue Mois
*
*  10. 10ème SECTION:  Dupliquer évènement
*		
*  II  AFFICHAGE DE HTML EN DESSOUS DES TITRES DE SECTION
*
*  III  AFFICHAGE DES CHAMPS DE PARAMÈTRES 
*
*  1. AFFICHAGE DES CHAMPS POUR LA 1ère SECTION paramètres par défaut
*
*  2. AFFICHAGE DES CHAMPS POUR LA 2ème SECTION versions pour les extensions The Event Calendar & Event Tickets
*
*  3. AFFICHAGE DES CHAMPS POUR LA 3ème SECTION Table des participants
*		
*  4. AFFICHAGE DES CHAMPS POUR LA 4ème SECTION Style table des participants
*		
*  5. AFFICHAGE DES CHAMPS POUR LA 5ème SECTION: Style Block Gutenberg RSVP
*		
*  6. AFFICHAGE DES CHAMPS POUR LA 6ème SECTION: The Events Calendar Vue Liste
*		
*  7. AFFICHAGE DES CHAMPS POUR LA 7ème SECTION: The Events Calendar Vue Photo
*
*  8. AFFICHAGE DES CHAMPS POUR LA 8ème SECTION:  Vue Photo personnalisé par extension:Tribe Ext Alternative Photo View
*
*  9. AFFICHAGE DES CHAMPS POUR LA 9ème SECTION: The Events Calendar Vue Mois
*  
*  V  NETTOYAGE DES VALEURS DES CHAMPS AVANT ENTRÉE EN BASE DE DONNÉES	
*
* 
************************************************************/


/* Empêche l'utilisateur public d'accéder directement aux fichiers .php via l'URL
et garantit que les fichiers du plugin seront exécutés uniquement dans l'environnement WordPress.*/
defined( 'ABSPATH' ) || die();


/***************************************************************************
 * Ajout d'un groupe de champs de paramètres à la page de réglages du plugin
 ****************************************************************************/
 
 // On se hooke sur admin_init et va simplement déclarer à WordPress l’existence d’un réglage
 add_action( 'admin_init', 'ecet_multiple_setting' );


// On enregistre plusieurs paramètres
function ecet_multiple_setting(){
	
    // on crée un réglage
	register_setting( 
        'settings_ecet',     // Settings group ( nom du groupe).
        'ecet_multiple_setting', // Setting name ( nom du réglage c'est le nom de la fonction qui est hooké sur admin_init)
        'ecet_multiple_settings_sanitize'  // Sanitize callback(fonction de nettoyage pour nos valeur de réglages avant l'entrée en base de données)
    );
	
	
	/* -------------------------------------- 1ère SECTION:  Paramètres par défaut -----------------------------------------*/
	
	
	// On enregistre une section pour pour y ranger nos champs de réglages
    add_settings_section( 
        'default_settings_section',                   // Section ID
        __( 'Default settings', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_section_default_settings_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );
	
	
	// on déclare un champ de type radio 
    add_settings_field( 
        'ecet_radio_default_setting_field',           // Field ID
        __( 'initialize the plugin with default settings', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_radio_default_setting_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'default_settings_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	// on déclare un champ de type radio pour supprimer les données lors de la désinstallation 
    add_settings_field( 
        'ecet_radio_delete_data_uninstallation_field',           // Field ID
        __( 'delete data during uninstallation?', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_radio_delete_data_uninstallation_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'default_settings_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* ----2ème SECTION:  Choix des versions pour les extensions The Event Calendar & Event Tickets --------- */
	
	
	// On enregistre une section pour pour y ranger nos champs de réglages
    add_settings_section( 
        'default_version_extension_events_section',                   // Section ID
        __( 'Select your extension version', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_section_default_version_extension_events_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );
	
	
	/* on déclare un champ de type radio */ 
    add_settings_field( 
        'ecet_default_version_extension_events_radio_field',           // Field ID
        __( 'Select your extension version', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_default_version_extension_events_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'default_version_extension_events_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	
	/* -------------------------------------- 3ème SECTION:  Table des participants -----------------------------------------*/
	
	
	// On enregistre une section pour pour y ranger nos champs de réglages
    add_settings_section( 
        'participants_table_section',                   // Section ID
        __( 'Participants table', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_section_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );
	
	
	/* on déclare un champ de type radio
       pour activer la table des participants */ 
    add_settings_field( 
        'ecet_enable_participants_table_radio_field',           // Field ID
        __( 'Enable participants table', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_enable_participants_table_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	// on déclare un champ de type text
    add_settings_field( 
        'ecet_text_field',                   // Field ID
        __( 'Table title', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_text_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );
	
	
	// On déclare un select pour la balise HTML du titre
    add_settings_field( 
        'ecet_select_title_tag_field',          // Field ID
        __( 'HTML title tag', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_select_title_tag_field_markup',   // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',          // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_section',  // Section (l'ID de la section à laquelle appartient le champ)
        array(
            'options' => array(
                'h2' => __( 'h2', 'custom-event-tickets' ),
                'h3' => __( 'h3', 'custom-event-tickets' ),
                'h4' => __( 'h4', 'custom-event-tickets' ),
				'h5' => __( 'h5', 'custom-event-tickets' ),
				'p' => __( 'p', 'custom-event-tickets' ),
            ),
        )
    );
	
	
	// On déclare un select pour le nombre de colonnes de la table
    add_settings_field( 
        'ecet_select_column_field',          // Field ID
        __( 'select the number of columns', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_select_column_field_markup',   // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',          // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_section',  // Section (l'ID de la section à laquelle appartient le champ)
        array(
            'options' => array(
                '3' => __( '3', 'custom-event-tickets' ),
                '4' => __( '4', 'custom-event-tickets' ),
                '5' => __( '5', 'custom-event-tickets' ),
				'6' => __( '6', 'custom-event-tickets' ),
				'7' => __( '7', 'custom-event-tickets' ),
				'8' => __( '8', 'custom-event-tickets' ),
            ),
        )
    );
	
	
	/* on déclare un champ de type radio
       pour afficher le Nom: Gilles Dupont au format
	   Gilles Du. */ 
    add_settings_field( 
        'ecet_participant_name_format_radio_field',           // Field ID
        __( 'Enable custom format for attendee name', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_participant_name_format_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	/* on déclare un champ de type radio
       pour activer le mode responsive
	   pour la table des participants */ 
    add_settings_field( 
        'ecet_participant_responsive_tables_radio_field',           // Field ID
        __( 'Enable responsive table', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_participant_responsive_tables_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* -------------------------------------- 4ème SECTION:  Style table des participants -----------------------------------------*/
	
	
	// On enregistre une section pour pour y ranger nos champs de réglages pour les styles de la table
    add_settings_section( 
        'participants_table_style_section',                   // Section ID
        __( 'Participants table style', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_section_style_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );
	
	
	// on déclare un champ de type text pour la couleur de fond des lignes paires de la table
    add_settings_field( 
        'ecet_color_line_field',                   // Field ID
        __( 'even line background color', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_color_line_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );
	
	
	/* On déclare un select pour l'épaisseur de la bordure table */
    add_settings_field( 
        'ecet_select_border_width_field',          // Field ID
        __( 'border thickness in pixel', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_select_border_width_field_markup',   // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',          // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_style_section',  // Section (l'ID de la section à laquelle appartient le champ)
        array(
            'options' => array(
                '1' => __( '1', 'custom-event-tickets' ),
                '2' => __( '2', 'custom-event-tickets' ),
                '3' => __( '3', 'custom-event-tickets' ),
				'4' => __( '4', 'custom-event-tickets' ),
            ),
        )
    ); 
	
	
	// on déclare un champ de type text pour la couleur des bordures de la table
    add_settings_field( 
        'ecet_color_border_field',                   // Field ID
        __( 'color border', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_color_border_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );
	
	
	/* On déclare un select pour la taille de la police */
    add_settings_field( 
        'ecet_select_font_size_field',          // Field ID
        __( 'font size in pixel', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_select_font_size_field_markup',   // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',          // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_style_section',  // Section (l'ID de la section à laquelle appartient le champ)
        array(
            'options' => array(
                '15' => __( '15', 'custom-event-tickets' ),
                '16' => __( '16', 'custom-event-tickets' ),
                '17' => __( '17', 'custom-event-tickets' ),
				'18' => __( '18', 'custom-event-tickets' ),
				'19' => __( '19', 'custom-event-tickets' ),
				'20' => __( '20', 'custom-event-tickets' ),
            ),
        )
    ); 
	
	
	// on déclare un champ de type text pour la couleur de la police
    add_settings_field( 
        'ecet_font_color_field',                   // Field ID
        __( 'font color', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_font_color_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'participants_table_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );
	
	
	/* -------------------------------------- 5ème SECTION:  Style Block Gutenberg RSVP ----------------------------------------- */


// On enregistre une section pour pour y ranger nos champs de réglages pour la personnalisation du block Gutenberg RSVP
    add_settings_section( 
        'rsvp_gutenberg_block_style_section',                   // Section ID
        __( 'RSVP Gutenberg block style', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_section_rsvp_gutenberg_block_style_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );
	
	
	// on déclare un champ de type text pour le texte du bouton du block Gutenberg RSVP
    add_settings_field( 
        'ecet_rsvp_button_text_field',                   // Field ID
        __( 'button text', 'custom-event-tickets' ),  // button text , Text Domain(pour la traduction)
        'ecet_rsvp_button_text_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'rsvp_gutenberg_block_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );


	// on déclare un champ de type text pour le texte en dessous du nombre de participants
    add_settings_field( 
        'ecet_text_below_number_of_participants_text_field',                   // Field ID
        __( 'text below number of participants', 'custom-event-tickets' ),  // text below number of participants , Text Domain(pour la traduction)
        'ecet_text_below_number_of_participants_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'rsvp_gutenberg_block_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );
	
	
	// on déclare un champ de type text pour le texte affiché pour une réservation clôturé
    add_settings_field( 
        'ecet_closed_reservation_text_field',                   // Field ID
        __( 'text displayed for a closed reservation', 'custom-event-tickets' ),  // text displayed for a closed reservation , Text Domain(pour la traduction)
        'ecet_text_displayed_closed_reservation_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'rsvp_gutenberg_block_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );
	
	
	// on déclare un champ de type Zone de texte pour le texte affiché en intro du formulaire
    add_settings_field( 
        'ecet_form_reservation_textarea_field',                   // Field ID
        __( 'text displayed in introduction reservation form', 'custom-event-tickets' ),  // text displayed in introduction reservation form , Text Domain(pour la traduction)
        'ecet_text_displayed_form_reservation_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'rsvp_gutenberg_block_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );


/* -------------------------------------- 6ème SECTION:  The Events Calendar Vue Liste ----------------------------------------- */


	// On enregistre une section pour pour y ranger nos champs de réglages pour la personnalisation du Calendrier Des Évènements en vue liste
    add_settings_section( 
        'events_calendar_list_style_section',                   // Section ID
        __( 'Custom the events calendar list view', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_events_calendar_list_style_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );
	
	
	/* on déclare un champ de type radio pour activer la personnalisation du style pour
       la vue liste de l'évènement	*/ 
    add_settings_field( 
        'ecet_enable_custom_events_list_view_style_radio_field',           // Field ID
        __( 'Enable personalization for list view', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_enable_custom_events_list_view_style_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_list_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	// on déclare un champ de type text pour le texte du nombre de participants
    add_settings_field( 
        'ecet_event_number_participants_text_field',                   // Field ID
        __( 'text displayed as a prefix to the number of participants List & Photo view', 'custom-event-tickets' ),  // text displayed as a prefix of the number of participants , Text Domain(pour la traduction)
        'ecet_event_number_of_participant_text_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_list_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );
	
	
	// on déclare un champ de type text pour le texte de billets restants
    add_settings_field( 
        'ecet_event_remaining_tickets_text_field',                   // Field ID
        __( 'text displayed as a prefix of the number of tickets remaining', 'custom-event-tickets' ),  // text displayed as a prefix of the number of tickets remaining , Text Domain(pour la traduction)
        'ecet_event_remaining_tickets_text_field_markup',            // Callback to display the field (la fonction permettant d'afficher le champ)
        'ecet_settings_page',                // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_list_style_section',        // Section (l'ID de la section à laquelle appartient le champ)
    );

	
	/* on déclare un champ de type radio pour activer une balise de catégorie
       pour la vue liste de l'évènement	*/ 
    add_settings_field( 
        'ecet_enable_list_view_category_tag_radio_field',           // Field ID
        __( 'Add category tag', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_enable_list_view_category_tag_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_list_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 

	
	/* Registers a checkbox example
    add_settings_field( 
        'ecet_checkbox_field',        // Field ID
        __( 'Example checkbox field', 'custom-event-tickets' ), // Title
        'ecet_checkbox_field_markup', // Callback
        'ecet_settings_page',         // Page
        'participants_table_section', // Section
        array( 
            'label_for' => 'ecet_checkbox_field',  // Id for the input and label element = Field ID
            'description' => __( 'This is a description of what the example checkbox does.', 'custom-event-tickets' ),
        )
    ); */

	
	/*Registers a textarea example
    add_settings_field( 
        'ecet_textarea_field',        // Field ID
        __( 'Example textarea field', 'custom-event-tickets' ),  // Title
        'ecet_textarea_field_markup', // Callback
        'ecet_settings_page',         // Page
        'participants_table_section', // Section
    ); */
	
	
/* -------------------------------------- 7ème SECTION:  The Events Calendar Vue Photo ----------------------------------------- */


	// On enregistre une section pour pour y ranger nos champs de réglages pour la personnalisation du Calendrier Des Évènements en vue liste
    add_settings_section( 
        'events_calendar_photo_style_section',                   // Section ID
        __( 'Custom the pro version events calendar photo view', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_events_calendar_photo_style_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );	
	
	/* on déclare un champ de type radio pour activer la personnalisation du style pour
       la vue photo de l'évènement	*/ 
    add_settings_field( 
        'ecet_enable_custom_events_photo_view_style_radio_field',           // Field ID
        __( 'Enable custom style for photo view', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_enable_custom_events_photo_view_style_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_photo_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* on déclare un champ de type radio pour ajouter un effet au survol 
       de la vue photo de l'évènement	*/ 
    add_settings_field( 
        'ecet_add_effect_hover_photo_view_event_radio_field',           // Field ID
        __( 'Add effect on hover for custom style', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_add_effect_hover_photo_view_event_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_photo_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	/* on déclare un champ de type radio pour déplacer la date 
       de l'évènement dans la photo */ 
    add_settings_field( 
        'ecet_moving_event_date_inside_photo_radio_field',           // Field ID
        __( 'Moving the event date inside of the photo', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_moving_event_date_inside_photo_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_photo_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* on déclare un champ de type radio pour ajouter le Nom du lieu et la ville
       sur la vue photo de l'évènement	*/ 
    add_settings_field( 
        'ecet_venue_details_photo_view_event_radio_field',           // Field ID
        __( 'Add Venue Name and Event City', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_venue_details_photo_view_event_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_photo_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* on déclare un champ de type radio pour ajouter le nombre de participants
       sur la vue photo de l'évènement	*/ 
    add_settings_field( 
        'ecet_add_attendees_number_photo_view_event_radio_field',           // Field ID
        __( 'Add the number of attendees', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_add_attendees_number_photo_view_event_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_photo_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* on déclare un champ de type radio pour activer une balise de catégorie
       sur la vue photo de l'évènement	*/ 
    add_settings_field( 
        'ecet_enable_photo_view_category_tag_radio_field',           // Field ID
        __( 'Add category tag', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_enable_photo_view_category_tag_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_photo_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* -------------------------------------- 8ème SECTION:  Vue Photo personnalisé par extension:Tribe Ext Alternative Photo View ----------------------------------------- */


	// On enregistre une section pour pour y ranger nos champs de réglages pour la personnalisation du Calendrier Des Évènements en vue liste
    add_settings_section( 
        'events_calendar_alternative_photo_style_section',                   // Section ID
        __( 'Custom Photo View by Extension:Tribe Ext Alternative Photo View', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_events_calendar_alternative_photo_style_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );	
	
	/* on déclare un champ de type radio pour activer la personnalisation du style pour
       la vue photo de l'évènement	*/ 
    add_settings_field( 
        'ecet_enable_events_alternative_photo_view_style_radio_field',           // Field ID
        __( 'Have you activated the extension: Tribe Ext Alternative Photo View', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_enable_events_alternative_photo_view_style_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_alternative_photo_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	/* -------------------------------------- 9ème SECTION:  The Events Calendar Vue Mois ----------------------------------------- */


	// On enregistre une section pour pour y ranger nos champs de réglages pour la personnalisation du Calendrier Des Évènements en vue Mois
    add_settings_section( 
        'events_calendar_month_style_section',                   // Section ID
        __( 'Customize the month view of the calendar of events', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_events_calendar_month_style_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );	
	
	/* on déclare un champ de type radio pour activer la personnalisation del'info-bulle pour
       la vue Mois de l'évènement	*/ 
    add_settings_field( 
        'ecet_enable_tooltip_customization_month_view_radio_field',           // Field ID
        __( 'enable tooltip customization', 'custom-event-tickets' ), // Title , Text Domain(pour la traduction)
        'ecet_enable_tooltip_customization_month_view_radio_field_markup',    // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page',         // Page (le slug de la page à laquelle appartient le champ)
        'events_calendar_month_style_section', // Section (l'ID de la section à laquelle appartient le champ)
    ); 
	
	
	/* -------------------------------------- 10ème SECTION:  Dupliquer évènement ----------------------------------------- */


	// On enregistre une section pour pour y ranger nos champs de réglages pour la personnalisation du Calendrier Des Évènements en vue Mois
    add_settings_section( 
        'events_calendar_duplicate-event_section',                   // Section ID
        __( 'Duplicate event', 'custom-event-tickets' ),  // Title , Text Domain(pour la traduction)
        'ecet_events_duplicate_event_style_markup',            // Callback or empty string (une fonction de rappel s’il y a de l’HTML spécial à afficher entre le titre de la section et les réglages)
        'ecet_settings_page'              // Page to display the section in (le slug de la page à laquelle appartient le champ)
    );	
	
	
}	// FIN FONCTION ecet_multiple_setting()




/* ---------------- AFFICHAGE DE HTML EN DESSOUS DES TITRES DE SECTION -----------------------------------------*/

	
/* Affiche du contenu HTML si besoin en dessous du titre de la section: Paramètres par défaut
   $args  Arguments(de type Array) passé a l'appel de la fonction */
function ecet_section_default_settings_markup( $args ){
	
	
}	
	

/* Affiche du contenu HTML si besoin en dessous du titre de la section: Sélectionner la version de vos extensions
   $args  Arguments(de type Array) passé a l'appel de la fonction */
function ecet_section_default_version_extension_events_markup( $args ){
	echo '<div class="mise-en-avant">';	
	echo esc_html__( 'Select the version of The Events Calendar extension on which the Easy Custom Event Tickets extension is dependent.','custom-event-tickets' );	
	echo'</div>';
}
	
	
/* Affiche du contenu HTML si besoin en dessous du titre de la section: Table des participants
   $args  Arguments(de type Array) passé a l'appel de la fonction */
function ecet_section_markup( $args ){
	
	echo '<div>';
		echo '<img  style="margin-top:15px;" src="'.esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL).'img/events-calendar-attendees-list-table.jpg">';
	echo '</div>';
	
	echo '<div class="mise-en-avant">';	
	echo esc_html__( 'You have an option to activate a custom format in order to partially display the Name of the participants provided that the users fill in the Name field of the registration form for an event starting with the First name followed by the Last name, for example:','custom-event-tickets' );	
	echo'<br>';
	echo esc_html__( 'Gilles Dupont is replaced by the custom format: Gilles Du.','custom-event-tickets' );	
	echo'<br><br>';
	echo esc_html__( 'Enable responsive tables:','custom-event-tickets' );
	echo'<br>';
	echo esc_html__( 'For a screen resolution < 767px the left column is fixed and the rest of the columns can scroll.','custom-event-tickets' );
	echo'</div>';
}

/* Affiche du contenu HTML si besoin en dessous du titre de la section: Style table des participants
   $args  Arguments(de type Array) passé a l'appel de la fonction */
function ecet_section_style_markup( $args ){
	
	
}

/* Affiche du contenu HTML si besoin en dessous du titre de la section: Style block Gutenberg RSVP
   $args  Arguments(de type Array) passé a l'appel de la fonction */
function ecet_section_rsvp_gutenberg_block_style_markup( $args ){
	echo '<div class="mise-en-avant">';	
	echo esc_html__( 'You can customize the texts below in the language of your choice, avoiding the quotation marks "','custom-event-tickets' );	
	echo'</div>';
}

/* Affiche du contenu HTML si besoin en dessous du titre de la section: Custom the events calendar list view */
function ecet_events_calendar_list_style_markup( $args ){
	
	echo '<div>';
		echo '<img  style="margin-top:15px;" src="'.esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL).'img/events-calendar-list-view.jpg">';
	echo '</div>';
	
	echo '<div class="mise-en-avant">';	
	echo esc_html__( 'You can customize the texts below in the language of your choice, avoiding the quotation marks "','custom-event-tickets' );		
	echo'<br>';
	echo'</div>';
}

/* Affiche du contenu HTML si besoin en dessous du titre de la section: Custom the events calendar photo view */
function ecet_events_calendar_photo_style_markup( $args ){
	echo '<div>';
		echo '<img  style="margin-top:15px;" src="'.esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL).'img/events-calendar-custom-photo-view.jpg">';
	echo '</div>';
}


/* Affiche du contenu HTML si besoin en dessous du titre de la section: Vue Photo personnalisé par extension:Tribe Ext Alternative Photo View */
function ecet_events_calendar_alternative_photo_style_markup( $args ){
	echo '<div>';
		echo '<img  style="margin-top:15px;" src="'.esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL).'img/events-calendar-alternative-photo-view.jpg">';
	echo '</div>';
	
	echo '<div class="mise-en-avant">';	
	echo esc_html__( '• To have this alternative view in a style consistent with the photo above activate the option below','custom-event-tickets' );	
	echo'<br>';
	echo esc_html__( '• You must also in the previous section have made the choice for the options:','custom-event-tickets' );	
	echo'<br>';
	echo esc_html__( 'Enable custom style for photo view --> no','custom-event-tickets' );
	echo'<br>';
	echo esc_html__( 'Move event date inside photo --> no','custom-event-tickets' );	
	echo'<br>';
	echo esc_html__( '• On the other hand you can in the previous section activate the options: Name of the place & City of the event + the number of attendees + Tag category.','custom-event-tickets' );	
	echo'<br>';
	echo'</div>';
	
}

/* Affiche du contenu HTML si besoin en dessous du titre de la section: The Events Calendar Vue Mois */
function ecet_events_calendar_month_style_markup( $args ){
	
	
	echo '<div style="float:left;"  class="mise-en-avant">';	
		echo esc_html__( '• To have this tooltip view in a style consistent with the photo opposite, activate the option below.','custom-event-tickets' );	
		echo'<br>';
		echo esc_html__( 'This allows you to add in the tooltip of the month view the Name of the place & the City of the event, the number of places remaining.','custom-event-tickets' );	
		echo'<br>';
	echo'</div>';
	
	echo '<div >';
		echo '<img  style="margin-top:15px;" src="'.esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL).'img/events-calendar-custom-tooltip-month-view.jpg">';
	echo '</div>';
	
}


/* Affiche du contenu HTML si besoin en dessous du titre de la section: Dupliquer évènement */
function ecet_events_duplicate_event_style_markup( $args ){
	
	echo '<div class="mise-en-avant">';	
		echo esc_html__( '• You must first select in the settings parameters of The Events Calendar extension in the Display tab / Date & Time section:','custom-event-tickets' );	
		echo'<br>';
		echo esc_html__( '- Date format with year: d/m/Y','custom-event-tickets' );	
		echo'<br>';
		echo esc_html__( '- Compact date format: 15/1/2024','custom-event-tickets' );		
	echo'</div>';
	
	echo '<div >';
		echo '<img  style="margin-top:15px;" src="'.esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL).'img/events-calendar-action-link-duplicate-event.jpg">';
	echo '</div>';
	
	echo '<div style="float:left;"  class="mise-en-avant">';	
		echo esc_html__( '• The extension adds a Duplicate Event link for actions on the screen that lists events.','custom-event-tickets' );	
		echo'<br>';
		echo esc_html__( '• By clicking on the Quick Edit link you can enter the new start and end dates for your duplicate event.','custom-event-tickets' );	
		echo'<br>';
		echo esc_html__( 'If there is a ticket, this has the advantage of also modifying the start & end date of a ticket, in correlation with the new date of the event.','custom-event-tickets' );	
		echo'<br>';
		echo esc_html__( '• So by remaining on the screen which lists the events you can easily duplicate your events which must be recurring and have an operational ticket for these events.','custom-event-tickets' );	
		echo'<br><br>';
		echo '<strong style="text-decoration:underline;">'.esc_html__( 'NOTICED:','custom-event-tickets' ).'</strong>';;	
		echo'<br>';
		echo esc_html__( 'If a paid ticket or RSVP is present in the duplicated event it is deleted because it remains linked to the event that was duplicated.','custom-event-tickets' );	
		echo'<br>';
		echo esc_html__( 'A new ticket block is created using the parameters of the original ticket block (capacity, title, description, price, etc...).','custom-event-tickets' );	
		echo'<br>';
	echo'</div>';
	
	echo '<div >';
		echo '<img  style="margin-top:15px;" src="'.esc_url(ECET_EASY_CUSTOM_EVENTS_TICKETS_URL).'img/events-calendar-quick-edit-event-duplicate-date.jpg">';
	echo '</div>';
	
	
}


/* ----------------AFFICHAGE DES CHAMPS POUR LA 1ère SECTION paramètres par défaut  -----------------------------------------*/


/* Affiche le paramètre de champ radio pour l'initialisation du plugin aux paramètres par défaut
   Attention ! ne prend pas les Majuscules pour les valeurs de champ.
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting */
function ecet_radio_default_setting_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_radio_default_setting_field'] ) ? $settings['ecet_radio_default_setting_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_radio_default_setting_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_radio_default_setting_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}


/* Affiche le paramètre de champ radio pour supprimer les données lors de la désinstallation */
function ecet_radio_delete_data_uninstallation_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_radio_delete_data_uninstallation_field'] ) ? $settings['ecet_radio_delete_data_uninstallation_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_radio_delete_data_uninstallation_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_radio_delete_data_uninstallation_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
	echo '<span id="section_plugins_version"></span>';
}


/* ------AFFICHAGE DES CHAMPS POUR LA 2ème SECTION versions pour les extensions The Event Calendar & Event Tickets -----------------------------------------*/


/* Affiche le paramètre de champ radio pour renseigner la version des extensions
   The Events Calendar & Event Tickets
   Attention ! ne prend pas les Majuscules pour les valeurs de champ.
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting */
function ecet_default_version_extension_events_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_default_version_extension_events_radio_field'] ) ? $settings['ecet_default_version_extension_events_radio_field'] : 'free';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'french', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_default_version_extension_events_radio_field]" value="free" <?php checked( $checked, 'free' ); ?>>
                    <?php esc_html_e( 'free', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_default_version_extension_events_radio_field]" value="pro" <?php checked( $checked, 'pro' ); ?>>
                    <?php esc_html_e( 'pro', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
	echo '<span id="section_attendees_table"></span>';
	
}




/* ----------- AFFICHAGE DES CHAMPS POUR LA 3ème SECTION Table des participants -----------------------------------------*/

/* Affiche le paramètre de champ radio pour activer 
   la table des participants
*/
function ecet_enable_participants_table_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_enable_participants_table_radio_field'] ) ? $settings['ecet_enable_participants_table_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_enable_participants_table_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_enable_participants_table_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
}



/* Affiche notre champ de réglage pour le titre de la table des participants
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting*/
function ecet_text_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_text_field'] ) ?  esc_html__($settings['ecet_text_field'],'custom-event-tickets')  : '';
    ?>
        <input class="regular-text" type="text" name="ecet_multiple_setting[ecet_text_field]" value="<?php echo htmlspecialchars_decode( esc_attr( $value ) ); ?>">
    <?php
	
}


/* Affiche le paramètre de champ de sélection pour la balise HTML du titre
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting*/
function ecet_select_title_tag_field_markup( $args ){
	
    $settings  = get_option( 'ecet_multiple_setting' ); // le nom du réglage passé a la fonction register_setting()
    $selected = ! empty( $settings['ecet_select_title_tag_field'] ) ? $settings['ecet_select_title_tag_field'] : '';// ID du champ
    
	?>
	
        <select name="ecet_multiple_setting[ecet_select_title_tag_field]">
		
            <option value=""><?php esc_html_e( 'Choose an option', 'custom-event-tickets' ); ?></option>
			
            <!-- On obtiend les valeurs passées dans un tableau d'options  -->
            <?php foreach ( $args['options'] as $value => $label ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?>><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
			
        </select>
		
    <?php
	
}



/* Affiche le paramètre de champ de sélection pour le nombre de colonnes de la table
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting*/
function ecet_select_column_field_markup( $args ){
	
    $settings  = get_option( 'ecet_multiple_setting' ); // le nom du réglage passé a la fonction register_setting()
    $selected = ! empty( $settings['ecet_select_column_field'] ) ? $settings['ecet_select_column_field'] : '';// ID du champ
    
	?>
	
        <select name="ecet_multiple_setting[ecet_select_column_field]">
		
            <option value=""><?php esc_html_e( 'Choose an option', 'custom-event-tickets' ); ?></option>
			
            <!-- On obtiend les valeurs passées dans un tableau d'options  -->
            <?php foreach ( $args['options'] as $value => $label ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?>><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
			
        </select>
		
    <?php
	
}


/* Affiche le paramètre de champ radio pour activer un format personnalisé 
   afin d'afficher partiellement le Nom des participants: 
   Gilles Dupont devient Gilles Du.
*/
function ecet_participant_name_format_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_participant_name_format_radio_field'] ) ? $settings['ecet_participant_name_format_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_participant_name_format_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_participant_name_format_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
}


/* Affiche le paramètre de champ radio pour activer 
   une table des participants responsive
*/
function ecet_participant_responsive_tables_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_participant_responsive_tables_radio_field'] ) ? $settings['ecet_participant_responsive_tables_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_participant_responsive_tables_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_participant_responsive_tables_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
	echo '<span id="section_attendees_table_style"></span>';
}





/* ------------------AFFICHAGE DES CHAMPS POUR LA 4ème SECTION Style table des participants -----------------------------------------*/


/* Affiche notre champ de réglage pour la couleur de fond des lignes impaires de la table
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting*/
function ecet_color_line_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_color_line_field'] ) ? $settings['ecet_color_line_field'] : '';
    ?>
        <input class="color-field" type="text" name="ecet_multiple_setting[ecet_color_line_field]" value="<?php echo esc_attr( $value ); ?>">
    <?php
	
}




/* Affiche le paramètre de champ de sélection pour l'épaisseur de la bordure 
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting */
function ecet_select_border_width_field_markup( $args ){
	
    $settings  = get_option( 'ecet_multiple_setting' ); // le nom du réglage passé a la fonction register_setting()
    $selected = ! empty( $settings['ecet_select_border_width_field'] ) ? $settings['ecet_select_border_width_field'] : '';// ID du champ
    
	?>
	
        <select name="ecet_multiple_setting[ecet_select_border_width_field]">
		
            <option value=""><?php esc_html_e( 'Choose an option', 'custom-event-tickets' ); ?></option>
			
            <!-- On obtiend les valeurs passées dans un tableau d'options  -->
            <?php foreach ( $args['options'] as $value => $label ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?>><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
			
        </select>
		
    <?php
	
}


/* Affiche notre champ de réglage pour la couleur des bordures de la table
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting*/
function ecet_color_border_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_color_border_field'] ) ? $settings['ecet_color_border_field'] : '';
    ?>
        <input class="color-field" type="text" name="ecet_multiple_setting[ecet_color_border_field]" value="<?php echo esc_attr( $value ); ?>">
    <?php
	
}


/* Affiche le paramètre de champ de sélection pour la taille de la police 
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting */
function ecet_select_font_size_field_markup( $args ){
	
    $settings  = get_option( 'ecet_multiple_setting' ); // le nom du réglage passé a la fonction register_setting()
    $selected = ! empty( $settings['ecet_select_font_size_field'] ) ? $settings['ecet_select_font_size_field'] : '';// ID du champ
    
	?>
	
        <select name="ecet_multiple_setting[ecet_select_font_size_field]">
		
            <option value=""><?php esc_html_e( 'Choose an option', 'custom-event-tickets' ); ?></option>
			
            <!-- On obtiend les valeurs passées dans un tableau d'options  -->
            <?php foreach ( $args['options'] as $value => $label ) : ?>
                <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $selected, $value ); ?>><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
			
        </select>
		
    <?php
	
}



/* Affiche notre champ de réglage pour la couleur de la police
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting*/
function ecet_font_color_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_font_color_field'] ) ? $settings['ecet_font_color_field'] : '';
    ?>
        <input class="color-field" type="text" name="ecet_multiple_setting[ecet_font_color_field]" value="<?php echo esc_attr( $value ); ?>">
    <?php
	
	echo '<span id="section_rsvp_block_style"></span>';
	
}


/* ------------ AFFICHAGE DES CHAMPS POUR LA 5ème SECTION: Style Block Gutenberg RSVP ----------------------------------------*/


/* Affiche notre champ de réglage pour le texte du bouton du block Gutenberg RSVP
   on décode a l'affichage l'échappement des caractères spéciaux excepté les "" qui bug la traduction
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting */
function ecet_rsvp_button_text_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_rsvp_button_text_field'] ) ? esc_html__( $settings['ecet_rsvp_button_text_field'],'custom-event-tickets' ) : '';
    ?>
        <input class="regular-text" type="text" name="ecet_multiple_setting[ecet_rsvp_button_text_field]" value="<?php echo htmlspecialchars_decode( esc_attr( $value ) ); ?>">
    <?php
	
}

/* Affiche notre champ de réglage pour le texte en dessous du nombre de participants
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting */
function ecet_text_below_number_of_participants_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_text_below_number_of_participants_text_field'] ) ?  esc_html__( $settings['ecet_text_below_number_of_participants_text_field'],'custom-event-tickets' ) : '';
    ?>
        <input class="regular-text" type="text" name="ecet_multiple_setting[ecet_text_below_number_of_participants_text_field]" value="<?php echo htmlspecialchars_decode( esc_attr( $value ) ); ?>">
    <?php
	
}


/* Affiche notre champ de réglage pour le texte affiché pour une réservation clôturé
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting */
function ecet_text_displayed_closed_reservation_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_closed_reservation_text_field'] ) ?  esc_html__( $settings['ecet_closed_reservation_text_field'],'custom-event-tickets' )  : '';
    ?>
        <input class="regular-text" type="text" name="ecet_multiple_setting[ecet_closed_reservation_text_field]" value="<?php echo htmlspecialchars_decode( esc_attr( $value ) ); ?>">
    <?php
	
}


/* Affiche le paramètre de champ textarea pour le texte affiché en intro du formulaire
   on décode a l'affichage l'échappement des caractères spéciaux excepté les "" qui bug la traduction
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting */
function ecet_text_displayed_form_reservation_field_markup( $args ){
	
    $settings  = get_option( 'ecet_multiple_setting' );
    $value = ! empty( $settings['ecet_form_reservation_textarea_field'] ) ?  esc_html__( $settings['ecet_form_reservation_textarea_field'],'custom-event-tickets' )  : '';
	
    ?>
        <textarea class="regular-text" rows=4 name="ecet_multiple_setting[ecet_form_reservation_textarea_field]"><?php echo htmlspecialchars_decode( esc_textarea( $value ) ); ?></textarea>
    <?php
	
	echo '<span id="section_events_calendar_list_view"></span>';
}


/* ----------- AFFICHAGE DES CHAMPS POUR LA 6ème SECTION: The Events Calendar Vue Liste  -----------------------------------------*/


/* Affiche le paramètre de champ radio pour activer la personnalisation
   du style pour la vue Liste */
function ecet_enable_custom_events_list_view_style_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_enable_custom_events_list_view_style_radio_field'] ) ? $settings['ecet_enable_custom_events_list_view_style_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_enable_custom_events_list_view_style_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_enable_custom_events_list_view_style_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}



/* Affiche notre champ de réglage pour le texte du nombre de participants
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting */
function ecet_event_number_of_participant_text_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_event_number_participants_text_field'] ) ?  esc_html__( $settings['ecet_event_number_participants_text_field'],'custom-event-tickets' )  : '';
    ?>
        <input class="regular-text" type="text" name="ecet_multiple_setting[ecet_event_number_participants_text_field]" value="<?php echo esc_attr( $value ); ?>">
    <?php
	
}


/* Affiche notre champ de réglage pour le texte en dessous du nombre de participants
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit : ecet_multiple_setting */
function ecet_event_remaining_tickets_text_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );// le nom du réglage passé a la fonction register_setting()
    $value   = ! empty( $settings['ecet_event_remaining_tickets_text_field'] ) ? esc_html__( $settings['ecet_event_remaining_tickets_text_field'],'custom-event-tickets' )  : '';
    ?>
        <input class="regular-text" type="text" name="ecet_multiple_setting[ecet_event_remaining_tickets_text_field]" value="<?php echo esc_attr( $value ); ?>">
    <?php
	
	echo '<span id="section_events_calendar_photo_view"></span>';
}

/* Affiche le paramètre de champ radio pour activer une balise catégorie
   pour la vue Liste */
function ecet_enable_list_view_category_tag_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_enable_list_view_category_tag_radio_field'] ) ? $settings['ecet_enable_list_view_category_tag_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_enable_list_view_category_tag_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_enable_list_view_category_tag_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}


/* Affiche le paramètre de champ checkbox
   $args  Arguments(de type Array) passé a l'appel de la fonction 
   on fait correspondre l’attribut name HTML de notre champ qui est un tableau avec le nom 
   du réglage déclaré avec register_setting() soit ecet_multiple_setting 
function ecet_checkbox_field_markup( $args ){
    
	$settings = get_option( 'ecet_multiple_setting' );
    $checked = (bool) $settings['ecet_checkbox_field'] ?: false;
	
    ?>
	
        <input id="<?php echo esc_attr( $args['label_for'] ); ?>" type="checkbox" name="ecet_multiple_setting[ecet_checkbox_field]" <?php checked( $checked ); ?>>
        <span><?php echo esc_html( $args['description'] ); ?></span>
		
    <?php
}


/* ----------- AFFICHAGE DES CHAMPS POUR LA 7ème SECTION: The Events Calendar Vue Photo  -----------------------------------------*/

/* Affiche le paramètre de champ radio pour activer la personnalisation
   du style pour la vue photo */
function ecet_enable_custom_events_photo_view_style_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_enable_custom_events_photo_view_style_radio_field'] ) ? $settings['ecet_enable_custom_events_photo_view_style_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_enable_custom_events_photo_view_style_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_enable_custom_events_photo_view_style_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}


/* Affiche le paramètre de champ radio pour ajouter un effet au survol
   de la vue photo de l'évènement */
function ecet_add_effect_hover_photo_view_event_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_add_effect_hover_photo_view_event_radio_field'] ) ? $settings['ecet_add_effect_hover_photo_view_event_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_add_effect_hover_photo_view_event_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_add_effect_hover_photo_view_event_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}

/* Affiche le paramètre de champ radio pour déplacer la date de l'évènement
   dans la photo */
function ecet_moving_event_date_inside_photo_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_moving_event_date_inside_photo_radio_field'] ) ? $settings['ecet_moving_event_date_inside_photo_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_moving_event_date_inside_photo_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_moving_event_date_inside_photo_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}


/* Affiche le paramètre de champ radio pour ajouter le Nom du lieu & la Ville
   pour la vue photo de l'évènement */
function ecet_venue_details_photo_view_event_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_venue_details_photo_view_event_radio_field'] ) ? $settings['ecet_venue_details_photo_view_event_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_venue_details_photo_view_event_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_venue_details_photo_view_event_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}



/* Affiche le paramètre de champ radio pour ajouter le nombre de participants
   à la vue photo de l'évènement */
function ecet_add_attendees_number_photo_view_event_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_add_attendees_number_photo_view_event_radio_field'] ) ? $settings['ecet_add_attendees_number_photo_view_event_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_add_attendees_number_photo_view_event_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_add_attendees_number_photo_view_event_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
}


/* Affiche le paramètre de champ radio pour une balise de catégorie
   à la vue photo de l'évènement */
function ecet_enable_photo_view_category_tag_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_enable_photo_view_category_tag_radio_field'] ) ? $settings['ecet_enable_photo_view_category_tag_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_enable_photo_view_category_tag_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_enable_photo_view_category_tag_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
	echo '<span id="section_events_calendar_alternative_photo_view"></span>';
	
}



/* ----------- AFFICHAGE DES CHAMPS POUR LA 8ème SECTION:  Vue Photo personnalisé par extension:Tribe Ext Alternative Photo View  -----------------------------------------*/

/* Affiche le paramètre de champ radio pour activer la personnalisation
   du style pour la vue photo avec l'extension: Tribe Ext Alternative Photo View  */
function ecet_enable_events_alternative_photo_view_style_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_enable_events_alternative_photo_view_style_radio_field'] ) ? $settings['ecet_enable_events_alternative_photo_view_style_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_enable_events_alternative_photo_view_style_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_enable_events_alternative_photo_view_style_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
	
	echo '<span id="section_events_calendar_month_view"></span>';
}


/* ----------- AFFICHAGE DES CHAMPS POUR LA 9ème SECTION: The Events Calendar Vue Mois  -----------------------------------------*/

/* Affiche le paramètre de champ radio pour activer la personnalisation
   de l'info-bulle pour la vue mois  */
function ecet_enable_tooltip_customization_month_view_radio_field_markup( $args ){
	
    $settings = get_option( 'ecet_multiple_setting' );
    $checked = ! empty( $settings['ecet_enable_tooltip_customization_month_view_radio_field'] ) ? $settings['ecet_enable_tooltip_customization_month_view_radio_field'] : 'no';
    
	?>
        <fieldset>
            <legend class="screen-reader-text"><?php esc_html_e( 'no', 'custom-event-tickets' ); ?></legend>
                <label for="settings_ecet_radio_option_1">
                    <input id="settings_ecet_radio_option_1" type="radio" name="ecet_multiple_setting[ecet_enable_tooltip_customization_month_view_radio_field]" value="no" <?php checked( $checked, 'no' ); ?>>
                    <?php esc_html_e( 'no', 'custom-event-tickets' ); ?>  <!--  label , text domain  -->
                </label>
                <br>
                <label for="settings_ecet_radio_option_2">
                    <input id="settings_ecet_radio_option_2" type="radio" name="ecet_multiple_setting[ecet_enable_tooltip_customization_month_view_radio_field]" value="yes" <?php checked( $checked, 'yes' ); ?>>
                    <?php esc_html_e( 'yes', 'custom-event-tickets' ); ?>
                </label>
        </fieldset>
		
    <?php
}

/* ----------------- NETTOYAGE DES VALEURS DES CHAMPS AVANT ENTRÉE EN BASE DE DONNÉES ----------------------*/



 /* Fonction de nettoyage pour nos valeurs de réglages avant l'entrée en base de données
 $settings Un tableau de paramètres, 
 avec en index l'ID des champs de réglage déclaré avec la fonction: add_settings_field() )
 */
function ecet_multiple_settings_sanitize( $settings ){
	
	/*-------------------------------------Sanitize 1ère SECTION  Paramètres par défaut -------------------------------------*/
	
	// Sanitize Radio
	// sanitize_key:  Nettoie une clé de chaîne.
    $settings['ecet_radio_default_setting_field']    = sanitize_key( $settings['ecet_radio_default_setting_field'] ) ;
	
	// Sanitize Radio delete data uninstallation
	$settings['ecet_radio_delete_data_uninstallation_field']    = sanitize_key( $settings['ecet_radio_delete_data_uninstallation_field'] ) ;
	
	/*----------------------------------- Sanitize 2ème SECTION  Sélectionner la version de vos extensions -------------------------------------*/
	
	// Sanitize Radio default version extension events
    $settings['ecet_default_version_extension_events_radio_field'] = sanitize_key( $settings['ecet_default_version_extension_events_radio_field'] ) ;
    
	
	/*------------------------------------ Sanitize 3ème SECTION Table des participants -------------------------------------*/
	
	// Sanitize Radio enable participants table
    $settings['ecet_enable_participants_table_radio_field'] = sanitize_key( $settings['ecet_enable_participants_table_radio_field'] ) ;
    
	// Sanitize Text field
    $settings['ecet_text_field']     = ! empty( $settings['ecet_text_field'] ) ? sanitize_text_field( $settings['ecet_text_field'] ) : '';
	
	// Sanitize Select title tag field
    $settings['ecet_select_title_tag_field']   = ! empty( $settings['ecet_select_title_tag_field'] ) ? sanitize_key( $settings['ecet_select_title_tag_field'] ) : '' ;
	
	// Sanitize Select column field
    $settings['ecet_select_column_field']   = ! empty( $settings['ecet_select_column_field'] ) ? sanitize_key( $settings['ecet_select_column_field'] ) : '' ;
    
	// Sanitize Radio participant name format
    $settings['ecet_participant_name_format_radio_field'] = sanitize_key( $settings['ecet_participant_name_format_radio_field'] ) ;
    
	// Sanitize Radio responsive tables
    $settings['ecet_participant_responsive_tables_radio_field'] = sanitize_key( $settings['ecet_participant_responsive_tables_radio_field'] ) ;
   
	
		
	/*-------------------------------------  Sanitize 4ème SECTION Style table des participants -------------------------------------*/	
	
	// Sanitize Line Color field
    $settings['ecet_color_line_field']     = ! empty( $settings['ecet_color_line_field'] ) ? sanitize_text_field( $settings['ecet_color_line_field'] ) : '';
    
	// Sanitize Select border width field
    $settings['ecet_select_border_width_field']   = ! empty( $settings['ecet_select_border_width_field'] ) ? sanitize_key( $settings['ecet_select_border_width_field'] ) : '' ;
	
	// Sanitize color border field
    $settings['ecet_color_border_field']     = ! empty( $settings['ecet_color_border_field'] ) ? sanitize_text_field( $settings['ecet_color_border_field'] ) : '';
	
	// Sanitize Select font size field
    $settings['ecet_select_font_size_field']   = ! empty( $settings['ecet_select_font_size_field'] ) ? sanitize_key( $settings['ecet_select_font_size_field'] ) : '' ;
	
	// Sanitize font color field
    $settings['ecet_font_color_field']     = ! empty( $settings['ecet_font_color_field'] ) ? sanitize_text_field( $settings['ecet_font_color_field'] ) : '';
	
	
	/*--------------------------------------  Sanitize 5ème SECTION Block Guteneberg RSVP ------------------------------------- */
	
	// Sanitize Text field
    $settings['ecet_rsvp_button_text_field']     = ! empty( $settings['ecet_rsvp_button_text_field'] ) ? sanitize_text_field( $settings['ecet_rsvp_button_text_field'] ) : '';
	
	// Sanitize Text field
    $settings['ecet_text_below_number_of_participants_text_field']     = ! empty( $settings['ecet_text_below_number_of_participants_text_field'] ) ? sanitize_text_field( $settings['ecet_text_below_number_of_participants_text_field'] ) : '';
	
	// Sanitize Text field
    $settings['ecet_closed_reservation_text_field']     = ! empty( $settings['ecet_closed_reservation_text_field'] ) ? sanitize_text_field( $settings['ecet_closed_reservation_text_field'] ) : '';
	
	// Sanitize Text Area
	// wp_kses_post : Nettoie le contenu des balises HTML autorisées pour le contenu des articles
	$settings['ecet_form_reservation_textarea_field'] = ! empty( $settings['ecet_form_reservation_textarea_field'] ) ? wp_kses_post( $settings['ecet_form_reservation_textarea_field'] ) : '' ;
	
	
	/*--------------------------------------  Sanitize 6ème The Event Calendar Vue Liste ------------------------------------- */
	
	// Sanitize Radio enable custom events list view style
    $settings['ecet_enable_custom_events_list_view_style_radio_field'] = sanitize_key( $settings['ecet_enable_custom_events_list_view_style_radio_field'] ) ;
    
	// Sanitize Text field event number of participants
    $settings['ecet_event_number_participants_text_field']  = ! empty( $settings['ecet_event_number_participants_text_field'] ) ? sanitize_text_field( $settings['ecet_event_number_participants_text_field'] ) : '';
	
	// Sanitize Text field event remaining tickets
	$settings['ecet_event_remaining_tickets_text_field']     = ! empty( $settings['ecet_event_remaining_tickets_text_field'] ) ? sanitize_text_field( $settings['ecet_event_remaining_tickets_text_field'] ) : '';
	
	// Sanitize Radio enable list view categoty tag
    $settings['ecet_enable_list_view_category_tag_radio_field'] = sanitize_key( $settings['ecet_enable_list_view_category_tag_radio_field'] ) ;
    
	
	/* Sanitize Checkbox
    $settings['ecet_checkbox_field'] = isset( $settings['ecet_checkbox_field'] ) ;

	// Sanitize Text Area
	// wp_kses_post : Nettoie le contenu des balises HTML autorisées pour le contenu des articles
	$settings['ecet_textarea_field'] = ! empty( $settings['ecet_textarea_field'] ) ? wp_kses_post( $settings['ecet_textarea_field'] ) : '' ;

	*/
	
	/*--------------------------------------  Sanitize 7ème The Event Calendar Vue Photo ------------------------------------- */
	
	// Sanitize Radio enable custom events photo view style
    $settings['ecet_enable_custom_events_photo_view_style_radio_field'] = sanitize_key( $settings['ecet_enable_custom_events_photo_view_style_radio_field'] ) ;
    
	// Sanitize Radio add effect hover photo view event
    $settings['ecet_add_effect_hover_photo_view_event_radio_field'] = sanitize_key( $settings['ecet_add_effect_hover_photo_view_event_radio_field'] ) ;
    
	// Sanitize Radio moving event date inside photo
    $settings['ecet_moving_event_date_inside_photo_radio_field'] = sanitize_key( $settings['ecet_moving_event_date_inside_photo_radio_field'] ) ;
    
	// Sanitize Radio venue details photo view event
    $settings['ecet_venue_details_photo_view_event_radio_field'] = sanitize_key( $settings['ecet_venue_details_photo_view_event_radio_field'] ) ;
	
	// Sanitize Radio add attendees number photo view event
    $settings['ecet_add_attendees_number_photo_view_event_radio_field'] = sanitize_key( $settings['ecet_add_attendees_number_photo_view_event_radio_field'] ) ;
    
	// Sanitize Radio enable photo view category tag
    $settings['ecet_enable_photo_view_category_tag_radio_field'] = sanitize_key( $settings['ecet_enable_photo_view_category_tag_radio_field'] ) ;
    
	
	
	
	/*---------Sanitize 8ème The Event Calendar Vue Photo Vue Photo personnalisé par extension:Tribe Ext Alternative Photo View ------------------------------------- */
	
	// Sanitize Radio add attendees number photo view event
    $settings['ecet_enable_events_alternative_photo_view_style_radio_field'] = sanitize_key( $settings['ecet_enable_events_alternative_photo_view_style_radio_field'] ) ;
    
	
	/*---------Sanitize 9ème The Events Calendar Vue Mois ------------------------------------- */
	
	// Sanitize Radio add attendees number photo view event
    $settings['ecet_enable_tooltip_customization_month_view_radio_field'] = sanitize_key( $settings['ecet_enable_tooltip_customization_month_view_radio_field'] ) ;
    
	
	
	return $settings;
	
}
 