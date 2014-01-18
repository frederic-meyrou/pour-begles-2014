<?php get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  <article>


    <post><div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php if ( is_front_page() ) { ?>
  		  <h4><?php the_title(); ?></h4>
  	  <?php } else { ?>
  		  <h4><?php the_title(); ?></h4>
  	  <?php } ?>

      <p>
        <?php the_content(); ?>
        <?php edit_post_link( __( 'Edit', 'icecap' ), '<p class="edit-post">', '</p>' ); ?>
    	</p>
    </div></post>

  </article>
	<?php comments_template( '', true ); ?>

<?php endwhile; ?>
