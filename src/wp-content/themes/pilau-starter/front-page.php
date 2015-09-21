<?php

/**
 * Front page
 *
 * @package	Pilau_Starter
 * @since	0.1
 */
global $pilau_use_breadcrumbs;
$pilau_use_breadcrumbs = false;

?>

<?php get_header(); ?>

<main role="main" id="content">


	<section class="home-hero">

		<?php pilau_slideshow(); ?>

	</section>


	<section class="home-tabs">
		<div class="wrap">

			<h2>Examples of tabs</h2>

			<div class="tabs" id="tabs-a">

				<h3 class="tabs-heading hide-if-js">Single tab example</h3>

				<ul class="tablist-1 hide-if-no-js" role="tablist">
					<li id="tab-a-1" aria-controls="panel-1-1" aria-selected="true" role="tab" tabindex="0">Tab 1</li>
				</ul>

				<div class="panels">
					<div id="panel-a-1" role="tabpanel" aria-labelledby="tab-a-1" aria-hidden="false">
						<h4 class="panel-heading hide-if-js">Tab 1 title</h4>
						<p>Sometimes you end up using the same style with just a single tab...</p>
					</div>
				</div>

			</div>

			<div class="tabs" id="tabs-b">

				<h3 class="tabs-heading hide-if-js">Double tab example</h3>

				<ul class="tablist-2 hide-if-no-js" role="tablist">
					<li id="tab-b-1" aria-controls="panel-b-1" aria-selected="true" role="tab" tabindex="0">Tab 1</li>
					<li id="tab-b-2" aria-controls="panel-b-2" aria-selected="false" role="tab" tabindex="0">Tab 2</li>
				</ul>

				<div class="panels">
					<div id="panel-b-1" role="tabpanel" aria-labelledby="tab-b-1" aria-hidden="false">
						<h4 class="panel-heading hide-if-js">Tab 1 title</h4>
						<p>First panel contents...</p>
					</div>
					<div id="panel-b-2" role="tabpanel" aria-labelledby="tab-b-2" aria-hidden="true">
						<h4 class="panel-heading hide-if-js">Tab 2 title</h4>
						<p>Second panel contents...</p>
					</div>
				</div>

			</div>

			<div class="tabs tabs-expand-for-mobile" id="tabs-c">

				<h3 class="tabs-heading hide-if-js">Triple tab example</h3>

				<ul class="tablist-3 hide-if-no-js" role="tablist">
					<li id="tab-c-1" aria-controls="panel-c-1" aria-selected="true" role="tab" tabindex="0">Tab 1</li>
					<li id="tab-c-2" aria-controls="panel-c-2" aria-selected="false" role="tab" tabindex="0">Tab 2</li>
					<li id="tab-c-3" aria-controls="panel-c-3" aria-selected="false" role="tab" tabindex="0">Tab 3</li>
				</ul>

				<div class="panels">
					<div id="panel-c-1" role="tabpanel" aria-labelledby="tab-c-1" aria-hidden="false">
						<h4 class="panel-heading hide-if-js">Tab 1 title</h4>
						<p>First panel contents...</p>
					</div>
					<div id="panel-c-2" role="tabpanel" aria-labelledby="tab-c-2" aria-hidden="true">
						<h4 class="panel-heading hide-if-js">Tab 2 title</h4>
						<p>Second panel contents...</p>
					</div>
					<div id="panel-c-3" role="tabpanel" aria-labelledby="tab-c-3" aria-hidden="true">
						<h4 class="panel-heading hide-if-js">Tab 3 title</h4>
						<p>Third panel contents...</p>
					</div>
				</div>

			</div>

			<div class="tabs tabs-expand-for-mobile" id="tabs-d">

				<h3 class="tabs-heading hide-if-js">Quadruple tab example</h3>

				<ul class="tablist-4 hide-if-no-js" role="tablist">
					<li id="tab-d-1" aria-controls="panel-d-1" aria-selected="true" role="tab" tabindex="0">Tab 1</li>
					<li id="tab-d-2" aria-controls="panel-d-2" aria-selected="false" role="tab" tabindex="0">Tab 2</li>
					<li id="tab-d-3" aria-controls="panel-d-3" aria-selected="false" role="tab" tabindex="0">Tab 3</li>
					<li id="tab-d-4" aria-controls="panel-d-4" aria-selected="false" role="tab" tabindex="0">Tab 4</li>
				</ul>

				<div class="panels">
					<div id="panel-d-1" role="tabpanel" aria-labelledby="tab-d-1" aria-hidden="false">
						<h4 class="panel-heading hide-if-js">Tab 1 title</h4>
						<p>First panel contents...</p>
					</div>
					<div id="panel-d-2" role="tabpanel" aria-labelledby="tab-d-2" aria-hidden="true">
						<h4 class="panel-heading hide-if-js">Tab 2 title</h4>
						<p>Second panel contents...</p>
					</div>
					<div id="panel-d-3" role="tabpanel" aria-labelledby="tab-d-3" aria-hidden="true">
						<h4 class="panel-heading hide-if-js">Tab 3 title</h4>
						<p>Third panel contents...</p>
					</div>
					<div id="panel-d-4" role="tabpanel" aria-labelledby="tab-d-4" aria-hidden="true">
						<h4 class="panel-heading hide-if-js">Tab 4 title</h4>
						<p>Fourth panel contents...</p>
					</div>
				</div>

			</div>

		</div>
	</section>


	<section class="home-grid">
		<div class="wrap">

			<h2>The grid</h2>

			<?php $pilau_grid_columns = 12; ?>
			<div class="grid-container">

				<?php foreach ( array( 1, 2, 3, 4, 6 ) as $denominator ) { ?>
					<div class="grid-row">
						<?php for ( $col = 1; $col <= $pilau_grid_columns / $denominator; $col++ ) { ?>
							<div class="grid-col-<?php echo $denominator; ?>">
								<p>Spans <?php echo $denominator; ?> cols</p>
							</div>
						<?php } ?>
					</div>
				<?php } ?>

				<?php for ( $i = 1; $i < $pilau_grid_columns; $i++ ) { ?>
					<div class="grid-row">
						<div class="grid-col-<?php echo $i; ?>">
							<p>Spans <?php echo $i; ?> cols</p>
						</div>
						<div class="grid-col-<?php echo $pilau_grid_columns - $i; ?>">
							<p>Spans <?php echo $pilau_grid_columns - $i; ?> cols</p>
						</div>
					</div>
				<?php } ?>

			</div>

		</div>
	</section>


</main>

<?php get_footer(); ?>