<?php
/**
 * Template Name: Prompts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package memorylab
 */

get_header(); ?>
<style>
  .prompt-item {
    transition: opacity 0.25s ease;
}

.prompt-item.is-hidden {
    display: none;
}
</style>
<main id="primary" class="page secondary-page site-main">

    <section class="catalog-main container" id="catalog-page">
        <div class="catalog-header">
            <h2 class="h2-catalog">
                Галерея <span class="text-gradient">промптов</span>
            </h2>
        </div>

        <div class="catalog-wrap">
            <div class="catalog-filter_wrap">
                <div class="catalog-filter">
                    <div class="filters-prompts">
                        <button class="filter-btn-prompt active" data-filter="all">Все предложения</button>
                        <?php
                        $filter_cats = get_terms(array(
                            'taxonomy'   => 'prompt_category',
                            'hide_empty' => true,
                        ));

                        if (!empty($filter_cats) && !is_wp_error($filter_cats)) :
                            foreach ($filter_cats as $fcat) : ?>
                                <button class="filter-btn-prompt" data-filter="<?php echo esc_attr($fcat->slug); ?>">
                                    <?php echo esc_html($fcat->name); ?>
                                </button>
                            <?php endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <!-- Карточки -->
            <div class="prompt-grid" id="prompt-grid">
                <?php
                // -----------------------------------------------------------------
                // Хелпер: получение картинок через Pods API
                // -----------------------------------------------------------------
                if (!function_exists('get_prompt_images')) {
                    function get_prompt_images($post_id) {
                        $pod = pods('prompt', $post_id);
                        if (!$pod) return array();

                        $images = $pod->field('prompt-imgs');
                        if (empty($images) || !is_array($images)) return array();

                        $ids = array();
                        foreach ($images as $image) {
                            if (is_array($image) && isset($image['ID'])) {
                                $ids[] = (int) $image['ID'];
                            } elseif (is_numeric($image)) {
                                $ids[] = (int) $image;
                            } else {
                                $aid = pods_image_id($image);
                                if ($aid) $ids[] = (int) $aid;
                            }
                        }
                        return array_values(array_filter($ids));
                    }
                }

                // -----------------------------------------------------------------
                // Получаем все посты prompt
                // -----------------------------------------------------------------
                $all_query = new WP_Query(array(
                    'post_type'      => 'prompt',
                    'posts_per_page' => -1,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));

                $by_type = array(
                    'prompt-2-foto'     => array(),
                    'prompt-small-foto' => array(),
                    'prompt-big-foto'   => array(),
                );

                if ($all_query->have_posts()) :
                    while ($all_query->have_posts()) : $all_query->the_post();
                        $post_id = get_the_ID();
                        $types = get_the_terms($post_id, 'prompt_type');
                        $slug = (!empty($types) && !is_wp_error($types)) ? $types[0]->slug : '';

                        if (!isset($by_type[$slug])) {
                            $slug = 'prompt-small-foto';
                        }
                        $by_type[$slug][] = $post_id;
                    endwhile;
                    wp_reset_postdata();
                endif;

                // -----------------------------------------------------------------
                // Порядок: 1 × 2-foto → 4 × small → 1 × big (цикл)
                // -----------------------------------------------------------------
                $queue_2foto = $by_type['prompt-2-foto'];
                $queue_small = $by_type['prompt-small-foto'];
                $queue_big   = $by_type['prompt-big-foto'];

                $ordered_ids = array();
                $used = array();

                $take_next = function ($preferred) use (&$queue_2foto, &$queue_small, &$queue_big, &$used) {
                    $fallbacks = array(
                        'prompt-2-foto'     => array('prompt-2-foto', 'prompt-big-foto', 'prompt-small-foto'),
                        'prompt-big-foto'   => array('prompt-big-foto', 'prompt-small-foto'),
                        'prompt-small-foto' => array('prompt-small-foto'),
                    );

                    foreach ($fallbacks[$preferred] as $type) {
                        switch ($type) {
                            case 'prompt-2-foto':     $q = &$queue_2foto; break;
                            case 'prompt-big-foto':   $q = &$queue_big;   break;
                            case 'prompt-small-foto': $q = &$queue_small; break;
                            default: continue 2;
                        }

                        while (!empty($q)) {
                            $id = array_shift($q);
                            if (!in_array($id, $used, true)) {
                                $used[] = $id;
                                return array($id, $type);
                            }
                        }
                    }
                    return array(null, null);
                };

                while (!empty($queue_2foto) || !empty($queue_small) || !empty($queue_big)) {
                    list($id, $type) = $take_next('prompt-2-foto');
                    if ($id) $ordered_ids[] = array('id' => $id, 'type' => $type);

                    for ($i = 0; $i < 4; $i++) {
                        list($id, $type) = $take_next('prompt-small-foto');
                        if ($id) $ordered_ids[] = array('id' => $id, 'type' => $type);
                    }

                    list($id, $type) = $take_next('prompt-big-foto');
                    if ($id) $ordered_ids[] = array('id' => $id, 'type' => $type);

                    $remaining = count($queue_2foto) + count($queue_small) + count($queue_big);
                    if ($remaining === 0) break;
                }

                // -----------------------------------------------------------------
                // Вывод
                // -----------------------------------------------------------------
                if (!empty($ordered_ids)) :
                    foreach ($ordered_ids as $item) :
                        $post_id   = $item['id'];
                        $type_slug = $item['type'];
                        $post      = get_post($post_id);
                        setup_postdata($post);

                        $images = get_prompt_images($post_id);

                        if ($type_slug === 'prompt-2-foto') {
                            $images = array_slice($images, 0, 2);
                        } else {
                            $images = array_slice($images, 0, 1);
                        }

                        $cats = get_the_terms($post_id, 'prompt_category');
                        $cats = (!empty($cats) && !is_wp_error($cats)) ? $cats : array();

                        $cat_slugs = array();
                        foreach ($cats as $c) $cat_slugs[] = $c->slug;
                        $cat_data = esc_attr(implode(' ', $cat_slugs));

                        // ---------- prompt-2-foto ----------
                        if ($type_slug === 'prompt-2-foto') :
                            while (count($images) < 2) {
                                $images[] = $images[0] ?? 0;
                            }

                            $img_url_1 = $images[0] ? wp_get_attachment_image_url($images[0], 'large') : '';
                            $img_url_2 = $images[1] ? wp_get_attachment_image_url($images[1], 'large') : '';
                            ?>
                            <div class="prompt-item prompt-wide"
                                 data-category="<?php echo $cat_data; ?>"
                                 data-type="prompt-2-foto">

                                <div class="prompt-image-wrapper">
                                    <div class="prompt-img-plus">
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/images/swap-icon.svg'); ?>"
                                             alt="Промпт" class="plus-prompts-icon" />
                                        <?php if ($img_url_1) : ?>
                                            <img src="<?php echo esc_url($img_url_1); ?>"
                                                 alt="<?php echo esc_attr(get_the_title($post_id)); ?>"
                                                 class="prompt-image" />
                                        <?php endif; ?>
                                        <?php if ($img_url_2) : ?>
                                            <img src="<?php echo esc_url($img_url_2); ?>"
                                                 alt="<?php echo esc_attr(get_the_title($post_id)); ?>"
                                                 class="prompt-image" />
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="prompt-content">
                                    <?php if (!empty($cats)) : ?>
                                        <div class="badge-prompt-wrap">
                                            <?php
                                            $badge_classes = array('orange-badge', 'violet-badge');
                                            foreach ($cats as $i => $cat) :
                                                $badge_class = $badge_classes[$i % count($badge_classes)];
                                                ?>
                                                <span class="badge-prompt <?php echo esc_attr($badge_class); ?>">
                                                    <?php echo esc_html($cat->name); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="prompt-title"><?php echo esc_html(get_the_title($post_id)); ?></h3>
                                    <div class="prompt-desc">
                                        <?php echo apply_filters('the_content', $post->post_content); ?>
                                    </div>
                                </div>
                            </div>

                        <?php
                        // ---------- prompt-big-foto ----------
                        elseif ($type_slug === 'prompt-big-foto') :
                            $img_id = $images[0] ?? 0;
                            $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'full') : '';
                            ?>
                            <div class="prompt-item prompt-wide"
                                 data-category="<?php echo $cat_data; ?>"
                                 data-type="prompt-big-foto">

                                <div class="prompt-image-wrapper">
                                    <?php if ($img_url) : ?>
                                        <img src="<?php echo esc_url($img_url); ?>"
                                             alt="<?php echo esc_attr(get_the_title($post_id)); ?>"
                                             class="prompt-image" />
                                    <?php endif; ?>
                                </div>

                                <div class="prompt-content">
                                    <?php if (!empty($cats)) : ?>
                                        <div class="badge-prompt-wrap">
                                            <?php
                                            $badge_classes = array('orange-badge', 'violet-badge');
                                            foreach ($cats as $i => $cat) :
                                                $badge_class = $badge_classes[$i % count($badge_classes)];
                                                ?>
                                                <span class="badge-prompt <?php echo esc_attr($badge_class); ?>">
                                                    <?php echo esc_html($cat->name); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="prompt-title"><?php echo esc_html(get_the_title($post_id)); ?></h3>
                                    <div class="prompt-desc">
                                        <?php echo apply_filters('the_content', $post->post_content); ?>
                                    </div>
                                </div>
                            </div>

                        <?php
                        // ---------- prompt-small-foto ----------
                        else :
                            $img_id = $images[0] ?? 0;
                            $img_url = $img_id ? wp_get_attachment_image_url($img_id, 'large') : '';
                            ?>
                            <div class="prompt-item"
                                 data-category="<?php echo $cat_data; ?>"
                                 data-type="prompt-small-foto">

                                <div class="prompt-image-wrapper">
                                    <?php if ($img_url) : ?>
                                        <img src="<?php echo esc_url($img_url); ?>"
                                             alt="<?php echo esc_attr(get_the_title($post_id)); ?>"
                                             class="prompt-image" />
                                    <?php endif; ?>
                                </div>

                                <div class="prompt-content">
                                    <?php if (!empty($cats)) : ?>
                                        <div class="badge-prompt-wrap">
                                            <?php
                                            $badge_classes = array('orange-badge', 'violet-badge');
                                            foreach ($cats as $i => $cat) :
                                                $badge_class = $badge_classes[$i % count($badge_classes)];
                                                ?>
                                                <span class="badge-prompt <?php echo esc_attr($badge_class); ?>">
                                                    <?php echo esc_html($cat->name); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="prompt-title"><?php echo esc_html(get_the_title($post_id)); ?></h3>
                                    <div class="prompt-desc">
                                        <?php echo apply_filters('the_content', $post->post_content); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endforeach;
                    wp_reset_postdata();
                else :
                    echo '<p>Нет доступных промптов.</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
</main>
              <script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn-prompt');
    const items = document.querySelectorAll('#prompt-grid .prompt-item');

    if (!filterButtons.length || !items.length) return;

    filterButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Переключаем активную кнопку
            filterButtons.forEach(function (b) {
                b.classList.remove('active');
            });
            this.classList.add('active');

            const filter = this.dataset.filter || 'all';

            // Фильтруем карточки
            items.forEach(function (item) {
                const cats = (item.dataset.category || '')
                    .trim()
                    .split(/\s+/)
                    .filter(Boolean);

                let show = false;

                if (filter === 'all') {
                    show = true;
                } else if (cats.includes(filter)) {
                    show = true;
                }

                if (show) {
                    item.classList.remove('is-hidden');
                    item.style.display = '';
                } else {
                    item.classList.add('is-hidden');
                    item.style.display = 'none';
                }
            });
        });
    });
});</script>
<?php get_footer();