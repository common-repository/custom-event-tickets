<?php

/************************************************************
*
*  Easy Custom Event Tickets [REV:2.1] 
*
* 
************************************************************/


/* Empêche l'utilisateur public d'accéder directement aux fichiers .php via l'URL
et garantit que les fichiers du plugin seront exécutés uniquement dans l'environnement WordPress.*/
defined( 'ABSPATH' ) || die();


/******************************
 * Setting up the admin pages
 ******************************/

// On utilise le hook admin menu pour ajouter notre menu 
// pour la page de réglages du plugin
add_action( 'admin_menu', 'ecet_menu_items' );



 /* On enregistre notre nouvel élément de menu*/
 function ecet_menu_items() {
    // On crée un élément de menu de niveau supérieur
    $hookname = add_menu_page(
        __( 'Easy Custom Event Tickets Settings', 'custom-event-tickets' ),  // Page title , Text Domain(pour la traduction)
        __( 'ECET Settings', 'custom-event-tickets' ),  // Menu title, Text Domain(pour la traduction)
        'manage_options',                     // Capabilities (Capacités)
        'ecet_settings_page',              // Slug
        'ecet_settings_page_markup',       // Display callback (fonction permettant l'affichage du contenu de la page de réglages)
        'dashicons-editor-table',          // Icon
        66                                 // Priority/position. Just after 'Plugins'
    );
}


/* Fonction de rappel qui va contenir le balisage de la page et donc nos réglages 
   on utilise la classe CSS de wrap, qui est utilisée un peu partout dans l’administration de WordPress 
   - On Affiche le Titre de la page de réglages
   - On place un élément <form> qui va pointer vers options.php , et qui va envoyer ses données en POST.
   - La fonction settings_fields() prend un unique paramètre correspondant au nom d’un groupe de réglages 
     (définit dans setting.php fonction: ecet_multiple_setting() )
     settings_fields() va afficher les champs cachés nécessaires pour traiter notre formulaire:
	 les champs nonce, referer, action, et option_page  
   - La fonction do_settings_sections() permet boucler sur nos sections et afficher les champs de réglages correspondants.
     elle prend en argument le slug de la page de réglages définit dans la fonction: ecet_menu_items() 
   - Le bouton de soumission du formulaire
*/
function ecet_settings_page_markup(){
	
    ?>
	    
        <div class="wrap">
		
            <h1><?php echo esc_html_e( get_admin_page_title(),'custom-event-tickets' ); ?></h1> <!-- On Affiche le Titre de la page de réglages avec le text domain donné en référence pour la traduction -->
			
			<?php
			// Bouton de retour en haut de page
			echo'<a href="#top" class="scroll-top"></a>';
			
			
			// **************** SOMMAIRE **************************
			echo '<div class="bloc-intro table-of-content" id="section_custom_back_office" >';
			
				echo '<span style="font-weight:bold;">'.esc_html__( 'SUMMARY','custom-event-tickets' ).'</span>';
				echo '<br>';
				echo '<a style="text-transform: uppercase;" href="#section_default_settings">'. esc_html__( 'Default settings', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_plugins_version">'. esc_html__( 'Select your extension version', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_attendees_table">'. esc_html__( 'Participants table', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_attendees_table_style">'. esc_html__( 'Participants table style', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_rsvp_block_style">'. esc_html__( 'RSVP Gutenberg block style', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_events_calendar_list_view">'. esc_html__( 'Custom the events calendar list view', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_events_calendar_photo_view">'. esc_html__( 'Custom the pro version events calendar photo view', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_events_calendar_alternative_photo_view">'. esc_html__( 'Custom Photo View by Extension:Tribe Ext Alternative Photo View', 'custom-event-tickets' ).'</a>';
				echo '<br>';
				echo '<a  style="text-transform: uppercase;" href="#section_events_calendar_month_view">'. esc_html__( 'Customize the month view of the calendar of events', 'custom-event-tickets' ).'</a>';
				echo '<br>';
			echo '</div>';
			
			echo '<span id="section_default_settings"></span>';
			
			?>
			
			<form action="options.php" method="POST">
			
				<?php
                    settings_fields( 'settings_ecet' );// afficher les champs nonce, referer, action, et option_page( en paramètre le settings group de réglage définit dans le fichier multiple-setting.php) 
					do_settings_sections( 'ecet_settings_page' );// permet  de boucler sur nos sections et afficher les champs de réglages correspondants ( en paramètre le slug de la page de réglages)
					submit_button( __( 'Save Settings', 'custom-event-tickets' ));// le bouton de soumission du formulaire( Enregister les paramètres) 
                ?>
            
			</form>
			
        </div>
		
    <?php
	
}