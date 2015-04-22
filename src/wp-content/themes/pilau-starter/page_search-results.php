<?php

/**
 * Template name: Search results
 *
 * Ready for Google CSE results, just need to:
 * - Add JS to header
 * - Set search form to submit to page set to this template, with search query as $_GET['q']
 *
 * @package	Pilau_Starter
 * @since	0.1
 */

?>

<?php get_header(); ?>

<main role="main" id="content">
	<div class="wrap">

		<div class="content-body">

			<h1 class="main-heading"><?php echo __( 'Search results for:' ) . ' &lsquo;' . esc_html( urldecode( $_GET['q'] ) ) . '&rsquo;'; ?></h1>

			<gcse:searchresults-only linktarget="_parent"></gcse:searchresults-only>

		</div>

	</div>
</main>

<?php get_footer(); ?>