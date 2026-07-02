<footer class="w-full border-t border-neutral-200 mt-16">
	<div class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">

		<div>
			<h3 class="text-sm font-semibold uppercase tracking-wide mb-4">
				<?php esc_html_e( 'About', 'sassy-strides' ); ?>
			</h3>
			<ul class="space-y-2 text-sm text-neutral-600">
				<li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About Us', 'sassy-strides' ); ?></a></li>
			</ul>
		</div>

		<div>
			<h3 class="text-sm font-semibold uppercase tracking-wide mb-4">
				<?php esc_html_e( 'Advertise', 'sassy-strides' ); ?>
			</h3>
			<ul class="space-y-2 text-sm text-neutral-600">
				<li><a href="<?php echo esc_url( home_url( '/advertise' ) ); ?>"><?php esc_html_e( 'Advertise With Us', 'sassy-strides' ); ?></a></li>
			</ul>
		</div>

		<div>
			<h3 class="text-sm font-semibold uppercase tracking-wide mb-4">
				<?php esc_html_e( 'Contact', 'sassy-strides' ); ?>
			</h3>
			<ul class="space-y-2 text-sm text-neutral-600">
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact Us', 'sassy-strides' ); ?></a></li>
			</ul>
		</div>

		<div>
			<h3 class="text-sm font-semibold uppercase tracking-wide mb-4">
				<?php esc_html_e( 'Privacy', 'sassy-strides' ); ?>
			</h3>
			<ul class="space-y-2 text-sm text-neutral-600">
				<li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'sassy-strides' ); ?></a></li>
			</ul>
		</div>

	</div>

	<div class="border-t border-neutral-200 py-6 text-center text-xs text-neutral-500">
		&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'sassy-strides' ); ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
