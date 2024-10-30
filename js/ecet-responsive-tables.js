/*--------------------------------------------------------------------------
				   RENDRE LA TABLE DES PARTICIPANTS REPONSIVE
---------------------------------------------------------------------------*/

/*

Script établi a partir de  ce lien:
https://zurb.com/playground/responsive-tables

Avec démo:
https://zurb.com/playground/projects/responsive-tables/

Ce script établi une colonne de gauche fixe pour une résolution écran < 767px  
et le reste des colonnes peuvent défiler.

Le JS va créer un balisage supplémentaire sur les mobiles,
sans affecter le balisage de la table des participants. 
ce balisage supplémentaire constituée de <div> est affectée 
des classes: .table-wrapper , .scrollable , .pinned
Des régles CSS applique les styles de positionnement et de 
débordement requis pour faire fonctionner ce balisage supplémentaire.

Pour que notre JS et CSS fonctionne on ajoute en js la classe 
.ecet-responsive à la balise <table>

	<div class="table-wrapper">
	
		<div class="scrollable">
		
		    ---- balisage de la table des participants ----
			<table class="table-participant ecet-responsive">
				<tbody>
					<tr>
						<td style="">Alain Bladou (1)</td>
						<td> Christophe Gosselin  (1)</td>
						<td> Dominique Lemoucheux  (3)</td>
						<td> Fabrice Neauleau  (2)</td>
					</tr>
					<tr>
						<td style=""> Franck Josselin  (1)</td>
						<td> Jerome Macouin  (1)</td>
						<td>Nathalie Lejeune (1)</td>
						<td> Thierry Barré  (1)</td>
					</tr>
				</tbody>
			</table>
			----  fin balisage de la table des participants ----
			
		</div>
		
		<div class="pinned">
			copie de la balise <table> mais seule la 1ère colonne est affichée
			les colonnes suivantes sont masquées en JS par la règle css display:none
		</div>
		
	</div>

*/

jQuery(document).ready(function($) {
	
	var switched = false;
  
	var updateTables = function() {
    
		// pour les mobiles
		// on ajoute notre balisage supplémentaire
		if (($(window).width() < 767) && !switched ){
			
		  // Ajout de la classe ecet-responsive a la balise <table> 
		  // pour la table des participants
		  $( '.table-participant' ).addClass( 'ecet-responsive' );
		  
		  switched = true;
		  
		  // ajout du balisage supplémentaire
		  $(".table-participant.ecet-responsive").each(function(i, element) {
			splitTable($(element));
		  });
		  
		  return true;
		}
		// sinon on détruit le balisage supplémentaire
		// pour revenir au balisage original de la Table
		else if (switched && ($(window).width() > 767)) {
			
		  switched = false;
		  
		  // on détruit le balisage supplémentaire
		  $("table.ecet-responsive").each(function(i, element) {
			unsplitTable($(element));
		  });
		  
		}
	
	};
   
	$(window).load(updateTables);
	$(window).on("redraw",function(){switched=false;updateTables();}); // Un événement à écouter
	$(window).on("resize", updateTables);
   
	// Fonction qui construit le balisage supplémentaire
	function splitTable(original) {
		
		original.wrap("<div class='table-wrapper' />");
		
		// copie de la balise <table> et son contenu, mais on
		// affiche que la 1ère colonne, et on masque les colonnes 
		// suivantes par la régle css display:none;
		// On supprime la classe ecet-responsive de cette copie de la table.
		// Pour la table d'origine la 1ère colonne sera masquée par régle
		// css dans le fichier editor-custom-color-palette.php
		var copy = original.clone();
		copy.find("td:not(:first-child), th:not(:first-child)").css("display", "none");
		copy.removeClass("ecet-responsive");
		
		original.closest(".table-wrapper").append(copy);
		copy.wrap("<div class='pinned' />");
		original.wrap("<div class='scrollable' />");

		setCellHeights(original, copy);
	}
	
	// Fonction qui détruit le balisage supplémentaire
	// pou revenir au balisage original du bloc table
	function unsplitTable(original) {
		original.closest(".table-wrapper").find(".pinned").remove();
		original.unwrap();
		original.unwrap();
	}

	function setCellHeights(original, copy) {
		
		var tr = original.find('tr'),
			tr_copy = copy.find('tr'),
			heights = [];

		tr.each(function (index) {
			
			var self = $(this),
			tx = self.find('th, td');

			tx.each(function () {
				var height = $(this).outerHeight(true);
				heights[index] = heights[index] || 0;
				if (height > heights[index]) heights[index] = height;
			});

		});

		tr_copy.each(function (index) {
			$(this).height(heights[index]);
		});
		
	}

});
