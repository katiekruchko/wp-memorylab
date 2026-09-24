=== Instagram Feed Sync ===
Contributors: yourusername
Tags: instagram, feed, api, cron, gallery
Requires at least: 5.6
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Скачивает изображения из Instagram через официальный API раз в день и подменяет блок .instagram-wrap. Переключатель Instagram / Дефолтные изображения и cron-токен.

== Description ==

**Instagram Feed Sync** — плагин для тех, у кого на сайте есть блок с фотографиями Instagram (например, `.instagram-wrap`), и кто хочет автоматически подменять его реальными изображениями из своего Instagram-аккаунта.

Плагин:

* Подключается к официальному **Instagram API with Instagram Login** (Meta Graph API).
* Раз в день скачивает новые изображения в папку `wp-content/uploads/instagram-feed/`.
* Скачивает **только новые файлы** — уже загруженные не дублируются.
* Работает через внешний cron (`cron-job.org`) по секретной ссылке с токеном.
* Имеет встроенный переключатель: **Instagram** или **Дефолтные изображения** из темы.
* Если картинок из Instagram не хватает — автоматически добивает дефолтными.
* Поддерживает обновление долгоживущего Access Token (60 дней) через отдельную cron-ссылку.
* Выводит статус: сколько картинок скачано, когда была последняя синхронизация, последнюю ошибку API.

= Как это работает =

1. Вы получаете долгоживущий Access Token в [Meta for Developers](https://developers.facebook.com/apps).
2. Вставляете токен в настройки плагина.
3. Настраиваете cron на `cron-job.org` — раз в день.
4. В шаблоне темы заменяете ваш HTML-блок на шорткод `[instagram_feed]`.
5. Плагин скачивает картинки и выводит их вместо дефолтных.

Строка для вставки в PHP-шаблон темы:

<?php echo do_shortcode( '[instagram_feed]' ); ?>

Классы HTML-обёртки сохранены ровно такими, как в вашей вёрстке:

<div class="instagram-wrap">
    <div class="instagram-grid-container">
        <div class="row-insta row-insta-1"> ... </div>
        <div class="row-insta row-insta-2"> ... </div>
    </div>
    <div class="gradient-overlay"></div>
</div>

Поэтому **ваши существующие CSS-стили подхватятся автоматически**, ничего в тему добавлять не нужно.

== Installation ==

= Автоматическая установка =

1. Загрузите папку `instagram-feed-sync` в `/wp-content/plugins/`.
2. Активируйте плагин через меню **Плагины** в WordPress.
3. Перейдите в **Настройки → Instagram Feed**.
4. Вставьте свой Instagram Access Token.
5. Выберите режим отображения и количество изображений.
6. Настройте cron на cron-job.org (см. ниже).
7. Замените HTML-блок в теме на шорткод `[instagram_feed]` — вставьте строку:

<?php echo do_shortcode( '[instagram_feed]' ); ?>

= Ручная установка =

1. Скачайте плагин.
2. Распакуйте архив.
3. Загрузите папку `instagram-feed-sync` в `/wp-content/plugins/` через FTP или файловый менеджер хостинга.
4. Активируйте плагин в админке.
5. Дальше — как в автоматической установке.

== Frequently Asked Questions ==

= Какой тип Instagram-аккаунта нужен? =

Только **Business** или **Creator**. Личный (Personal) аккаунт не поддерживается официальным API. Переключение бесплатно: в Instagram → **Settings → Account type and tools → Switch to professional account**.

= Что делать, если API возвращает ошибку? =

Плагин покажет текст ошибки в админке. Самые частые случаи:

* `Invalid OAuth access token` — токен истёк или введён неверно.
* `The user is not an Instagram Business` — аккаунт не Business/Creator.
* `Insufficient permissions` — не выдан scope `instagram_business_basic`.
* `This app is in development mode` — аккаунт не добавлен как Instagram Tester.

= Как долго живёт Access Token? =

**60 дней.** Плагин умеет обновлять его через отдельную cron-ссылку (`&ifs_refresh=1`). Настройте её **раз в 50 дней**, чтобы обновлять токен с запасом. После истечения срока токен обновить нельзя — придётся получать новый вручную.

= Что будет, если картинок из Instagram меньше, чем нужно? =

Недостающие подставятся из дефолтных изображений темы: `/wp-content/themes/ваша-тема/images/insta-1.png` … `insta-14.png`. Если их в теме нет — используется стандартный placeholder WordPress.

= Можно ли использовать плагин без cron? =

Да. На странице настроек есть кнопка **«Синхронизировать сейчас»** для ручного запуска. Но автоматическое обновление раз в день возможно только через внешний cron.

= Что делать, если картинки не обновляются? =

Проверьте:

1. Cron-задача на cron-job.org активна и возвращает JSON с `"ok": true`.
2. Access Token актуален.
3. В Instagram появились новые посты (плагин скачивает только новые).
4. Папка `wp-content/uploads/instagram-feed/` доступна для записи.

= Удаляются ли старые картинки? =

Плагин **не удаляет** уже скачанные файлы — они накапливаются. В базе хранятся последние `нужно × 2` URL (минимум 30). Файлы на диске остаются — можете чистить вручную, если нужно.

= Совместим ли с мультисайтом? =

Пока нет. Плагин использует `get_option` в рамках одного сайта.

== Screenshots ==

1. Страница настроек плагина.
2. Превью последних скачанных изображений.
3. Cron-ссылки для cron-job.org.
4. Результат на сайте (заменённый блок `.instagram-wrap`).

== Changelog ==

= 1.0.0 =
* Первый релиз.
* Интеграция с Instagram API with Instagram Login.
* Cron-синхронизация с секретным токеном.
* Переключатель Instagram / Дефолтные изображения.
* Автоматическое обновление Access Token.
* Русская локализация интерфейса.

== Upgrade Notice ==

= 1.0.0 =
Первый релиз плагина.

== Arbitrary section ==

= Cron-ссылки =

После настройки Access Token в админке вы увидите две ссылки:

**Основная синхронизация** (раз в день):

https://вашсайт.ru/?ifs_cron=1&token=ВАШ_CRON_ТОКЕН

**Обновление Access Token** (раз в 50 дней):

https://вашсайт.ru/?ifs_cron=1&ifs_refresh=1&token=ВАШ_CRON_ТОКЕН

Обе можно скопировать прямо со страницы настроек плагина.

= Шорткод =

В редакторе страницы или записи:

[instagram_feed]

В PHP-шаблоне темы:

<?php echo do_shortcode( '[instagram_feed]' ); ?>

= Где хранятся изображения? =

wp-content/uploads/instagram-feed/

Публичный URL: https://вашсайт.ru/wp-content/uploads/instagram-feed/

= Безопасность =

* Cron-ссылки защищены секретным токеном (32 символа), который сравнивается через `hash_equals()` — устойчиво к timing-атакам.
* Доступ к настройкам — только для пользователей с правом `manage_options`.
* Все входные данные санитизируются (`sanitize_text_field`, `sanitize_textarea_field`, `sanitize_file_name`).
* Прямой доступ к файлам плагина заблокирован проверкой `ABSPATH`.

== External services ==

Плагин обращается к сервисам Meta:

* **Instagram Graph API** (`https://graph.instagram.com/me/media`) — получение списка медиа.
* **Instagram Refresh Token** (`https://graph.instagram.com/refresh_access_token`) — обновление долгоживущего токена.

Данные, которые отправляются: Access Token.
Данные, которые получаются: список URL изображений, метаданные (тип медиа, timestamp, permalink).

Условия использования: https://developers.facebook.com/terms/
Политика конфиденциальности: https://www.facebook.com/privacy/policy/

== Credits ==

* Разработано по заказу.
* Использует официальный Instagram API (Meta).

вот код шаблона, вместо которого сделан шорткод:

<div class="instagram-wrap">
        <div class="instagram-grid-container">
          <div class="row-insta row-insta-1">
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-1.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-2.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-3.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-4.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-5.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-6.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-7.png" alt="" />
            </div>
          </div>
          <div class="row-insta row-insta-2">
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-8.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-9.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-10.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-11.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-12.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-13.png" alt="" />
            </div>
            <div class="instagram-item">
              <img src="<?php echo get_template_directory_uri(); ?>/images/insta-14.png" alt="" />
            </div>
          </div>
        </div>
        <div class="gradient-overlay"></div>
      </div>