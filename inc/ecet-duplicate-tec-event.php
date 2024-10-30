<?php

/************************************************************
*
*  Easy Custom Event Tickets [REV:2.1.1] 
*
* 
************************************************************/

// on vérifie que nous sommes dans wp-admin avant d'effectuer 
// toute action supplémentaire
if( is_admin() ) {
    add_action( 'admin_init', 'ecet_init' );
}



/* ================================================================================ 
	                           INITIALISATION
	• S'assurer que TEC (The Events Calendar) est disponible
	- Si oui : 
		ajoute l'action 'Dupliquer Évènement' à la page qui liste les évènements
		Vérifie si la duplication doit être effectuée en fonction du paramètre 
		action passé par l'URL
	- Si non:
	Affiche une notice d'erreur informant d'installer TEC 
================================================================================ */

function ecet_init() {
	
    // vérifier que le plugin TEC existe
    if( class_exists( 'Tribe__Events__API' ) ) {
		
        // Configurer un lien d'action sur la liste des événements à dupliquer
        add_filter( 'post_row_actions', 'ecet_row_actions', 10, 2);

        if( isset( $_GET['action'] ) 
                && 'duplicate_tribe_event' == $_GET['action'] 
                && isset( $_GET['post'] )
                && is_numeric( $_GET['post'] )
        ) {
            ecet_duplicate_tribe_event();
        }
		
    } else {
        // On met en file d'attente une thickbox native a WordPress
		// afin ensuite d'afficher un fenêtre modale d'alerte
		// indiquant à l'utilisateur d'installer TEC
        add_action( 'admin_enqueue_scripts', 'add_thickbox' );
        add_action( 'admin_notices', 'ecet_admin_notice_install_tec' );
    }
}


/* ================================================================================ 
	               NOTIFICATION D'INSTALLATION THE EVENTS CALENDAR
	• Affiche une notification dans wp-admin informant l'utilisateur d'installer TEC.
	  Fournit un lien vers le programme d'installation
================================================================================ */
/**
 * Affiche une notification dans wp-admin informant l'utilisateur d'installer TEC.
 * Fournit un lien vers le programme d'installation
 */
function ecet_admin_notice_install_tec() {    
    $url = admin_url('plugin-install.php?tab=plugin-information&plugin=the-events-calendar&TB_iframe=true&width=640&height=517');
    
    echo '<div class="error">
       <p>'.esc_html__( 'You need to install the plugin ', 'custom-event-tickets' ).'<a href="'.$url.'" class="thickbox onclick">The Events Calendar</a>'.esc_html__( ' before enabling the Easy Custom Event Tickets plugin!', 'custom-event-tickets' ).'</p>
    </div>';
}




/* ================================================================================ 
	                         LIEN D'ACTION
	• Ajoute un lien action supplémentaire: Dupliquer Évènement à la page 
	  qui liste les évènements
	  
	@param array $actions
    @param type $post
    @return string 
================================================================================ */
/**
 * 
 * @param array $actions
 * @param type $post
 * @return string 
 */
function ecet_row_actions( $actions, $post ) {
    
	// Avant de modifier les actions disponibles, 
	// on vérifie qu'on est sur la page des événements
    if( $post->post_type != 'tribe_events' ) return $actions;

    // On crée une nonce de sécurité inséré dans l'URL
	$nonce = wp_create_nonce( 'dte_duplicate_event' );
	
    $actions['duplicate_tribe_event'] = '<a href=\''.admin_url('?post_type=tribe_events&action=duplicate_tribe_event&post='.$post->ID).'&_nonce=' . $nonce . '\' >'.esc_html__( 'Duplicate Event', 'custom-event-tickets' ).'</a>';
    
    return $actions;
	
}


/* ================================================================================ 
	                         DUPLICATION ÉVÈNEMENT
	C'est la fonction principale qui duplique un évènement
	• On récupère dans un tableau le contenu de l'évènement d'origine
	• On récupère un objet pour le ticket de l'évènement d'origine 
	• s'il n' y a pas de billet
		- alors le contenu pour l'évènement dupliqué sera identique a l'èvènement 
		d'origine
		- sinon on modifie dans le contenu de l'évènement le balisage du ticket Payant
		a un ticket vierge, on laisse le balisage initial pour un ticket RSVP.
	• On crée le nouvel évènement vierge de contenu et on ajoute:
		- le contenu modifié de l'évènement d'origine 
		- les métadonnées de l'évènement d'origine
		- les taxonomies de l'évènement d'origine
	• S'il y a un ticket payant pour l'évènement d'origine alors pour l'évènement dupliqué:
		- On crée un ticket avec les mêmes paramètres que l'évènement d'origine
		- Important! on met a jour les métas suivantes:
			_tribe_tickets_list avec l'ID du nouveau ticket ( nous servira 
			pour l'écran d'edition rapide)
			_tribe_tickets_ar_iac avec l'option choisie pour la collecte 
			individuelle des participants
		- Mise a jour dans la table wp_posts du balisage complet du bloc ticket
		pour renseigner q'un ticket est crée donc paramètré et son ID
	• s'il y a un ticket RSVP pour l'évènement d'origine alors pour l'évènement dupliqué:
		- On crée un ticket avec les mêmes paramètres que l'évènement d'origine
		- Important! on met a jour la méta suivante:
			_tribe_tickets_list avec l'ID du nouveau ticket ( nous servira 
			pour l'écran d'edition rapide)
	• On renvoi a la page qui liste les évènements
	
	REMARQUE: Les nouvelles date de l'évènement et des tickets seront mis a jour
			   a partir de l'écran d'édition rapide
			   géré par les fichiers: ecet-quick-edit.php & ecet-update-fields-quick-edit.js
	
================================================================================ */

/* ================================================================================ 
	                        COMPLÉMENT  BALISAGE BLOC TICKETS
							 
Balisage d'un ticket payant non paramètré tel qu'établi
lors de la création d'un évènement:
<!-- wp:tribe/tickets -->
<div class="wp-block-tribe-tickets"></div>
<!-- /wp:tribe/tickets -->

Balisage d'un ticket RSVP qui reste identique paramètré ou non:
<!-- wp:tribe/rsvp /-->

Balisage d'un ticket payant paramètré:
<!-- wp:tribe/tickets -->
<div class="wp-block-tribe-tickets"><!-- wp:tribe/tickets-item {"hasBeenCreated":true,"ticketId":9779} -->
<div class="wp-block-tribe-tickets-item"></div>
<!-- /wp:tribe/tickets-item --></div>
<!-- /wp:tribe/tickets -->				 
							 
Balisage de deux tickets Payant paramétrés + 1 ticket RSVP paramètrés
<!-- wp:tribe/tickets -->
<div class="wp-block-tribe-tickets"><!-- wp:tribe/tickets-item {"hasBeenCreated":true,"ticketId":9636} -->
<div class="wp-block-tribe-tickets-item"></div>
<!-- /wp:tribe/tickets-item -->

<!-- wp:tribe/tickets-item {"hasBeenCreated":true,"ticketId":9640} -->
<div class="wp-block-tribe-tickets-item"></div>
<!-- /wp:tribe/tickets-item --></div>
<!-- /wp:tribe/tickets -->

<!-- wp:tribe/rsvp /-->

================================================================================ */

function ecet_duplicate_tribe_event() {
	
	$data_item = '';
	$new_closing_tag = '';
	
    if( !isset( $_GET['_nonce'] ) || !wp_verify_nonce( $_GET['_nonce'], 'dte_duplicate_event' ) )
            return false;
    
    // l'ID de l'évènement a été passé 
	// par le paramètre post de l'URL
	$event_id = $_GET['post'];
    
    if ( !class_exists( 'Tribe__Events__API' ) ) 
        return false;
	
    
	// On récupère un objet de l'évènement d'origine
	// que l'on transforme en tableau avec la fonction (array)
	// Le titre est donné par $event['post_title']
	// Le contenu par $event['post_content']  etc..
	$event = (array)get_post( $event_id );
	
	// on récupère les billets de la publication a dupliquer($event_id)
	// c'est a dire les billets payant mais aussi RSVP
	// get_all_event_tickets() étant une méthode statique on n'a pas besoin
	// d'instancier la classe
	$tickets = Tribe__Tickets__Tickets::get_all_event_tickets( $event_id );
	
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
	
	
	$nbr_tickets =  intval($all_tickets['tickets']['count']);
	
	$has_RSVP = intval($all_tickets['rsvp']['count']);
	
	
	/* pour debug 
	echo'<pre>';
	echo'Données de tous les tickets: '.'<br>';
	print_r($tickets).'<br>';
	echo'Nombre ticket RSVP: '.$has_RSVP.'<br>';
	echo'Nombre ticket PAYANT: '.$nbr_tickets.'<br>';
	echo'</pre>';
	exit;
	*/
	
	// balise ouverture du bloc ticket avec en fin un retour a la ligne
	// pour juste une question de présentation
	$original_open_tag = '<!-- wp:tribe/tickets -->
<div class="wp-block-tribe-tickets">';
	
	// balise fermeture du bloc ticket
	$original_closing_tag = '</div><!-- /wp:tribe/tickets -->';
	
	
	// s'il y a des tickets payant & un ticket RSVP 
	if( $nbr_tickets && $has_RSVP ) {
		
		// balise fermeture des blocs tickets avec en fin un retour a la ligne
		// juste pour une question de présentation
		$original_closing_tag = '</div><!-- /wp:tribe/tickets -->
<!-- wp:tribe/rsvp /-->';
		
		// balises pour le champ post_content de la table wp_post
		// pour un ticket payant & RSVP restant a paramètrer
		$ticket_tag = $original_open_tag.$original_closing_tag; 
				  
		// du contenu de la publication d'origine: $event['post_content']
		// on supprime les informations sur les tickets.
		// On utilise une expression régulière qui cible tout le texte entre les balises
		// <!-- wp:tribe/tickets --> et <!-- /wp:tribe/tickets -->, 
		// y compris les balises elles-mêmes, pour les remplacer par $ticket_tag
		// le ticket avec ses balises est définie dans le champ post_event de la table wp_posts
		$new_content = preg_replace( '/<!-- wp:tribe\/tickets -->[\s\S]*?<!-- \/wp:tribe\/tickets -->/', $ticket_tag, $event['post_content'] );

	// sinon s'il y a que des tickets payant
	}elseif ( $nbr_tickets && !$has_RSVP ) {
		
		// balise pour le champ post_content de la table wp_post
		// pour un ticket payant 
		$ticket_tag = $original_open_tag.$original_closing_tag;
				  
		$new_content = preg_replace( '/<!-- wp:tribe\/tickets -->[\s\S]*?<!-- \/wp:tribe\/tickets -->/', $ticket_tag, $event['post_content'] );
		
	// sinon il n'y a qu'un ticket RSVP ou pas du tout
	}else {
		
		// pour un billet RSVP paramètré le balisage suivant: <!-- wp:tribe/rsvp /--> 
		// n'est pas modifié dans le champ post_content de la table wp_posts	
		// contrairement au billet payant
		$new_content = $event['post_content'];
		
	}
	
				 
	// On récupère les champs méta de publication, 
	// en fonction de l'ID de publication.
    $meta = get_post_custom( $event_id );
     
    $all_meta = array();
    
	foreach( $meta AS $meta_key => $meta_value ) {
        $all_meta[$meta_key] = $meta_value[0];
    }
	
	// paramètres pour créer le nouvel évènement
	// on peut aussi obtenir la date de début et de fin de l'évènement ainsi
	// date( 'Y-m-d H:i:s', strtotime( get_post_meta( $event_id, '_EventStartDate' , true  ) ) );
	// date( 'Y-m-d H:i:s', strtotime( get_post_meta( $event_id, '_EventEndDate' , true  ) ) );
    $args = [
        'title'      => $event['post_title'],
        'start_date' => tribe_get_start_date($event_id, false, 'Y-m-d H:i:s'),
        'end_date'   => tribe_get_end_date($event_id, false, 'Y-m-d H:i:s'),
        'status'  => 'draft',
    ];
	
	// Utiliser tribe_events() pour créer le nouvel évènement 
	// avec les arguments définis
	// https://docs.theeventscalendar.com/apis/orm/create/events/
	// https://docs.theeventscalendar.com/apis/orm/create/events/examples/
	// on créé alors une nouvelle publication dans la table wp_posts
    $new_event = tribe_events()->set_args($args)->create();
	
	// Si la création est réussie, on peut mettre à jour le contenu de la publication
	if (!empty($new_event)) {
		
		// ID de l'évènement dupliqué
		$new_event_id = $new_event->ID;
		
		// Mettre à jour le contenu du nouvel événement
		wp_update_post([ 'ID' => $new_event_id, 'post_content' => $new_content ]);

		// Ajouter les métadonnées à l'événement dupliqué
		// Remarque: ces métadonnées ajoute entre autre l'image en avant
		// dans la barre latérale de l'editeur et renseigne les champs personnalisées
		// du plugin The Event Calendar
		foreach( $all_meta AS $meta_key => $meta_value ) {
			update_post_meta( $new_event_id, $meta_key, $meta_value );
		}

		// On récupère les taxonomies pour le type de publication personnalisées
		// que sont les évènements
		$taxonomies = get_object_taxonomies( 'tribe_events' );
    
		//Ajouter les termes de taxonomie à l'événement dupliqué
		foreach ($taxonomies as $taxonomy) {
			$terms = wp_get_object_terms($event_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_event->ID, $terms, $taxonomy, false);
		}
		
	}
	
	
	// s'il y a des tickets payant
	if( $nbr_tickets ) {
		
		$ticket_index = 0;
		
		for ( $index=0;$index<$nbr_tickets + $has_RSVP ;$index++ ) {
			
			// on récupère le type de billet
			// selon la classe pour la passerelle de paiement
			// provider_class = Tribe__Tickets__RSVP pour un billet gratuit
			// provider_class = Tribe__Tickets__Commerce__PayPal__Main pour un billet payant avec PayPal
			// Remarque: $payment_gateway = Tribe__Tickets__Tickets::get_event_ticket_provider($event_id);
			// retourne la passerelle de paiement par défaut
			$provider_class = $tickets[$index] -> provider_class;
			
			// on récupère l'ID du ticket pour l'évènement à dupliquer
			$ticket_id = $tickets[$index] -> ID; 
			
			// si ce n'est pas un ticket RSVP
			if ( $provider_class !== 'Tribe__Tickets__RSVP') {;
		
				$ticket_index ++;
				
				// Créer un tableau avec les mêmes arguments que le ticket original, sauf l'ID
				// remarque: tribe-ticket'  => ['mode' correspond  a la capacité du ticket:
				// - set capacity for this thicket only alors la méta _global_stock_mode = own
				// - share capacity with other tickets => méta _global_stock_mode = capped
				// - Unlimited => méta _global_stock_mode est vide
				// 'ticket_provider' correspond a la classe de la passerelle de paiement
				$new_ticket_args = array(
					'ticket_id'        	 		=> '' ,
					'ticket_name'        		=> $tickets[$index] -> name,
					'ticket_description' 		=> $tickets[$index] -> description,
					'ticket_price'       	  	=> $tickets[$index] -> price,
					'ticket_show_description' 	=> $tickets[$index] -> show_description,
					'ticket_provider' 			=> $tickets[$index] -> provider_class,
					'ticket_start_date'  		=> $tickets[$index] -> start_date,//get_post_meta( $ticket_id , '_ticket_start_date' , true  )
					'ticket_end_date'    		=> $tickets[$index] -> end_date, //get_post_meta( $ticket_id , '_ticket_end_date' , true  )
					'ticket_menu_order' 		=> $tickets[$index] -> menu_order,
					'ticket_start_time'        	=> $tickets[$index] -> start_time,
					'ticket_end_time'          	=> $tickets[$index] -> end_time,
					'ticket_sku'               =>  $tickets[$index] -> sku,
					'tribe-ticket'     			=> [
														'mode'            => $tickets[$index] -> global_stock_mode ,
														'event_capacity' => 0,
														'capacity'       => $tickets[$index] -> capacity
													],
				);
				
				// création du ticket
				// selon https://gist.github.com/ethanclevenger91/98f1101ca0da176de5cbb3a08bf3c05a
				// on crée une nouvelle publication pour le ticket dans la table wp_posts
				// avec le post_type: tribe_tpp_tickets
				// on enregistre dans la table wp_postmeta les métadonnées du ticket
				// que l'on a passés a la méthode ticket_add()
				// la méthode ticket_add() faisant elle même appel a la méthode save_ticket()
				// la métadonnée du ticket qui fait la relation avec l'évènement est: _tribe_tpp_for_event
				// elle reçoit l'ID de l'évènement
				// https://docs.theeventscalendar.com/reference/classes/tribe__tickets__tickets/ticket_add/
				// https://docs.theeventscalendar.com/reference/classes/tribe__tickets__tickets/save_ticket/
				$new_ticket_id = $provider_class::get_instance() -> ticket_add( $new_event_id , $new_ticket_args );
				
				// Remarque pour supprimer un ticket par exemple sur la publication d'origine
				// $provider_class::get_instance() -> delete_ticket( $event_id, $ticket_id);
			
				// si la création du ticket a réussi 
				if($new_ticket_id ){
					
					if ($ticket_index == 1) {
						// on met a jour la méta de l'évènement qui le lie au ticket référent
						// c'est a dire le premier ticket
						update_post_meta( $new_event_id, '_tribe_tickets_list', $new_ticket_id );
					}
					
					// On récupère pour le ticket d'origine  la méta pour 
					// la collecte des infos des participants
					// pas de collecte individuelle des participants: none
					// Autoriser la collecte individuelle des participants: allowed 
					// Exiger la collecte individuelle des participants: required
					$attendee_collection = get_post_meta( $ticket_id,'_tribe_tickets_ar_iac',true );
					
					if(!empty($attendee_collection)){
						
						// on met a jour la méta pour le ticket du nouvel évènement
						update_post_meta( $new_ticket_id , '_tribe_tickets_ar_iac', $attendee_collection );
					}
					
					// On concaténe le balisage pour les items de ticket
					// avec en fin un retour a la ligne juste
					// pour une question de présentation		
					// On renseigne q'un ticket est crée avec son ID.
					// cela permettra au bloc Ticket de gutenberg d'accéder aux métadonnées
					// soit les paramètres du ticket
					$data_item .= '<!-- wp:tribe/tickets-item {"hasBeenCreated":true,"ticketId":'.$new_ticket_id.'} -->
<div class="wp-block-tribe-tickets-item"></div>
<!-- /wp:tribe/tickets-item -->
';
					
				}// Fin si la création du ticket a réussi
				
				/* pour debug 
				echo'<pre>';
				echo'<strong> Argument Ticket Payant </strong>'.'<br>';
				print_r($new_ticket_args).'<br>';
				echo'<strong> nouvel ID Ticket: </strong>'.$new_ticket_id .'<br>';
				echo'</pre>';
				exit;
				*/
				
			}// fin si ce n'est pas un ticket RSVP
			
		}// fin boucle for
		
		
		// Mise a jour du balisage de fin du bloc Ticket:
		$new_closing_tag .= $data_item;
		$new_closing_tag .= $original_closing_tag;
		
		$new_content = str_replace( $original_closing_tag, $new_closing_tag, $new_content );
		
		// Mise à jour du contenu du nouvel évènement avec le balisage complet du ticket
		// dans le champ post_content de la table wp_posts
		wp_update_post([ 'ID' => $new_event_id, 'post_content' => $new_content ]);

	
	}// fin s'il y a des tickets payant
	
	
	// s'il y a un ticket RSVP
	if( $has_RSVP ) {
		
		for ( $index=0;$index<$nbr_tickets + $has_RSVP;$index++ ) {
			
			$provider_class = $tickets[$index] -> provider_class;
			
			// si c'est un ticket RSVP
			if ( $provider_class == 'Tribe__Tickets__RSVP') {;
		
				// Créer un tableau avec les mêmes arguments que le ticket original, sauf l'ID
				$new_ticket_args = array(
					'ticket_id'        	 		=> '' ,
					'ticket_name'        		=> $tickets[$index] -> name,
					'ticket_description' 		=> $tickets[$index] -> description,
					'ticket_price'       	  	=> $tickets[$index] -> price,
					'ticket_show_description' 	=> $tickets[$index] -> show_description,
					'ticket_provider' 			=> $tickets[$index] -> provider_class,
					'ticket_start_date'  		=> $tickets[$index] -> start_date, //get_post_meta( $ticket_id , '_ticket_start_date' , true  ),
					'ticket_end_date'    		=> $tickets[$index] -> end_date, //get_post_meta( $ticket_id , '_ticket_end_date' , true  ),
					'ticket_start_time'        	=> $tickets[$index] -> start_time,
					'ticket_end_time'          	=> $tickets[$index] -> end_time,
					'ticket_menu_order' 		=> $tickets[$index] -> menu_order,
					'tribe-ticket'     			=> [
														'mode'            => $tickets[$index] -> global_stock_mode ,
														'event_capacity' => 0,
														'capacity'       => $tickets[$index] -> capacity
													],
				);
				
				
				// création du ticket
				// selon https://gist.github.com/ethanclevenger91/98f1101ca0da176de5cbb3a08bf3c05a
				// on crée une nouvelle publication pour le ticket dans la table wp_post
				// avec le post_type: tribe_tpp_tickets
				// on enregistre dans la table wp_postmeta les métadonnées du ticket
				// que l'on a passés a la méthode ticket_add()
				// la méthode ticket_add() faisant elle même appel a la méthode save_ticket()
				// la métadonnée du ticket qui fait la relation avec l'évènement est: _tribe_tpp_for_event
				// elle reçoit l'ID de l'évènement
				// https://docs.theeventscalendar.com/reference/classes/tribe__tickets__tickets/ticket_add/
				// https://docs.theeventscalendar.com/reference/classes/tribe__tickets__tickets/save_ticket/
				$new_ticket_id = $provider_class::get_instance() -> ticket_add( $new_event_id , $new_ticket_args );
				
				/* pour debug
				echo'<pre>';
				echo'<strong> Argument Ticket RSVP </strong>'.'<br>';
				print_r($new_ticket_args).'<br>';
				echo'<strong> nouvel ID Ticket: </strong>'.$new_ticket_id .'<br>';
				echo'</pre>';
				exit;
				 */ 
				
			}// Fin si c'est un ticket RSVP
		
		}// fin boucle for
		
	}
	
	/* pour debug
	echo'<pre>';
	echo'ID ticket nouvel évènement = '.$new_ticket_id.'<br>';
	echo'</pre>';
	*/
	
    // On renvoi a la page qui liste les évènements
	wp_redirect(admin_url("edit.php?post_type=tribe_events" ) ); 
	exit;
	
}