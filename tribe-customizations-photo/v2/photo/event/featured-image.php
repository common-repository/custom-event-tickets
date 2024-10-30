<?php
/**
 * View: Photo View - Single Event Featured Image
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events-pro/v2/photo/event/featured-image.php
 *
 * See more documentation about our views templating system.
 *
 * @link https://evnt.is/1aiy
 *
 * @version 5.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 * @var string $placeholder_url The url for the placeholder image if a featured image does not exist.
 *
 * @see tribe_get_event() For the format of the event object.
 */

// Personnalisation ECET
use Tribe__Date_Utils as Dates;
 
$image_url = $event->thumbnail->exists ? $event->thumbnail->full->url : $placeholder_url;

// Personnalisation ECET
//origine du code apartir du template de la date:
//wp-content\plugins\events-calendar-pro\src\views\v2\photo\event\date-tag.php
$display_date = empty( $is_past ) && ! empty( $request_date )
    ? max( $event->dates->start_display, $request_date )
    : $event->dates->start_display;
 
$event_month     = $display_date->format_i18n( 'M' );
$event_day_num   = $display_date->format_i18n( 'j' );
$event_date_attr = $display_date->format( Dates::DBDATEFORMAT );


?>
<div class="tribe-events-pro-photo__event-featured-image-wrapper">
	<a
		href="<?php echo esc_url( $event->permalink ); ?>"
		title="<?php echo esc_attr( get_the_title( $event ) ); ?>"
		rel="bookmark"
		class="tribe-events-pro-photo__event-featured-image-link"
	>
		<img
			src="<?php echo esc_url( $image_url ); ?>"
			<?php if ( ! empty( $event->thumbnail->srcset ) ) : ?>
				srcset="<?php echo esc_attr( $event->thumbnail->srcset ); ?>"
			<?php endif; ?>
			<?php if ( ! empty( $event->thumbnail->alt ) ) : ?>
				alt="<?php echo esc_attr( $event->thumbnail->alt ); ?>"
			<?php else : // We need to ensure we have an empty alt tag for accessibility reasons if the user doesn't set one for the featured image ?>
				alt=""
			<?php endif; ?>
			<?php if ( ! empty( $event->thumbnail->title ) ) : ?>
				title="<?php echo esc_attr( $event->thumbnail->title ); ?>"
			<?php endif; ?>
			class="tribe-events-pro-photo__event-featured-image"
		/>
		<!--- Personnalisation ECET: Ajout de la date de l'évènement dans la photo ---->
		<!--- origine du code template de la date: ---->
		<!---  wp-content\plugins\events-calendar-pro\src\views\v2\photo\event\date-tag.php ---->
		<div class="tribe-events-pro-photo__event-featured-image-date-tag">
            <time class="tribe-events-pro-photo__event-featured-image-date-tag-datetime" datetime="<?php echo esc_attr( $event_date_attr ); ?>">
                <span class="tribe-events-pro-photo__event-featured-image-date-tag-month">
                    <?php echo esc_html( $event_month ); ?>
                </span>
                <span class="tribe-events-pro-photo__event-featured-image-date-tag-daynum tribe-common-h5 tribe-common-h4--min-medium">
                    <?php echo esc_html( $event_day_num ); ?>
                </span>
            </time>
        </div>
	</a>
</div>
