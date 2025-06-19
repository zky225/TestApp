<?php
/**
 * Template to be used for the print page.
 *
 * @link       http://bootstrapped.ventures
 * @since      4.0.3
 *
 * @package    WP_Recipe_Maker
 * @subpackage WP_Recipe_Maker/templates/public
 */

function wprm_maybe_redirect_to_parent_post( $output ) {
	if ( WPRM_Settings::get( 'print_page_redirect' ) && isset( $output['url'] ) ) {
		// Use timeout to make sure args (set by clicking on print button in a parent post) are set.
		echo '<script>setTimeout( () => { window.WPRMPrint.maybeRedirect("' . esc_url( $output['url'] ) . '"); }, 100 );</script>';
	}
}

?>
<!DOCTYPE html>
<html <?php echo wp_kses_post( get_language_attributes() ); ?>>
	<head>
		<title><?php echo esc_html( isset( $output['title'] ) && $output['title'] ? $output['title'] : get_bloginfo( 'name' ) ); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<meta name="robots" content="noindex">
		<?php if ( WPRM_Settings::get( 'metadata_pinterest_disable_print_page' ) ) : ?>
			<meta name="pinterest" content="nopin" />
		<?php endif; ?>
		<?php wp_site_icon(); ?>
		<?php
		if ( isset( $output['assets'] ) ) {
			$serialized = array_map( 'serialize', $output['assets'] );
			$unique = array_unique( $serialized );
			$assets = array_intersect_key( $output['assets'], $unique );
			
			foreach ( $output['assets'] as $asset ) {
				switch ( $asset['type'] ) {
					case 'css':
						echo '<link rel="stylesheet" type="text/css" href="' . esc_attr( $asset['url'] . '?ver=' . WPRM_VERSION ) . '"/>';
						break;
					case 'js':
						echo '<script src="' . esc_attr( $asset['url'] . '?ver=' . WPRM_VERSION ) . '"></script>';
						break;
					case 'custom':
						echo $asset['html'];
						break;
				}
			}
		}
		?>
	</head>
	<body class="wprm-print<?php echo esc_attr( is_rtl() ? ' rtl' : '' ); ?> wprm-print-<?php echo esc_attr( $output['type'] ); ?>">
		<div id="wprm-print-header">
			<div id="wprm-print-header-main">
				<?php
				$back_link = isset( $output['url'] ) ? $output['url'] : home_url();

				$http_referer = isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : false;

				if ( $http_referer ) {
					$http_host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
					$host_parts = explode( ':', $http_host );
					$host_without_port = $host_parts[0];

					// Check if same domain.
					if ( $host_without_port === wp_parse_url( $http_referer, PHP_URL_HOST ) ) {
						$back_link = $http_referer;
					} else {
						wprm_maybe_redirect_to_parent_post( $output );
					}
				} else {
					// No referer. Redirect to parent post?
					wprm_maybe_redirect_to_parent_post( $output );
				}
				?>
				<a href="<?php echo esc_url( $back_link ); ?>" id="wprm-print-button-back" class="wprm-print-button"><?php esc_html_e( 'Go Back', 'wp-recipe-maker' );?></a>
				<?php
				if ( ! isset( $output['no-email'] ) && WPRM_Settings::get( 'print_email_link_button' ) ) {
					echo '<a href="#" id="wprm-print-button-email" class="wprm-print-button">' . esc_html( __( 'Email Link', 'wp-recipe-maker' ) ) . '</a>';
				}
				// if ( WPRM_Settings::get( 'print_download_pdf_button' ) ) {
				// 	echo '<a href="#" id="wprm-print-button-pdf" class="wprm-print-button">' . __( 'Download PDF', 'wp-recipe-maker' ) . '</a>';
				// }
				?>
				<button id="wprm-print-button-print" class="wprm-print-button" type="button"><?php esc_html_e( 'Print', 'wp-recipe-maker' );?></button>
			</div>
			<?php if ( isset( $output['header'] ) ) : ?>
			<div id="wprm-print-header-options"><?php echo $output['header']; ?></div>
			<?php endif; ?>
		</div>
		<?php
		$classes = isset( $output['classes'] ) ? $output['classes'] : array();

		echo '<div id="wprm-print-content" class="' . esc_attr( implode( ' ', $classes ) ) . '">';

		$html = do_shortcode( $output['html'] );
		echo apply_filters( 'wprm_print_output_html', $html );

		echo '</div>';

		// Recipes we need the data for.
		WPRM_Recipe_Manager::recipe_data_in_footer( $output['recipe_ids'] );
		?>
		<?php
		$print_ad = trim( WPRM_Settings::get( 'print_footer_ad' ) );

		if ( $print_ad ) {
			echo '<div id="wprm-print-footer-ad">' . $print_ad . '</div>';
		}
		?>
		<div id="print-pdf"></div>
	</body>
</html>