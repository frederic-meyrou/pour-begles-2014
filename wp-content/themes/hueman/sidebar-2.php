<<<<<<< HEAD
<?php $sidebar = alx_sidebar_secondary(); ?>

<div class="sidebar s2">
	
	<a class="sidebar-toggle" title="<?php _e('Expand Sidebar','hueman'); ?>"><i class="fa icon-sidebar-toggle"></i></a>
	
	<div class="sidebar-content">
		
		<div class="sidebar-top group">
			<p><?php _e('RESEAUX','hueman'); ?></p>
				<?php alx_social_links() ; ?>	
		</div>
		
		<?php if ( ot_get_option( 'post-nav' ) == 's2') { get_template_part('inc/post-nav'); } ?>
		
		<?php dynamic_sidebar($sidebar); ?>
		
	</div><!--/.sidebar-content-->
	
=======
<?php $sidebar = alx_sidebar_secondary(); ?>

<div class="sidebar s2">
	
	<a class="sidebar-toggle" title="<?php _e('Expand Sidebar','hueman'); ?>"><i class="fa icon-sidebar-toggle"></i></a>
	
	<div class="sidebar-content">
		
		<div class="sidebar-top group">
			<p><?php _e('RESEAUX','hueman'); ?></p>
				<?php alx_social_links() ; ?>	
		</div>
		
		<?php if ( ot_get_option( 'post-nav' ) == 's2') { get_template_part('inc/post-nav'); } ?>
		
		<?php dynamic_sidebar($sidebar); ?>
		
	</div><!--/.sidebar-content-->
	
>>>>>>> a643d81d404847ee87bd0b142ff888fd6886239a
</div><!--/.sidebar-->