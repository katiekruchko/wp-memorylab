<?php
$pod = pods(get_post_type(), get_the_ID());

// Текст — через display(), Pods сам обработает безопасно
$ramki_text = $pod->display('ai-ramki-text');

// Картинки — всё ещё нужен field(), так как display() вернёт HTML-теги <img>, а не URL
$ramki_items = array_filter((array) $pod->field('ai_ramki'), function($item) {
    return !empty($item);
});
$ramki_items = array_values($ramki_items);

if (!empty($ramki_items)):
    $image_urls = [];
    foreach ($ramki_items as $item) {
        $url = '';
        if (is_array($item)) {
            $url = !empty($item['ID']) ? pods_image_url($item['ID'], 'large') : ($item['guid'] ?? '');
        } elseif (is_numeric($item)) {
            $url = pods_image_url($item, 'large');
        } elseif (is_string($item)) {
            $url = $item;
        }
        if (!empty($url)) $image_urls[] = $url;
    }
    ?>
    <section class="eg-ramki gap-section">
        <h2 class="h2-catalog">Примеры <span class="text-gradient">брендированных фоторамок</span></h2>

        <?php if (!empty($ramki_text)): ?>
            <div class="eg-ff-text-block">
                <div class="eg-ff-desc"><?php echo $ramki_text; ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($image_urls)): ?>
            <div class="ramki-wrap">
                <div class="ramki-items">
                    <?php foreach ($image_urls as $img_url): ?>
                        <div class="ramki-item">
                            <img class="ramki-img" src="<?php echo esc_url($img_url); ?>" alt=" " loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>