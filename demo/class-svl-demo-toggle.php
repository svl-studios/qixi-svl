<?php

if ( ! class_exists( 'Svl_Demo_Toggle' ) ) {
	class Svl_Demo_Toggle {
		public function __construct() {
			// add_action( 'wp_footer', array( $this, 'add_demo_toggle' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
			// add_shortcode( 'svl_demos', array( $this, 'shortcode' ) );
		}

		public function shortcode( $atts = '', $content = null ) {
			$html = '';

			if ( false === get_transient( 'qixi_remote_demo_data' ) ) {
				$api = wp_remote_get( 'https://www.svlstudios.com/wp-content/qixi-demos.json' );

				if ( ! is_wp_error( $api ) && is_array( $api ) && ! empty( $api['body'] ) ) {
					$demos = (array) json_decode( $api['body'], true );

					set_transient( 'qixi_remote_demo_data', $demos, 7 * DAY_IN_SECONDS );
				}
			} else {
				$demos = (array) get_transient( 'qixi_remote_demo_data' );
			}

			if ( ! empty( $demos ) ) {
				$html .= '<div class="svl-demos" style="visibility: visible;">';

				foreach ( $demos as $param => $value ) {
					$coming_soon = '';

					if ( $value['coming_soon'] ?? false ) {
						$coming_soon = 'svl-coming-soon';
					}

					$html .= '    <div class="col-xs-6 col-sm-4 col-md-3 demo-block ' . esc_attr( $param ) . ' ' . esc_attr( $coming_soon ) . '">';
					$html .= '		<div class="demo-wrap">';

					if ( '' === $coming_soon ) {
						$html .= '			<a target="_blank" href="' . esc_url( $value['url'] ) . '" class="overlay"></a>';
						$html .= '			<a target="_blank" href="' . esc_url( $value['url'] ) . '" class="svl-button button btn-primary no-hover">View Demo</a>';
					}

					$html .= '			<img src="' . esc_url( $value['img'] ) . '">';
					$html .= '			<a target="_blank" href="' . esc_url( $value['url'] ) . '" class="title-link">';
					$html .= '				<h4>' . esc_html( $value['title'] ) . '</h4>';
					$html .= '			</a>';

					if ( $value['new'] ?? false ) {
						$html .= '<span class="new-demo">NEW!</span>';
					}

					$html .= '			<span class="svl-builder ' . esc_attr( $value['builder'] ) . '">';
					$html .= '              <img src="' . esc_url( $value['builder_img'] ) . '">';
					$html .= '	        </span>';
					$html .= '		</div>';
					$html .= '	</div>';
				}

				$html .= '</div>';
			}

			echo $html; // phpcs:ignore
		}

		public function add_demo_toggle() {
			?>
			<div class="svl-demo-select-wrap init-onload">
				<span href="#" class="svl-demo-toggle">
					<i class="fa fa-plus"></i> DEMOS
				</span>
				<div class="svl-demos-info-box">
					<div class="buy-now-btn">
						<a href=""> Purchase Qixi </a>
					</div>
					<span class="demos-count"></span>
					<span class="svl-more-demos-text"> Loading Demos </span>
				</div>
				<div class="svl-demo-window">
					<i class="loading-demos fa fa-spin fa-refresh"></i>
					<ul style="height: 376px;"></ul>
				</div>
			</div>
			<?php
		}

		public function enqueue() {
			wp_enqueue_style(
				'svl-demo-toggle',
				get_stylesheet_directory_uri() . '/demo/demo-toggle.css',
				array(),
				'1.0.0'
			);

			wp_enqueue_script(
				'svl-demo-toggle',
				get_stylesheet_directory_uri() . '/demo/demo-toggle.js',
				array(),
				'1.0.0',
				true
			);

			wp_localize_script(
				'svl-demo-toggle',
				'svlOptions',
				array(
					'baseURL' => home_url(),
				)
			);
		}
	}

	new Svl_Demo_Toggle();
}
