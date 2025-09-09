<?php
/**
 * Plugin Name: Case Study Blocks
 * Description: Bloques Gutenberg: Hero, Case Study Item y Case Studies Grid dinámico.
 * Version: 1.1.0
 * Author: Tu Nombre
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Registrar Hero e Item (bloques estáticos)
function csb_register_blocks() {
  register_block_type( __DIR__ . '/blocks/hero' );
  register_block_type( __DIR__ . '/blocks/item' );
}
add_action( 'init', 'csb_register_blocks' );

// Registrar Grid dinámico
function csb_register_case_studies_grid() {
  register_block_type( __DIR__ . '/blocks/grid', [
    'render_callback' => 'csb_render_case_studies_grid'
  ]);
}

function csb_render_case_studies_grid() {
  $json_path = plugin_dir_path( __FILE__ ) . 'case-studies.json';
  if ( ! file_exists( $json_path ) ) {
    return '<p>No se encontró el archivo case-studies.json</p>';
  }

  $json = file_get_contents( $json_path );
  $items = json_decode($json, true);
  if ( ! is_array($items) ) {
    return '<p>Error al leer case-studies.json</p>';
  }

  ob_start();
  ?>
  <section class="cs-grid">
    <?php foreach ($items as $item): ?>
      <article class="cs-item">
        <figure class="cs-item__media">
          <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
        </figure>
        <div class="cs-item__body">
          <h3 class="cs-item__title"><?php echo esc_html($item['title']); ?></h3>
          <p class="cs-item__desc"><?php echo esc_html($item['description']); ?></p>
          <p class="cs-item__link">
            <a href="<?php echo esc_url($item['link']); ?>">Read More →</a>
          </p>
        </div>
      </article>
    <?php endforeach; ?>
  </section>
  <?php
  return ob_get_clean();
}
add_action('init', 'csb_register_case_studies_grid');
