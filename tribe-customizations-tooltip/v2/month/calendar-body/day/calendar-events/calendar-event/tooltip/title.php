<?php
/**
 * View: Month View - Single Event Tooltip Title
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/month/calendar-body/day/calendar-events/calendar-event/tooltip/title.php
 *
 * See more documentation about our views templating system.
 *
 * @link {INSERT_ARTICLE_LINK_HERE}
 *
 * @version 5.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

?>
<h3 class="tribe-events-calendar-month__calendar-event-tooltip-title tribe-common-h7">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		title="<?php echo esc_attr( $event->title ); ?>"
		rel="bookmark"
		class="tribe-events-calendar-month__calendar-event-tooltip-title-link tribe-common-anchor-thin"
	>
		<?php
		// phpcs:ignore
		echo $event->title;
		?>
	</a>
</h3>


<?php

/********************************************************
 Personnalisation par le plugin Easy Custom Event Tickets
/********************************************************/

// ID de l'évènement
$event_id = $event->ID;

if ( !tribe_address_exists($event_id) ) {
	return;
}

// Nom du lieu de l'évènement ex: La Ferme Du Four 
$venue = tribe_get_venue($event_id);

// Ville de l'évènement
$city = tribe_get_city($event_id);

// on Affiche le Nom du lieu + la ville de l'èvènement 
// ex: La Ferme Du Four Disgoville
echo '<div class="tribe-common-b3 ecet-tooltip-venue-details">' . '<strong>' . $venue . '</strong>' . ' ' . $city . '</div>';


?>

