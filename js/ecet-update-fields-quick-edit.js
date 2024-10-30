/*----------------------------------------------------------------------------------------
  METTRE À JOUR LES CHAMPS D'ÉDITION RAPIDE SELON LES VALEURS DE MÉTAS DE PUBLICATIONS
-----------------------------------------------------------------------------------------*/
/*
	script pour mettre a jour les champs date de l'edition rapide
	en fonction de la valeur des métas de publication:
	_EventStartDate & _EventEndDate 
	affichées dans les lignes de publications des écrans qui listent les évènements
*/


jQuery(document).ready(function($){

	/* si on  est sur l'écran d'édition rapide alors inlineEditPost est définie */ 
	if (typeof inlineEditPost !== 'undefined') { // Vérifie si la variable inlineEditPost est définie
	
		const wp_inline_edit_function = inlineEditPost.edit;

		/* On utilise le hook  inlineEditPost.edit
		   pour mettre a jour les champs date de l'edition rapide
		*/
		inlineEditPost.edit = function( post_id ) {

			/* On fusionne les arguments de la fonction d'origine */
			wp_inline_edit_function.apply( this, arguments );

			/* obtenir l'ID de publication */ 
			if ( typeof( post_id ) == 'object' ) { // s'il s'agit d'un objet, obtenez l'ID
				post_id = parseInt( this.getId( post_id ) );
			}

			/* définir la ligne pour la variable edit_row qui met a jour le champ date de l'edition rapide
			   définir la ligne de publication pour la variable post_row qui permet de récupérer la valeur
			   de la méta de publication _EventStartDate & _EventEndDate  
			   chaque ligne de publication ayant un id établi par WordPress en fonction de l'id de publication
			   selon le format suivant: #edit-post_id soit par exemple id="post-7973"
			*/
			const edit_row = $( '#edit-' + post_id )
			const post_row = $( '#post-' + post_id )

			/* on récupère les données dans les colonnes
			   ayant pour id : start-date & end-date 
			   a partir duquel WordPress ajoute les classes suivantes aux en-têtes de colonne:
			   column-start-date & column-end-date
			*/
			let startDate =  $( '.column-start-date', post_row ).text();
			
			/* j'inverse la date pour la mettre au format anglais 'yyyy-MM-dd'
			   attendu par le date picker*/
			startDate = startDate.split('/').reverse().join('-');
			
			let endDate =  $( '.column-end-date', post_row ).text();
			endDate = endDate.split('/').reverse().join('-');

			/* mise a jour des champs date de l'edition rapide avec les données des colonnes
			   en fonction de l'attribut name du champ
			*/
			$( ':input[name="new_start_date"]', edit_row ).val(startDate);
			$( ':input[name="new_end_date"]', edit_row ).val(endDate);
			
		}
		
	}/* fin si on  est sur l'écran d'édition rapide */
	
});