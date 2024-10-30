<?php

/************************************************************
*
*  Easy Custom Event Tickets [REV:2.1.1] 
*
* 
************************************************************/


/*------ Ajouter des champs d'édition rapide en HTML pour les Métas de publication -------*/

// Étape 1. Ajouter des champs date en HTML dans l'édition rapide

// le hook quick_edit_custom_box permet d'ajouter du HTML dans Quick Edit(edition rapide)
add_action( 'quick_edit_custom_box','ecet_quick_edit',1,2 );

/*
Cette Fonction permet d'ajouter des champs date dans l'edition rapide
Pour modifier les dates de début & de fin d'un évènement

Pour regrouper tous les champs dans une seule balise  <fieldset>
j'ai ajouté une <fieldset> balise d'ouverture lors de l'affichage du premier champ date 
et une balise de fermeture </fieldset> lors de l'affichage du dernier champ date .

Les classes inline-edit-col-left,inline-edit-col,title sont native a WordPress.
Nécessite le script Jquery :ecet-quick-edit.js pour fonctionner,
ce script est dans le dossier JS du plugin

*/
function ecet_quick_edit( $column_id, $post_type ) {

	
	switch( $column_id ) {
		
		case 'start-date': {
			?>
				<fieldset id="tribe-event-new-date" class="inline-edit-col-left">
				
					<span class="title">Dates Évènement</span>
					
					<div class="inline-edit-col">
						<label>
							<input type="date" id="new_start_date" name="new_start_date">
							<?php esc_html_e('start date','custom-event-tickets')?>
						</label>
					</div>
					
			<?php
			break;
		}
		
		case 'end-date': {
			?>
					<div class="inline-edit-col">
						<label>
							<input type="date" id="new_end_date" name="new_end_date">
							<?php esc_html_e('end date','custom-event-tickets')?>
						</label>
					</div>
					
				</fieldset>
			<?php
			break;
		}
		
	}
	
}


// Étape 2. Remplir les champs dates de l'edition rapide en utilisant les données des colonnes 

// on ne peut pas utiliser par exemple get_post_meta( get_the_ID(),'_EventStartDate',true );
// directement a l'ETAPE 3 via le hook: quick_edit_custom_box dans l'edition rapide pour mettre a jour 
// un champ date car l'ID retourné sera toujours celui du dernier message
// en effet la variable globale  $post auxquel se réfère la fonction get_the_ID()
// renvoie uniquement l'ID du dernier message car les entrées d'édition rapide ne sont créées 
// qu'une seule fois et clonées au besoin lors de l'édition rapide d'une colonne
//
// la mise a jour des champs date devra se faire avec un fichier JS
// en utilisant le hook inlineEditPost.edit qui permet de définir les valeurs d'entrée 
// le fichier JS utilisé est: ecet-update-fields-quick-edit.js
// il se trouve dans le Dossier js de l'extension
// au préalable il faut sélectionner dans l'onglet Affichage des paramètres de réglages
// de l'extension The Events Calendar section Date & Time:
// Format date avec année: d/m/Y 
// Format de date compact: 15/1/2024
// Séparateur de dates et d’heures: /


// Étape 3. Enregistrer les champs d'édition rapide 

// Enregistrer les champs pour les métas de publication après la modification rapide
add_action( 'save_post', 'ecet_quick_edit_save',10, 3 );

function ecet_quick_edit_save( $post_id , $post, $update ){
	
	
	/* ============================================================== 
	                   VÉRIFICATION DE SÉCURITÉ
		• Vérification du nonce de sécurité
	    • Vérification de la capacité d'edition
	    • On exécute cette fonction que pour une mise a jour 
		et on évite de l'exécuter pour:
		- la création d'un évènement
		- lorsque WordPress gère les révisions
		- les sauvegardes automatiques
		On l'exécute uniquement pour le CPT The Events Calendar
	 ================================================================ */

	// Vérifier nonce pour inline edit
    // Ajout isset($_POST['_inline_edit']) pour gestion erreur: undefined array key "_inline_edit"  
	if ( isset($_POST['_inline_edit']) ){
		if(!wp_verify_nonce( $_POST[ '_inline_edit' ], 'inlineeditnonce' )){
			return ;
		}  
	} 
	
	
	// on autorise la modification des métas de publication 
	// que pour les utilisateurs qui ont la capacité d' édition
	if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
	
	// Sinon la fonction se lance dès le clic sur "ajouter" un évènement
	// $update est égal à false si on est dans le cas d’une première création ;
    //$update est égal à true lorsqu’on enregistre un article.
	if( !$update ) {
    	return;
	}

	// On ne veut pas executer le code lorsque c'est une révision
	if( wp_is_post_revision( $post_id ) ) {
		return;
	}

	// On évite les sauvegardes automatiques
	if( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) {
		return;
	}

	// Seulement pour les CPT The Events Calendar
	if( $post->post_type != 'tribe_events' ) {
    	return;
	}


	/* ============================================================== 
	         MISE A JOUR DATE DE DÉBUT & DE FIN DE L'ÉVÈNEMENT
		• Mise a jour:
			- si l'une des nouvelles dates est différente de celle 
			pour l'évènement d'origine
			- si elles ne sont pas vierges
			- si la nouvelle date de fin >= nouvelle date de début
	 ================================================================ */


	// format date: 2023-12-16 20:00:00
	// récupérer les dates de l'évènement d'origine
	$old_event_start_date = get_post_meta( $post_id,'_EventStartDate',true );
	$old_event_end_date = get_post_meta( $post_id,'_EventEndDate',true );
	
	// Vérifier que les dates ne sont pas vides:
	// bizarrement l'edition rapide est exécutée deux fois, 
	/* et à la deuxième fois $old_event_start_date & $old_event_end_date sont vide */
	if ( empty($old_event_start_date) || empty($old_event_end_date) ) {
		return;
	}
	
	
	// Récupérer les heures et les minutes pour la date de début
	// strtotime permet de convertir les anciennes dates en objets DateTime
	// la fonction date pour extraire les heures au format 24H et les minutes.
	$EventStartHour   = date('H', strtotime($old_event_start_date));
	$EventStartMinute = date('i', strtotime($old_event_start_date));
	
	// Récupérer les heures et les minutes pour la date de fin
	$EventEndHour   = date('H', strtotime($old_event_end_date));
	$EventEndMinute = date('i', strtotime($old_event_end_date));
	
	/* pour debug 
	echo'<br>';
	echo'<pre>';
	echo'ID: '.$post_id;
	echo'Ancienne date début: '.$old_event_start_date;
	echo'Nouvelle date début: '.$_POST[ 'new_start_date' ];
	echo'heure début:'.$EventStartHour;
	echo'minute début:'.$EventStartMinute;
	echo'</pre>';
	*/
	
	$new_event_start_date ='';
	$new_event_end_date = '';
	
	
	// Mise a jour des Métas de publication
	// Si l'une des nouvelles dates de l'évènement sélectionnées 
	// est différente de celles en cours pour l'évènement
	// si elles ne sont pas vierges
	// si la nouvelle date de fin >= nouvelle date de début
	if (  isset( $_POST[ 'new_start_date' ] ) && ( $_POST[ 'new_start_date' ] !== date( 'Y-m-d',strtotime($old_event_start_date) ) ) 
			||
		  isset( $_POST[ 'new_end_date' ] ) && ( $_POST[ 'new_end_date' ] !== date( 'Y-m-d',strtotime($old_event_end_date) ) )
	   ){
		
		$new_event_start_date = $_POST[ 'new_start_date' ].' '.$EventStartHour.
		 ':'.$EventStartMinute.':00';
		
		// Par sécurité on vérifie que le format de la date soit valide
		// si pour une raison quelconque le format des dates dans les colonnes
		// Date de début & Date de fin du tableau qui liste les évènements
		// n'est pas au format d/m/Y, alors les dates picker de l'édition rapide
		// ne sont pas initialisé, il retourne une chaine vide
		// La fonction date_parse_from_format()
		// retourne un tableau associatif pour lequel:
		// $check_date['error_count'] = 0 si le format de la date correspond
		$check_date_start = date_parse_from_format('Y-m-d H:i:s', $new_event_start_date);
		
		$new_event_end_date = $_POST[ 'new_end_date' ].' '.$EventEndHour.
		 ':'.$EventEndMinute.':00';
		 
		$check_date_end = date_parse_from_format('Y-m-d H:i:s', $new_event_end_date);

		if ( !$check_date_start['error_count'] && !$check_date_end['error_count'] 
				&&
			 $new_event_end_date >= $new_event_start_date
			
			){
				
			update_post_meta( $post_id, '_EventStartDate', $new_event_start_date );
			update_post_meta( $post_id, '_EventEndDate', $new_event_end_date );
			
		}else{
			return;
		}
		
	}
	
	
	/* ============================================================================= 
	    MISE A JOUR COMPLÉMENTAIRE DATE DE DÉBUT & DE FIN DE L'ÉVÈNEMENT
		            POUR L'EXTENSION THE EVENTS CALENDAR PRO
		La mise a jour des métas ne suffit pas pour the events calendar pro
		qui recherche les dates dans les tables wp_tec_events & wp_tec_occurrences 
		pour uniquement les afficher dans les colonnes dates de début 
		& dates de fin de l'écran qui liste les évènements.
		ailleurs ce sont les méta _EventStartDate & _EventEndDate qui sont utilisées 		
	 =============================================================================== */
	
	
	// Vérifier si la classe Tribe__Events__Pro__Main existe
	// alors le plugin The Event Calendar Pro est installé
	if ( class_exists( 'Tribe__Events__Pro__Main' ) ) {
	
		// Si l'une des nouvelles dates de l'évènement sélectionné est différentes de celles 
		// en cours pour l'évènement on met a jour les dates pour l'évènement 	
		// dans les tables wp_tec_events &  wp_tec_occurrences
		if (  isset( $_POST[ 'new_start_date' ] ) && ( $_POST[ 'new_start_date' ] !== date( 'Y-m-d',strtotime($old_event_start_date) ) )
			|| isset( $_POST[ 'new_end_date' ] ) && ( $_POST[ 'new_end_date' ] !== date( 'Y-m-d',strtotime($old_event_end_date) ) )	 ){
			
			//la classe wpdb est toujours instanciée au chargement de l’application 
			//et stockée dans une variable globale appelée $wpdb
			global $wpdb;
			
			// permet d'afficher les erreurs
			$wpdb->show_errors();
			
			/* Requête SQL classique
			$requete = "UPDATE {$wpdb->prefix}tec_events
					   SET start_date = $new_event_start_date, end_date = $new_event_end_date 
					   WHERE post_id = $post_id";
			
			$result = $wpdb->query($requete);
			*/
			
			
			// Mise a jour de la table wp_tec_events avec 
			// les dates de début & de fin  du nouvel évènement
			// On prépare la requête SQL pour éviter les injections SQL
			$requete = $wpdb->prepare(
				"UPDATE {$wpdb->prefix}tec_events
				SET start_date = %s, end_date = %s
				WHERE post_id = %d",
				$new_event_start_date, $new_event_end_date, $post_id
			);

			// Exécuter la requête SQL
			$result = $wpdb->query($requete);
		
			// Mise a jour de la table wp_tec_occurrences avec  
			// les dates de début & de fin du nouvel évènement
			$requete = $wpdb->prepare(
				"UPDATE {$wpdb->prefix}tec_occurrences
				SET start_date = %s, end_date = %s
				WHERE post_id = %d",
				$new_event_start_date, $new_event_end_date, $post_id
			);

			// Exécuter la requête SQL
			$result = $wpdb->query($requete);
			
			
		}
		
	}
	
	
	/* ======================================================================================= 
	                  MISE A JOUR DATE DÉBUT & FIN DES TICKETS
		• On récupère par des requêtes en base de données
		- l'ID du ticket RSVP
		- les ID des tickets payant
		• Si la date de début de l'évènement n'a pas été modifiée,ou s'il n'y a pas de ticket, 
		alors on ne fait pas la mise a jour des dates du ticket
		• Si une nouvelle date de début a été sélectionné pour l'évènement alors:
		1) procédure ticket RSVP:
		- on récupère les dates de début & de fin pour le ticket de l'évènement d'origine
		- calcule en jours la différence (diff-day1 = date début èvènement d'origine - date de fin ticket)
		- calcule en jours la différence ( diff-day2 = date de fin ticket - date de début ticket)
		- nouvelle date fin ticket = nouvelle date début évènement - diff-day1
		- nouvelle date début ticket = nouvelle date fin ticket - diff-day2
		- mise a jour en base de données des nouvelles dates de début & de fin pour le ticket RSVP
		2) procédure ticket Payant: Identique à RSVP
		- Remarque: les nouvelles dates de début & de fin seront les mêmes pour tous les tickets
	========================================================================================= */
	
	// la classe wpdb est toujours instanciée au chargement de l’application 
	// et stockée dans une variable globale appelée $wpdb
	global $wpdb;
	
	// on récupère l'ID du ticket RSVP via la méta _tribe_rsvp_for_event
	// qui lie l'évènement au ticket RSVP
	$requete = $wpdb->prepare( "SELECT post_id 
								FROM {$wpdb->prefix}postmeta
								WHERE meta_key = %s " , "_tribe_rsvp_for_event"  );
								
	$requete = $wpdb->prepare( $requete. " AND meta_value = %s" ,strval($post_id)  );
								
	$ticket_RSVP_id  = $wpdb->get_var($requete);
	
	
	// on récupère l'ID des tickets payant via la méta _tribe_tpp_for_event
	// qui lie l'évènement au ticket payant
	// Remarque: s'il n'y a q'un seul ticket on peut récupèrer l'ID du ticket
	// via la méta _tribe_tickets_list qui lie l'évènement au ticket
	// $ticket_id = get_post_meta( $post_id, '_tribe_tickets_list' , true  ) 
	$requete = $wpdb->prepare( "SELECT post_id 
								FROM {$wpdb->prefix}postmeta
								WHERE  meta_key = %s " , "_tribe_tpp_for_event"  );
								
	$requete = $wpdb->prepare( $requete. " AND meta_value = %s" , strval($post_id)  );
								
	$requete = $wpdb->get_results($requete);
	
	$ticket_quantity = 0;
	
	foreach ( $requete as $row ) {
			$ticket_quantity++;
			$ticket_id[$ticket_quantity] = $row->post_id;
	}
	
	// Lors de la duplication d'un évènement le hook save_post est activé
	// ce qui déclenche aussi la fonction ecet_quick_edit_save()
	// on ne peut alors récupèrer les métas pour un évènement
	// qui n'est pas encore dupliqué et donc l'ID du ticket.
	// Bizarrement l'edition rapide est aussi exécutée deux fois
	// ce qui génére une erreur sur la fonction ecet_diff_in_days()
	// déclarée deux fois.
	// permet aussi de filtrer: 
	// • Les évènements qui n'ont pas de ticket
	// • Si la date de début de l'évènement n'a pas été modifiée, alors on ne fait
	// pas la mise a jour des dates du ticket
	if ( ( empty($ticket_id) && !$ticket_RSVP_id ) || ( $_POST[ 'new_start_date' ] == date( 'Y/m/d',strtotime($old_event_start_date) ) ) ){
		return;
	}
	
	// calcule la diffèrence entre deux dates
	function ecet_diff_in_days($end_date, $start_date ){
		
		$start_date = strtotime($start_date);
		$end_date = strtotime($end_date);
 
		// dans un jour il y a 60'* 60s *24H
		// floor permet d'arrondir à l’entier inférieur le plus proche
		$diff_in_days = floor(($end_date - $start_date) / (60 * 60 * 24));

		return $diff_in_days ;
		
	}
	
	// Soustrait un nombre de jours:$day a une date:$date qui
	// attention! doit être au format date anglaise Y/m/d
	// retourne la date au format spécifié:$format
	function ecet_subtract_days($date, $days, $format){
		$date = strtotime("$date -$days day");
		return date($format, $date);
	}
	 
	
	// Si une nouvelle date de début a été sélectionné pour l'évènement
	// mise a jour en base de données des dates de début & de fin pour 
	// les tickets RSVP ET/OU Payant.
	// Ces dates doivent être au format date anglaise: Y-m-d  
	if ( isset($new_event_start_date) && !empty($new_event_start_date) ) {
 
		// Si c'est un billet RSVP
		// le format des dates de début & de fin est : Y-m-d H:i:s 
		if ( $ticket_RSVP_id ) {
			
			// on récupère les dates de début & de fin pour le ticket de l'évènement d'origine
			$start_date_ticket =  get_post_meta( $ticket_RSVP_id , '_ticket_start_date' , true  ) ;
			$end_date_ticket   =  get_post_meta( $ticket_RSVP_id , '_ticket_end_date' , true  ) ;

			// on récupère les heures de début & de fin pour le ticket de l'évènement d'origine
			$start_time_ticket_RSVP = date('H:i:s', strtotime($start_date_ticket) );
			$end_time_ticket_RSVP = date('H:i:s', strtotime($end_date_ticket) );
			
			// calcule en jours la différence (date début èvènement d'origine - date de fin ticket)
			$diff_in_days_start_event = ecet_diff_in_days($old_event_start_date,$end_date_ticket);
	
			// calcule en jours la différence (date de fin ticket - date de début ticket)
			$diff_in_days_end_start_ticket = ecet_diff_in_days($end_date_ticket,$start_date_ticket);
 
			// calcul des nouvelles dates de début & de fin 
			// remarque: $new_event_start_date & $new_end_date_ticket
			// doivent impérativement être au format: Y/m/d
			$new_end_date_ticket = ecet_subtract_days($new_event_start_date,$diff_in_days_start_event,'Y-m-d' );
			$new_start_date_ticket = ecet_subtract_days($new_end_date_ticket,$diff_in_days_end_start_ticket,'Y-m-d' );
		
			$new_end_date_ticket = $new_end_date_ticket.' '.$end_time_ticket_RSVP; 
			$new_start_date_ticket = $new_start_date_ticket.' '.$start_time_ticket_RSVP;
			
			// mise a jour des nouvelles dates de début & de fin pour le Ticket RSVP
			update_post_meta( $ticket_RSVP_id, '_ticket_start_date', $new_start_date_ticket );
			update_post_meta( $ticket_RSVP_id, '_ticket_end_date', $new_end_date_ticket );
		
			/* Pour Debug 
			echo'<br>';
			echo'<pre>';
			echo'ID évènement: '.$post_id.'<br>';
			echo'date début évènement d\'origine: '.$old_event_start_date.'<br>';
			echo'nouvelle date début évènement: '.$new_event_start_date.'<br>';
			echo'ID du Ticket RSVP: '.$ticket_RSVP_id.'<br>';
			echo'date fin ticket: '.$end_date_ticket.'<br>';
			echo'date début èvènement d\'origine - date fin ticket = '.$diff_in_days_start_event.'<br>';
			echo'date début ticket: '.$start_date_ticket.'<br>';
			echo'date fin ticket - date début ticket = '.$diff_in_days_end_start_ticket.'<br>';
			echo'nouvelle date début ticket = '.$new_start_date_ticket.'<br>';
			echo'nouvelle date fin ticket = '.$new_end_date_ticket.'<br>';
			echo'</pre>';
			*/
			
		}
				
		// S'il y a des billets payant	
		if ( !empty($ticket_id ) ) {
			
			// qu'il y ait un ou plusieurs tickets, on considére que la période de réservation
			// démarre et termine a la même date pour tous les tickets
			$start_date_ticket =  get_post_meta( $ticket_id[1] , '_ticket_start_date' , true  ) ;
			$end_date_ticket   =  get_post_meta( $ticket_id[1] , '_ticket_end_date' , true  ) ;
		
			// calcule en jours la différence (date début èvènement d'origine - date de fin ticket)
			$diff_in_days_start_event = ecet_diff_in_days($old_event_start_date,$end_date_ticket);
	
			// calcule en jours la différence (date de fin ticket - date de début ticket)
			$diff_in_days_end_start_ticket = ecet_diff_in_days($end_date_ticket,$start_date_ticket);
			
			// calcul des nouvelles dates de début & de fin 
			// pour un ticket payant le format de la date est : Y-m-d 
			$new_end_date_ticket = ecet_subtract_days($new_event_start_date,$diff_in_days_start_event,'Y-m-d' );
			$new_start_date_ticket = ecet_subtract_days($new_end_date_ticket,$diff_in_days_end_start_ticket,'Y-m-d' );
		
			// mise a jour des nouvelles dates de début & de fin pour tous les tickets
			for( $index = 1;$index <= $ticket_quantity;$index++ ) {
				
				update_post_meta( $ticket_id[$index], '_ticket_start_date', $new_start_date_ticket );
				update_post_meta( $ticket_id[$index], '_ticket_end_date', $new_end_date_ticket );
				
			}
			
			/* Pour Debug 
			echo'<br>';
			echo'<pre>';
			echo'ID évènement: '.$post_id.'<br>';
			echo'date début évènement d\'origine: '.$old_event_start_date.'<br>';
			echo'nouvelle date début évènement: '.$new_event_start_date.'<br>';
			echo'ID du Ticket Payant: '.'<br>';
			print_r($ticket_id).'<br>';
			echo'date fin ticket: '.$end_date_ticket.'<br>';
			echo'date début èvènement d\'origine - date fin ticket = '.$diff_in_days_start_event.'<br>';
			echo'date début ticket: '.$start_date_ticket.'<br>';
			echo'date fin ticket - date début ticket = '.$diff_in_days_end_start_ticket.'<br>';
			echo'nouvelle date début ticket = '.$new_start_date_ticket.'<br>';
			echo'nouvelle date fin ticket = '.$new_end_date_ticket.'<br>';
			echo'</pre>';
			*/
	
		}
		
	}
	

	
    // Ajouter un script JavaScript pour actualiser la page
	// pour palier au problème:
	// aprés le hook  save_post je suis obligé de rafraichir la page qui liste 
	// les évènements pour que les dates de début & de fin s'affichent 
	// dans la bonne colonne.
	// Le contenu est légèrement déformé avant l'actualisation de la page
    /*echo '<script type="text/javascript">
	window.location.reload(true);
	</script>';*/
	
}