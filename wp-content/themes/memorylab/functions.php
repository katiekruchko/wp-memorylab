<?php
/**
 * memorylab functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package memorylab
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function memorylab_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on memorylab, use a find and replace
		* to change 'memorylab' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'memorylab', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'memorylab' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'memorylab_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'memorylab_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function memorylab_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'memorylab_content_width', 640 );
}
add_action( 'after_setup_theme', 'memorylab_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function memorylab_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'memorylab' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'memorylab' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'memorylab_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function memorylab_scripts() {
	wp_enqueue_style( 'memorylab-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'memorylab-style', 'rtl', 'replace' );

	wp_enqueue_script( 'memorylab-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'memorylab_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Функция-помощник для получения названия категории/тега по slug
 */
function get_term_display_name($slug) {
    // Сначала пробуем найти в тегах
    $term = get_term_by('slug', $slug, 'post_tag');
    if ($term) {
        return $term->name;
    }

    // Пробуем найти в категориях
    $term = get_term_by('slug', $slug, 'category');
    if ($term) {
        return $term->name;
    }

    // Пробуем найти в пользовательских таксономиях
    $term = get_term_by('slug', $slug, 'your_custom_taxonomy'); // если есть
    if ($term) {
        return $term->name;
    }

    // Если не нашли, возвращаем оригинальный slug с преобразованием
    return ucfirst(str_replace('-', ' ', $slug));
}

/**
 * AJAX загрузка дополнительных постов с фильтрацией
 */
function load_more_posts() {
    check_ajax_referer('load_more_nonce', 'nonce');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'staff';
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : 'all';
    $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $page,
    );

    // Добавляем текстовый поиск если есть
    if (!empty($search) && strlen($search) >= 2) {
        $args['s'] = $search;
    }

    // Добавляем фильтр если не "all"
    if ($filter !== 'all') {
        if (in_array($filter, array('new', 'hit'))) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => $filter,
                )
            );
        } else {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'slug',
                    'terms'    => $filter,
                )
            );
        }
    }

    $query = new WP_Query($args);

    // Получаем название категории/тега для вывода в сообщении
    $filter_name = '';
    if ($filter !== 'all') {
        $filter_name = get_term_display_name($filter);
    }

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/content', 'card');
        endwhile;
    else :
        if ($page == 1) {
            // Только для первой страницы показываем сообщение
            echo '<div class="no-results-message">';
            if (!empty($search) && $filter !== 'all') {
                echo '<p>По запросу "<strong>' . esc_html($search) . '</strong>" в категории "<strong>' . esc_html($filter_name) . '</strong>" ничего не найдено.</p>';
            } elseif (!empty($search)) {
                echo '<p>По запросу "<strong>' . esc_html($search) . '</strong>" ничего не найдено.</p>';
            } elseif ($filter !== 'all') {
                echo '<p>В категории "<strong>' . esc_html($filter_name) . '</strong>" ничего не найдено.</p>';
            } else {
                echo '<p>Интерактивов не найдено.</p>';
            }
            echo '</div>';
        } else {
            echo '<p class="no-more-posts" style="display:none;"></p>';
        }
    endif;

    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

/**
 * AJAX поиск постов
 */
function search_posts_ajax() {
    check_ajax_referer('load_more_nonce', 'nonce');

    $search_term = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'staff';

    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        's'              => $search_term,
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/content', 'card');
        endwhile;
    else :
        echo '<p class="no-results">По вашему запросу ничего не найдено</p>';
    endif;

    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_search_posts', 'search_posts_ajax');
add_action('wp_ajax_nopriv_search_posts', 'search_posts_ajax');

/**
 * Разрешить CORS для AJAX запросов
 */
add_action('init', function() {
    header("Access-Control-Allow-Origin: https://memorylab.by");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type, X-WP-Nonce");
});

add_action('admin_init', function() {
    header("Access-Control-Allow-Origin: https://memorylab.by");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type, X-WP-Nonce");
});

/**
 * AJAX поиск продуктов по названию и категориям
 */
function search_products_ajax() {
    check_ajax_referer('load_more_nonce', 'nonce');

    $search_term = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : 'all';

    if (empty($search_term) || strlen($search_term) < 2) {
        wp_die();
    }

    $args = array(
        'post_type'      => 'staff',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'relevance',
        's'              => $search_term,
    );

    // Добавляем поиск по таксономиям
    add_filter('posts_where', function($where) use ($search_term) {
        global $wpdb;

        if (!empty($search_term)) {
            $where .= " OR (EXISTS (
                SELECT 1 FROM {$wpdb->term_relationships}
                INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_taxonomy}.term_taxonomy_id = {$wpdb->term_relationships}.term_taxonomy_id
                INNER JOIN {$wpdb->terms} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id
                WHERE {$wpdb->term_relationships}.object_id = {$wpdb->posts}.ID
                AND (taxonomy = 'category' OR taxonomy = 'post_tag')
                AND {$wpdb->terms}.name LIKE '%" . esc_sql($wpdb->esc_like($search_term)) . "%'
            ))";
        }

        return $where;
    });

    // Если есть активный фильтр
    if ($filter !== 'all') {
        if (in_array($filter, array('new', 'hit'))) {
            $args['tax_query'][] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => $filter,
            );
        } else {
            $args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $filter,
            );
        }
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/content', 'card');
        endwhile;

        echo '<div class="search-results-count">';
        echo 'Найдено: ' . $query->found_posts . ' интерактивов';
        echo '</div>';
    else :
        echo '<div class="no-search-results">';
        echo '<p>По вашему запросу <strong>"' . esc_html($search_term) . '"</strong> ничего не найдено</p>';
        echo '</div>';
    endif;

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_search_products', 'search_products_ajax');
add_action('wp_ajax_nopriv_search_products', 'search_products_ajax');

/**
 * AJAX обработчик AI калькулятора
 */
function ai_calculate_ajax() {
    check_ajax_referer('load_more_nonce', 'nonce');

    // Получаем данные из формы
    parse_str($_POST['data'], $form_data);

    $event_type = isset($form_data['event_type']) ? sanitize_text_field($form_data['event_type']) : '';
    $duration = isset($form_data['duration']) ? sanitize_text_field($form_data['duration']) : '';
    $format = isset($form_data['format']) ? sanitize_text_field($form_data['format']) : '';
    $people_count = isset($form_data['people_count']) ? intval($form_data['people_count']) : 200;

    // Здесь логика подбора оборудования на основе введенных данных
    // Это пример - нужно адаптировать под вашу логику

    $args = array(
        'post_type'      => 'staff',
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'rand', // или другая логика
    );

    // Пример: подбор по типу мероприятия
    if ($event_type) {
        $args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'name',
            'terms'    => $event_type,
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="ai-results-header">';
        echo '<h3>Рекомендуем для вашего мероприятия:</h3>';
        echo '<p>На основе ваших параметров мы подобрали оптимальное оборудование</p>';
        echo '</div>';

        echo '<div class="cards-grid">';
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/content', 'card');
        endwhile;
        echo '</div>';
    else :
        echo '<div class="no-ai-results">';
        echo '<p>По вашим параметрам не найдено подходящего оборудования.</p>';
        echo '<p>Попробуйте изменить критерии или <a href="/catalog">посмотрите весь каталог</a>.</p>';
        echo '</div>';
    endif;

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_ai_calculate', 'ai_calculate_ajax');
add_action('wp_ajax_nopriv_ai_calculate', 'ai_calculate_ajax');

/**
 * Подключение скриптов для AJAX функционала
 */
function memorylab_enqueue_scripts() {
    // Универсальный AJAX скрипт для всех страниц
    wp_enqueue_script(
        'universal-ajax',
        get_template_directory_uri() . '/js/universal-ajax.js',
        array('jquery'),
        '1.0.0',
        true
    );

    // Локализация для AJAX
    wp_localize_script('universal-ajax', 'ajax_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('load_more_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'memorylab_enqueue_scripts');

/**
 * AJAX обработчик для комбинированного поиска (текст + фильтр)
 */
function combined_search_ajax() {
    check_ajax_referer('load_more_nonce', 'nonce');

    $search_term = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
    $filter = isset($_POST['filter']) ? sanitize_text_field($_POST['filter']) : 'all';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'staff';

    $args = array(
        'post_type'      => $post_type,
        'posts_per_page' => 6,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'paged'          => $page,
    );

    // Добавляем текстовый поиск
    if (!empty($search_term) && strlen($search_term) >= 2) {
        $args['s'] = $search_term;

        // Расширяем поиск на таксономии
        add_filter('posts_where', function($where) use ($search_term) {
            global $wpdb;

            if (!empty($search_term)) {
                $search_term_like = '%' . $wpdb->esc_like($search_term) . '%';

                // Ищем в названиях категорий и тегов
                $where .= " OR (EXISTS (
                    SELECT 1 FROM {$wpdb->term_relationships}
                    INNER JOIN {$wpdb->term_taxonomy} ON {$wpdb->term_taxonomy}.term_taxonomy_id = {$wpdb->term_relationships}.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} ON {$wpdb->terms}.term_id = {$wpdb->term_taxonomy}.term_id
                    WHERE {$wpdb->term_relationships}.object_id = {$wpdb->posts}.ID
                    AND (taxonomy = 'category' OR taxonomy = 'post_tag')
                    AND {$wpdb->terms}.name LIKE '{$search_term_like}'
                ))";
            }

            return $where;
        });
    }

    // Добавляем фильтр по категории/тегу
    if ($filter !== 'all') {
        $tax_query = array();

        if (in_array($filter, array('new', 'hit'))) {
            // Это тег
            $tax_query[] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => $filter,
            );
        } else {
            // Это категория
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field'    => 'slug',
                'terms'    => $filter,
            );
        }

        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);
    $found_posts = $query->found_posts;

    // Получаем название категории/тега для вывода в сообщении
    $filter_name = '';
    if ($filter !== 'all') {
        $filter_name = get_term_display_name($filter);
    }

    if ($query->have_posts()) {
        while ($query->have_posts()) : $query->the_post();
            get_template_part('template-parts/content', 'card');
        endwhile;

        // Добавляем информацию о количестве найденных результатов
        if ($page == 1) {
            echo '<div class="search-info" style="display: none;" data-total="' . $found_posts . '"></div>';
        }
    }
    else {
        echo '<div class="no-results-message">';
        if (!empty($search_term) && $filter !== 'all') {
            echo '<p>По запросу "<strong>' . esc_html($search_term) . '</strong>" в категории "<strong>' . esc_html($filter_name) . '</strong>" ничего не найдено.</p>';
        } elseif (!empty($search_term)) {
            echo '<p>По запросу "<strong>' . esc_html($search_term) . '</strong>" ничего не найдено.</p>';
        } elseif ($filter !== 'all') {
            echo '<p>В категории "<strong>' . esc_html($filter_name) . '</strong>" ничего не найдено.</p>';
        } else {
            echo '<p>Интерактивов не найдено.</p>';
        }
        echo '</div>';
    }

    wp_reset_postdata();
    wp_die();
}

add_action('wp_ajax_combined_search', 'combined_search_ajax');
add_action('wp_ajax_nopriv_combined_search', 'combined_search_ajax');
