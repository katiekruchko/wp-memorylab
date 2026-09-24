<?php
/**
 * Plugin Name: Instagram Feed Sync
 * Description: Скачивает изображения из Instagram через официальный API раз в день и подменяет блок .instagram-wrap. Переключатель Instagram / Дефолтные изображения + cron-токен.
 * Version: 1.0.0
 * Author: Memorylab
 * Text Domain: ifs
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'IFS_VERSION', '1.0.0' );
define( 'IFS_FILE', __FILE__ );
define( 'IFS_URL', plugin_dir_url( __FILE__ ) );

/* ==============================================================
 *  ГЛАВНЫЙ КЛАСС ПЛАГИНА
 * ============================================================ */
class IFS_Plugin {

    const OPT_MODE         = 'ifs_mode';           // 'instagram' | 'default'
    const OPT_TOKEN        = 'ifs_token';          // секретный токен для cron
    const OPT_ACCESS_TOKEN = 'ifs_access_token';   // Instagram Access Token
    const OPT_LAST         = 'ifs_last_sync';      // timestamp последней синхронизации
    const OPT_MAP          = 'ifs_image_map';      // массив URL скачанных картинок
    const OPT_COUNT        = 'ifs_needed';         // сколько картинок нужно
    const OPT_LAST_ERROR   = 'ifs_last_error';     // последняя ошибка API

    const ENDPOINT_MEDIA   = 'https://graph.instagram.com/me/media';
    const ENDPOINT_REFRESH = 'https://graph.instagram.com/refresh_access_token';

    const TOTAL_SLOTS = 14;

    public static function init() {
        add_action( 'admin_menu',            [ __CLASS__, 'admin_menu' ] );
        add_action( 'admin_init',            [ __CLASS__, 'register_settings' ] );
        add_action( 'admin_post_ifs_save',   [ __CLASS__, 'handle_save' ] );
        add_action( 'admin_post_ifs_sync',   [ __CLASS__, 'handle_manual_sync' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );

        add_action( 'init', [ __CLASS__, 'maybe_handle_cron' ] );
        add_shortcode( 'instagram_feed', [ __CLASS__, 'render_shortcode' ] );
    }

    /* ---------- Активация / деактивация ---------- */
    public static function activate() {
        self::ensure_upload_dir();

        if ( ! get_option( self::OPT_TOKEN ) ) {
            update_option( self::OPT_TOKEN, wp_generate_password( 32, false, false ) );
        }
        if ( ! get_option( self::OPT_MODE ) ) {
            update_option( self::OPT_MODE, 'default' );
        }
        if ( ! get_option( self::OPT_COUNT ) ) {
            update_option( self::OPT_COUNT, self::TOTAL_SLOTS );
        }
    }

    public static function deactivate() {}

    /* ---------- Папка для картинок ---------- */
    public static function ensure_upload_dir() {
        $upload = wp_upload_dir();
        $dir = trailingslashit( $upload['basedir'] ) . 'instagram-feed';
        if ( ! file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
        }
        return $dir;
    }

    public static function upload_url() {
        $upload = wp_upload_dir();
        return trailingslashit( $upload['baseurl'] ) . 'instagram-feed';
    }

    /* ---------- Помощники ---------- */
    public static function get_mode() {
        return get_option( self::OPT_MODE, 'default' );
    }

    public static function get_needed() {
        return max( 1, (int) get_option( self::OPT_COUNT, self::TOTAL_SLOTS ) );
    }

    /* ==============================================================
     *  НАСТРОЙКИ В АДМИНКЕ
     * ============================================================ */
    public static function admin_menu() {
        add_options_page(
            'Instagram Feed',
            'Instagram Feed',
            'manage_options',
            'ifs-settings',
            [ __CLASS__, 'render_settings_page' ]
        );
    }

    public static function register_settings() {
        register_setting( 'ifs_group', self::OPT_MODE );
        register_setting( 'ifs_group', self::OPT_COUNT );
        register_setting( 'ifs_group', self::OPT_ACCESS_TOKEN );
    }

    public static function enqueue_assets( $hook ) {
        if ( $hook !== 'settings_page_ifs-settings' ) return;
        wp_enqueue_style( 'ifs-admin', IFS_URL . 'assets/admin.css', [], IFS_VERSION );
    }

    public static function handle_save() {
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'No access' );
        check_admin_referer( 'ifs_save' );

        $mode  = sanitize_text_field( $_POST['mode'] ?? 'default' );
        $count = max( 1, (int) ( $_POST['count'] ?? self::TOTAL_SLOTS ) );
        $token = sanitize_textarea_field( $_POST['access_token'] ?? '' );

        update_option( self::OPT_MODE, in_array( $mode, [ 'instagram', 'default' ], true ) ? $mode : 'default' );
        update_option( self::OPT_COUNT, $count );
        update_option( self::OPT_ACCESS_TOKEN, $token );

        if ( ! empty( $_POST['regenerate_token'] ) ) {
            update_option( self::OPT_TOKEN, wp_generate_password( 32, false, false ) );
        }

        wp_safe_redirect( add_query_arg(
            [ 'page' => 'ifs-settings', 'saved' => 1 ],
            admin_url( 'options-general.php' )
        ) );
        exit;
    }

    public static function handle_manual_sync() {
        if ( ! current_user_can( 'manage_options' ) ) wp_die( 'No access' );
        check_admin_referer( 'ifs_sync' );

        self::sync_instagram();

        wp_safe_redirect( add_query_arg(
            [ 'page' => 'ifs-settings', 'synced' => 1 ],
            admin_url( 'options-general.php' )
        ) );
        exit;
    }

    public static function render_settings_page() {
        $mode   = self::get_mode();
        $count  = self::get_needed();
        $token  = get_option( self::OPT_TOKEN );
        $access = get_option( self::OPT_ACCESS_TOKEN );
        $last   = get_option( self::OPT_LAST );
        $map    = get_option( self::OPT_MAP, [] );
        $error  = get_option( self::OPT_LAST_ERROR );

        $cron_url = add_query_arg( [
            'ifs_cron' => 1,
            'token'    => $token,
        ], home_url( '/' ) );

        $refresh_url = add_query_arg( [
            'ifs_cron'    => 1,
            'ifs_refresh' => 1,
            'token'       => $token,
        ], home_url( '/' ) );

        $dir = self::ensure_upload_dir();
        ?>
        <div class="wrap ifs-wrap">
            <h1>Instagram Feed Sync</h1>

            <?php if ( isset( $_GET['saved'] ) ): ?>
                <div class="notice notice-success is-dismissible"><p>Настройки сохранены.</p></div>
            <?php endif; ?>

            <?php if ( isset( $_GET['synced'] ) ): ?>
                <div class="notice notice-success is-dismissible">
                    <p>Синхронизация выполнена. Картинок в базе: <b><?php echo count( $map ); ?></b></p>
                </div>
            <?php endif; ?>

            <?php if ( $error ): ?>
                <div class="notice notice-error is-dismissible">
                    <p><b>Последняя ошибка API:</b> <?php echo esc_html( $error ); ?></p>
                </div>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'ifs_save' ); ?>
                <input type="hidden" name="action" value="ifs_save">

                <h2 class="title">Основные настройки</h2>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row">Режим отображения</th>
                        <td>
                            <fieldset>
                                <label>
                                    <input type="radio" name="mode" value="instagram" <?php checked( $mode, 'instagram' ); ?>>
                                    <b>Instagram</b> — скачанные изображения
                                </label><br>
                                <label>
                                    <input type="radio" name="mode" value="default" <?php checked( $mode, 'default' ); ?>>
                                    <b>Дефолтные</b> — изображения из темы
                                </label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="ifs-count">Сколько изображений выводить</label></th>
                        <td>
                            <input type="number" id="ifs-count" name="count"
                                   value="<?php echo esc_attr( $count ); ?>" min="1" max="50" class="small-text">
                            <p class="description">Ряды формируются по 7 штук. По умолчанию — 14 (2 ряда).</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="ifs-token">Instagram Access Token</label></th>
                        <td>
                            <textarea id="ifs-token" name="access_token" rows="4"
                                      class="large-text code"><?php echo esc_textarea( $access ); ?></textarea>
                            <p class="description">
                                Долгоживущий токен (60 дней). Получить:
                                <a href="https://developers.facebook.com/apps" target="_blank" rel="noopener">
                                    Meta for Developers → Instagram API with Instagram Login
                                </a>.
                                Scope: <code>instagram_business_basic</code>.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">Регенерировать cron-токен</th>
                        <td>
                            <label>
                                <input type="checkbox" name="regenerate_token" value="1">
                                Создать новый секретный токен (старые ссылки перестанут работать)
                            </label>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <button class="button button-primary">Сохранить изменения</button>
                </p>
            </form>

            <hr>

            <h2 class="title">Cron URL</h2>
            <p>Добавьте этот URL в <a href="https://cron-job.org" target="_blank" rel="noopener">cron-job.org</a> — <b>раз в день</b>:</p>
            <p>
                <input type="text" readonly value="<?php echo esc_attr( $cron_url ); ?>" class="ifs-copy" onclick="this.select()">
            </p>

            <h3>Обновление Access Token</h3>
            <p>Токен живёт 60 дней. Настройте эту ссылку отдельной задачей — <b>раз в 50 дней</b>:</p>
            <p>
                <input type="text" readonly value="<?php echo esc_attr( $refresh_url ); ?>" class="ifs-copy" onclick="this.select()">
            </p>

            <hr>

            <h2 class="title">Ручная синхронизация</h2>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <?php wp_nonce_field( 'ifs_sync' ); ?>
                <input type="hidden" name="action" value="ifs_sync">
                <p>
                    <button class="button">Синхронизировать сейчас</button>
                    <span class="description">Скачает новые изображения из Instagram прямо сейчас.</span>
                </p>
            </form>

            <hr>

            <h2 class="title">Статус</h2>
            <table class="widefat striped ifs-status">
                <tbody>
                    <tr>
                        <td><b>Всего скачано</b></td>
                        <td><?php echo count( $map ); ?> из <?php echo esc_html( $count ); ?></td>
                    </tr>
                    <tr>
                        <td><b>Последняя синхронизация</b></td>
                        <td><?php echo $last ? esc_html( date_i18n( 'd.m.Y H:i', $last ) ) : '—'; ?></td>
                    </tr>
                    <tr>
                        <td><b>Папка для картинок</b></td>
                        <td><code><?php echo esc_html( $dir ); ?></code></td>
                    </tr>
                    <tr>
                        <td><b>Публичный URL папки</b></td>
                        <td><code><?php echo esc_html( self::upload_url() ); ?></code></td>
                    </tr>
                    <tr>
                        <td><b>Шорткод</b></td>
                        <td><code>[instagram_feed]</code></td>
                    </tr>
                    <tr>
                        <td><b>Access Token установлен</b></td>
                        <td><?php echo $access ? '✅ да' : '❌ нет'; ?></td>
                    </tr>
                </tbody>
            </table>

            <?php if ( ! empty( $map ) ): ?>
                <h3>Последние скачанные изображения</h3>
                <div class="ifs-preview">
                    <?php foreach ( array_slice( $map, -14 ) as $url ): ?>
                        <img src="<?php echo esc_url( $url ); ?>" alt="">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    /* ==============================================================
     *  INSTAGRAM API
     * ============================================================ */
    public static function fetch_images() {
        $token = get_option( self::OPT_ACCESS_TOKEN );
        if ( ! $token ) {
            return new WP_Error( 'ifs_no_token', 'Access Token не задан в настройках плагина.' );
        }

        $all_items = [];
        $max_pages = 10;   // до 10 страниц × 50 = до 500 постов
        $page      = 0;

        // Базовый URL
        $url = add_query_arg( [
            'fields'       => 'id,media_url,media_type,thumbnail_url,timestamp,permalink,children{media_url,media_type,thumbnail_url}',
            'access_token' => $token,
            'limit'        => 50,
        ], self::ENDPOINT_MEDIA );

        // Пагинация через paging.next
        while ( $url && $page < $max_pages ) {
            $page++;

            $response = wp_remote_get( $url, [
                'timeout'    => 25,
                'user-agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
            ] );

            if ( is_wp_error( $response ) ) {
                if ( $page === 1 ) return $response;
                break; // при ошибке на 2+ странице — отдаём то, что собрали
            }

            $code = (int) wp_remote_retrieve_response_code( $response );
            $data = json_decode( wp_remote_retrieve_body( $response ), true );

            if ( $code !== 200 || ! is_array( $data ) ) {
                if ( $page === 1 ) {
                    $msg = $data['error']['message'] ?? 'Instagram API вернул HTTP ' . $code;
                    return new WP_Error( 'ifs_api_error', $msg, [ 'status' => $code ] );
                }
                break;
            }

            if ( ! empty( $data['data'] ) && is_array( $data['data'] ) ) {
                $all_items = array_merge( $all_items, $data['data'] );
            }

            // Есть ли следующая страница?
            $url = $data['paging']['next'] ?? null;
        }

        if ( empty( $all_items ) ) {
            return new WP_Error( 'ifs_empty', 'Instagram не вернул медиа.' );
        }

        // Разворачиваем всё в плоский список URL
        $urls = [];

        foreach ( $all_items as $item ) {
            $type = $item['media_type'] ?? '';

            // --- Карусель: забираем всех детей ---
            if ( $type === 'CAROUSEL_ALBUM' && ! empty( $item['children']['data'] ) ) {
                foreach ( $item['children']['data'] as $child ) {
                    $child_type = $child['media_type'] ?? '';

                    if ( $child_type === 'VIDEO' ) {
                        if ( ! empty( $child['thumbnail_url'] ) ) {
                            $urls[] = $child['thumbnail_url'];
                        }
                    } elseif ( ! empty( $child['media_url'] ) ) {
                        $urls[] = $child['media_url'];
                    }
                }
                continue;
            }

            // --- Обычные посты ---
            if ( $type === 'VIDEO' ) {
                if ( ! empty( $item['thumbnail_url'] ) ) {
                    $urls[] = $item['thumbnail_url'];
                }
            } elseif ( ! empty( $item['media_url'] ) ) {
                $urls[] = $item['media_url'];
            }
        }

        return array_values( array_unique( $urls ) );
    }

    public static function refresh_access_token() {
        $token = get_option( self::OPT_ACCESS_TOKEN );
        if ( ! $token ) {
            return [ 'ok' => false, 'error' => 'Нет токена для обновления.' ];
        }

        $url = add_query_arg( [
            'grant_type'   => 'ig_refresh_token',
            'access_token' => $token,
        ], self::ENDPOINT_REFRESH );

        $response = wp_remote_get( $url, [ 'timeout' => 25 ] );

        if ( is_wp_error( $response ) ) {
            return [ 'ok' => false, 'error' => $response->get_error_message() ];
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! empty( $data['access_token'] ) ) {
            update_option( self::OPT_ACCESS_TOKEN, $data['access_token'] );
            return [ 'ok' => true, 'expires_in' => $data['expires_in'] ?? null ];
        }

        return [
            'ok'    => false,
            'error' => $data['error']['message'] ?? 'Неизвестная ошибка при обновлении токена.',
        ];
    }

    /* ==============================================================
     *  CRON + СИНХРОНИЗАЦИЯ
     * ============================================================ */
    public static function maybe_handle_cron() {
        if ( empty( $_GET['ifs_cron'] ) ) return;

        $token = sanitize_text_field( $_GET['token'] ?? '' );
        $saved = get_option( self::OPT_TOKEN );

        if ( ! $token || ! $saved || ! hash_equals( $saved, $token ) ) {
            status_header( 403 );
            header( 'Content-Type: application/json' );
            echo json_encode( [ 'ok' => false, 'error' => 'Forbidden' ] );
            exit;
        }

        if ( ! empty( $_GET['ifs_refresh'] ) ) {
            header( 'Content-Type: application/json' );
            echo json_encode( self::refresh_access_token() );
            exit;
        }

        self::sync_instagram();

        $map = get_option( self::OPT_MAP, [] );
        header( 'Content-Type: application/json' );
        echo json_encode( [
            'ok'        => true,
            'synced_at' => current_time( 'mysql' ),
            'count'     => count( $map ),
            'error'     => get_option( self::OPT_LAST_ERROR ) ?: null,
        ] );
        exit;
    }

    public static function sync_instagram() {
        self::ensure_upload_dir();
        update_option( self::OPT_LAST, time() );

        $images = self::fetch_images();

        if ( is_wp_error( $images ) ) {
            update_option( self::OPT_LAST_ERROR, $images->get_error_message() );
            return;
        }

        update_option( self::OPT_LAST_ERROR, '' );

        if ( empty( $images ) ) {
            return;
        }

        $dir   = self::ensure_upload_dir();
        $map   = get_option( self::OPT_MAP, [] );
        $known = array_flip( array_map( 'basename', $map ) );

        require_once ABSPATH . 'wp-admin/includes/file.php';

        foreach ( $images as $url ) {
            $basename = self::url_to_filename( $url );

            if ( isset( $known[ $basename ] ) ) continue;

            $tmp = download_url( $url, 30 );
            if ( is_wp_error( $tmp ) ) continue;

            $dest = trailingslashit( $dir ) . $basename;

            if ( ! @rename( $tmp, $dest ) ) {
                @copy( $tmp, $dest );
                @unlink( $tmp );
            }

            if ( file_exists( $dest ) ) {
                $map[] = trailingslashit( self::upload_url() ) . $basename;
                $known[ $basename ] = true;
            }
        }

        // Оставляем запас: нужно × 2, минимум 30
        $keep = max( self::get_needed() * 2, 30 );
        if ( count( $map ) > $keep ) {
            $map = array_slice( $map, -$keep );
        }

        update_option( self::OPT_MAP, array_values( $map ) );
    }

    public static function url_to_filename( $url ) {
        $path = wp_parse_url( $url, PHP_URL_PATH );
        $name = $path ? basename( $path ) : '';

        // Instagram отдаёт хеши без расширения — приводим к jpg
        if ( ! $name || ! preg_match( '/\.(jpg|jpeg|png|webp|gif)$/i', $name ) ) {
            return md5( $url ) . '.jpg';
        }

        return sanitize_file_name( $name );
    }

    /* ==============================================================
     *  РЕНДЕР ШОРТКОДА
     * ============================================================ */
    public static function collect_images() {
        $mode = self::get_mode();
        $need = self::get_needed();
        $map  = get_option( self::OPT_MAP, [] );
        $urls = [];

        if ( $mode === 'instagram' && ! empty( $map ) ) {
            $urls = array_slice( $map, -$need );
        }

        if ( count( $urls ) < $need ) {
            $defaults = self::default_images();
            $deficit  = $need - count( $urls );
            $urls     = array_merge( $urls, array_slice( $defaults, 0, $deficit ) );
        }

        return $urls;
    }

    public static function default_images() {
        $base = trailingslashit( get_template_directory_uri() ) . 'images/';
        $out  = [];

        for ( $i = 1; $i <= self::TOTAL_SLOTS; $i++ ) {
            $path = trailingslashit( get_template_directory() ) . "images/insta-{$i}.png";
            $out[] = file_exists( $path )
                ? $base . "insta-{$i}.png"
                : includes_url( 'images/media/default.png' );
        }

        return $out;
    }

    public static function render_shortcode() {
        $urls = self::collect_images();
        $rows = array_chunk( $urls, 7 );

        ob_start();
        ?>
        <div class="instagram-wrap">
            <div class="instagram-grid-container">
                <?php foreach ( $rows as $i => $row ): ?>
                    <div class="row-insta row-insta-<?php echo (int) ( $i + 1 ); ?>">
                        <?php foreach ( $row as $src ): ?>
                            <div class="instagram-item">
                                <img src="<?php echo esc_url( $src ); ?>" alt="" loading="lazy" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="gradient-overlay"></div>
        </div>
        <?php
        return ob_get_clean();
    }
}

/* ==============================================================
 *  СТАРТ
 * ============================================================ */
register_activation_hook( __FILE__, [ 'IFS_Plugin', 'activate' ] );
register_deactivation_hook( __FILE__, [ 'IFS_Plugin', 'deactivate' ] );

IFS_Plugin::init();