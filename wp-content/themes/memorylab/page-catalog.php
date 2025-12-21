<?php
/**
 * Template Name: Catalog
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package memorylab
 */

get_header();
?>
<main id="primary" class="page secondary-page site-main">
    
    <section class="catalog-main container">
      <div class="search-product-header">
        <div class="search-product-h"><h1 class="h1-black">Все интерактивы</h1></div>
        <div class="search-product-block">
          <div class="search-product-wrap">
            <div class="search-input-icon"><img src="/images/search-icon.svg" alt="поиск интерактива"></div>
            <input type="text" class="search-input" placeholder="Название интерактива, категория..." autofocus />
        </div>
        </div>
      </div>
      <div class="catalog-wrap">
        <div class="catalog-filter_wrap">
          <div class="catalog-filter">
            <div class="filters">
              <button class="filter-btn active">Все интерактивы</button>
              <button class="filter-btn">Хиты сезона 🔥</button>
              <button class="filter-btn">Фотобудки</button>
              <button class="filter-btn">AI Интерактивы 🤖</button>
              <button class="filter-btn">Новинки ⭐</button>
              <button class="filter-btn">Фото</button>
              <button class="filter-btn">Видео</button>
              <button class="filter-btn">Музыкальные</button>
              <button class="filter-btn">Тимбилдинг</button>
              <button class="filter-btn">Корпоратив</button>
              <button class="filter-btn">Конференция</button>
            </div>
          </div>
        </div>
        <!-- Карточки -->
        <div class="cards-grid">
          <!-- Карточка 1 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-1.png" alt="Фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Фотобудка</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>

          <!-- Карточка 2 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-2.png" alt="Видеоспинер" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Видеоспинер</h3>
              <div class="card-desc">
                Создаёт головокружительные видеоклипы с эффектом замедления,
                готовые для размещения в социальных...
              </div>
            </div>
          </div>

          <!-- Карточка 3 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-3.png" alt="AI-фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">AI-фотобудка</h3>
              <div class="card-desc">
                Многоновая печать фотографий обработанных нейросетью в режиме
                реального времени по заданным
              </div>
            </div>
          </div>

          <!-- Карточка 4 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-4.png" alt="Скетч-бот" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Скетч-бот</h3>
              <div class="card-desc">
                Рисует портрет в режиме реального времени по фотографии
              </div>
            </div>
          </div>

          <!-- Карточка 5 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-5.png" alt="Селфи-зеркало" class="card-image" />
              <div class="badge-wrap">
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Селфи-зеркало</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>
          <!--  -->
          <!-- Карточка 6 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-1.png" alt="Фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Фотобудка</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>

          <!-- Карточка 7 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-2.png" alt="Видеоспинер" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Видеоспинер</h3>
              <div class="card-desc">
                Создаёт головокружительные видеоклипы с эффектом замедления,
                готовые для размещения в социальных...
              </div>
            </div>
          </div>

          <!-- Карточка 8 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-3.png" alt="AI-фотобудка" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">AI-фотобудка</h3>
              <div class="card-desc">
                Многоновая печать фотографий обработанных нейросетью в режиме
                реального времени по заданным
              </div>
            </div>
          </div>

          <!-- Карточка 9 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-4.png" alt="Скетч-бот" class="card-image" />
              <div class="badge-wrap">
                <span class="badge new">Новинка</span>
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Скетч-бот</h3>
              <div class="card-desc">
                Рисует портрет в режиме реального времени по фотографии
              </div>
            </div>
          </div>

          <!-- Карточка 10 -->
          <div class="card">
            <div class="card-image-wrapper">
              <img src="/images/img-5.png" alt="Селфи-зеркало" class="card-image" />
              <div class="badge-wrap">
                <span class="badge hit">Хит 🔥</span>
              </div>
            </div>
            <div class="card-content">
              <h3 class="card-title">Селфи-зеркало</h3>
              <div class="card-desc">
                Фотографирует и моментально печатает снимки с вашим логотипом
              </div>
            </div>
          </div>
        </div>
        <div class="btn-catalog">
          <button class="btn-grey btn-more">Показать ещё</button>
        </div>
      </div>
    </section>
  </main>

<?php
get_footer();
