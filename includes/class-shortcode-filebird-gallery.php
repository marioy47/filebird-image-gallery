<?php
/**
 * Creates the filebird-gallery shortcode.
 *
 * @package Filebird_Image_Gallery
 */

namespace Filebird_Gallery;

/**
 * Creates the shortcode.
 */
class Shortcode_Filebird_Gallery {

	/**
	 * Singleton.
	 * You have to use the factory method.
	 */
	private function __construct() {

	}

	/**
	 * Factory.
	 * Creates instances without the need of the 'new' keyword.
	 *
	 * @return self
	 */
	public static function factory(): self {
		static $obj;
		return isset( $obj ) ? $obj : $obj = new self();
	}

	/**
	 * Add your WordPress hooks here.
	 *
	 * @return self
	 */
	public function wp_hooks(): self {
		add_shortcode( 'filebird-gallery', array( $this, 'shortcode' ) );
		return $this;
	}

	/**
	 * Creates the shortcode.
	 *
	 * @param array $atts The shortcode attributes.
	 * @return string The html
	 */
	public function shortcode( $atts ): string {

		$atts = shortcode_atts(
			array(
				'count'     => 15,
				'size'      => 'medium_large',
				'slug'      => '',
				'wrap'      => 'ul',
				'item-wrap' => 'li',
				'class'     => '',
				'link'      => 'yes',
				'sort'      => 'rand',
				'order'     => 'ASC',
				'tax'       => 'nt_wmc_folder',
			),
			$atts
		);

		if ( empty( $atts['slug'] ) ) {
			$help = array_reduce(
				get_terms( $atts['tax'] ),
				function( $carry, $item ) {
						return $carry . sprintf( __( '<li>Gallery: <b>%1$s</b>, Slug: <code>%2$s</code></li>', 'fb-image-gal' ), $item->name, $item->slug );
				},
				'<br />'
			);
			return __( 'You have to provide the "slug" parammeter. Some options are: ', 'fb-image-gal' ) . $help;
		}

		$params = array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => $atts['count'],
			'orderby'        => $atts['sort'],
			'order'          => $atts['order'],
			'tax_query'      => array(
				array(
					'taxonomy' => $atts['tax'],
					'field'    => 'slug',
					'terms'    => array( $atts['slug'] ),
				),
			),
		);

		switch ( $atts['wrap'] ) {
			case 'ul':
				$atts['item-wrap'] = 'li';
				break;
			default:
				$atts['item-wrap'] = 'div';
				break;
		}

		$class   = ! empty( $atts['class'] ) ? ' class="' . $atts['class'] . '"' : '';
		$baseurl = wp_get_upload_dir()['baseurl'];

		$res = array();

		$query = new \WP_Query( $params );
		while ( $query->have_posts() ) {
			$query->the_post();
			$img = wp_get_attachment_image( get_the_ID(), $atts['size'] );
			if ( strtolower( $atts['link'] ) !== 'no' ) {
				$url = get_post_meta( get_the_ID(), '_wp_attachment_metadata', true )['file'];
				$img = '<a href="' . $baseurl . '/' . $url . '">' . $img . '</a>';
			}
			$res[] = '<' . $atts['item-wrap'] . '>' . $img . '</' . $atts['item-wrap'] . '>';
		}

		return '<' . $atts['wrap'] . $class . '>' . implode( "\n", $res ) . '</' . $atts['wrap'] . '>';
	}
}
