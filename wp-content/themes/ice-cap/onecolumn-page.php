<?php
/**
 * Template Name: One column, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'icecap' ), 'after' => '' ) ); ?>
				<?php edit_post_link( __( 'Edit', 'icecap' ), '<p class="edit-post">', '</p>' ); ?>

<?php endwhile; ?>

