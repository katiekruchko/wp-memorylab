(function ($) {
    'use strict';

    // ========================
    // SKELETON HELPERS
    // ========================

    /**
     * Генерирует HTML скелетонов карточек
     * @param {number} count - количество скелетонов
     * @returns {string} HTML-строка
     */
    function generateSkeletons(count) {
        count = count || 8;
        var html = '';
        for (var i = 0; i < count; i++) {
            html +=
                '<div class="skeleton-card">' +
                '<div class="skeleton-image"></div>' +
                '<div class="skeleton-line title"></div>' +
                '<div class="skeleton-line medium"></div>' +
                '<div class="skeleton-line short"></div>' +
                '</div>';
        }
        return html;
    }

    /**
     * Полностью заменяет содержимое контейнера скелетонами
     */
    function showSkeletons($container, count) {
        $container.html(generateSkeletons(count || 8));
    }

    /**
     * Добавляет скелетоны в конец контейнера
     */
    function appendSkeletons($container, count) {
        $container.append(generateSkeletons(count || 4));
    }

    /**
     * Удаляет все скелетоны из контейнера
     */
    function removeSkeletons($container) {
        $container.find('.skeleton-card').remove();
    }

    /**
     * Проверяет, есть ли ещё посты по маркеру из ответа сервера
     * @param {string} response - HTML ответа
     * @returns {boolean}
     */
    function hasMorePosts(response) {
        if (response.indexOf('no-more-posts') !== -1) return false;
        if (response.indexOf('no-results-message') !== -1) return false;

        var match = response.match(/data-has-more="([01])"/);
        if (match) {
            return match[1] === '1';
        }

        // Безопасный дефолт — не показывать кнопку
        return false;
    }

    /**
     * Вставляет карточки из ответа сервера в сетку,
     * предварительно удалив служебные элементы (ajax-meta, search-info и т.д.)
     */
    function appendCardsFromResponse($cardsGrid, response) {
        var $temp = $('<div>').html(response);

        // Убираем служебные элементы
        $temp.find('.ajax-meta, .search-info, .no-results-message, .no-more-posts').remove();

        // Вставляем все оставшиеся карточки (.card)
        var $newCards = $temp.find('.card');
        if ($newCards.length > 0) {
            $cardsGrid.append($newCards);
        }

        return $newCards.length;
    }

    /**
     * Считает количество карточек (.card) в HTML-ответе
     */
    function countCardsInResponse(response) {
        var $temp = $('<div>').html(response);
        return $temp.find('.card').length;
    }

    /**
     * Инициализация каталога на странице
     */
    function initCatalog(containerSelector, hasSearch, isHomePage = false) {
        console.log('Initializing catalog:', {
            container: containerSelector,
            hasSearch: hasSearch,
            isHomePage: isHomePage
        });

        var $container = $(containerSelector);
        var $searchInput = hasSearch ? $container.find('#product-search') : null;
        var $cardsGrid = $container.find('.cards-grid');
        var $loadingSpinner = $container.find('.loading-spinner');
        var $loadMoreBtn = $container.find('.btn-more');
        var $filters = $container.find('.filters');

        if ($cardsGrid.length === 0) {
            console.error('Cards grid not found in:', containerSelector);
            return;
        }

        var currentFilter = 'all';
        var currentSearch = '';
        var currentPage = 1;
        var searchTimeout;
        var DEBOUNCE_DELAY = 300;
        var MIN_SEARCH_LENGTH = 3; // Минимум 3 символа для поиска
        var PAGE_SIZE = 8;          // Должно совпадать с posts_per_page в PHP

        // ========================
        // ГЛАВНАЯ СТРАНИЦА (без поиска)
        // ========================

        if (isHomePage) {
            console.log('Setting up home page catalog (no search)');

            // Обработчик фильтров
            $filters.on('click', '.filter-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var $button = $(this);
                var newFilter = $button.data('filter');

                console.log('Home filter clicked:', newFilter);

                if (newFilter === currentFilter) {
                    return;
                }

                currentFilter = newFilter;
                currentPage = 1;

                $filters.find('.filter-btn').removeClass('active');
                $button.addClass('active');

                loadHomeFilteredPosts();
            });

            // Кнопка "Показать ещё" на главной
            $loadMoreBtn.on('click', function (e) {
                e.preventDefault();

                var $button = $(this);
                var page = parseInt($button.data('page')) || 1;

                console.log('Home load more clicked. Page:', page);

                $button.hide();

                // Скелетоны внизу
                appendSkeletons($cardsGrid, 4);

                $.ajax({
                    url: ajax_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'load_more_posts',
                        page: page,
                        post_type: $button.data('post-type') || 'staff',
                        filter: currentFilter,
                        nonce: ajax_params.nonce
                    },
                    success: function (response) {
                        removeSkeletons($cardsGrid);

                        var $temp = $('<div>').html(response);
                        var $newCards = $temp.find('.card');
                        var hasMore = hasMorePosts(response);

                        if ($newCards.length > 0) {
                            $cardsGrid.append($newCards);
                        }

                        // Обновляем номер страницы
                        $button.data('page', page + 1);

                        // Показываем кнопку, ТОЛЬКО если сервер сказал "есть ещё"
                        // И реально пришли карточки
                        if (hasMore && $newCards.length > 0) {
                            $button.show();
                        } else {
                            $button.hide();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Home load more error:', error);
                        removeSkeletons($cardsGrid);
                        $button.show();
                    }
                });
            });

            // Загрузка отфильтрованных постов на главной
            function loadHomeFilteredPosts() {
                console.log('Loading home filtered posts:', currentFilter);

                $loadMoreBtn.hide();
                showSkeletons($cardsGrid, 8);

                $.ajax({
                    url: ajax_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'load_more_posts',
                        page: 0,
                        filter: currentFilter,
                        post_type: $loadMoreBtn.data('post-type') || 'staff',
                        nonce: ajax_params.nonce
                    },
                    success: function (response) {
                        // Считаем карточки ДО удаления маркера
                        var cardCount = countCardsInResponse(response);

                        $cardsGrid.html(response);
                        $cardsGrid.find('.ajax-meta').remove();

                        // Первая страница загружена — следующая для "Показать ещё" = 2
                        currentPage = 2;
                        $loadMoreBtn.data('page', 2);

                        console.log('Home filter result: cards =', cardCount, ', has-more =', hasMorePosts(response));

                        // Показываем кнопку ТОЛЬКО если:
                        // 1. Сервер сказал has-more=1
                        // 2. Вернулась полная партия (8 карточек) — значит, точно есть продолжение
                        if (hasMorePosts(response) && cardCount >= PAGE_SIZE) {
                            $loadMoreBtn.show();
                        } else {
                            $loadMoreBtn.hide();
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Home filter error:', error);
                        $cardsGrid.html(
                            '<p class="no-results-message">Ошибка загрузки.</p>'
                        );
                        $loadMoreBtn.hide();
                    }
                });
            }

            console.log('Home page catalog initialized successfully');
            return;
        }

        // ========================
        // СТРАНИЦА КАТАЛОГА (с поиском)
        // ========================

        // Обработчик фильтров
        $filters.on('click', '.filter-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $button = $(this);
            var newFilter = $button.data('filter');

            console.log('Catalog filter clicked:', newFilter, 'Current filter:', currentFilter, 'Current search:', currentSearch);

            if (newFilter === currentFilter) {
                return;
            }

            currentFilter = newFilter;
            currentPage = 1;

            $filters.find('.filter-btn').removeClass('active');
            $button.addClass('active');

            performCombinedSearch();
        });

        // Поиск
        if (hasSearch && $searchInput) {
            console.log('Search initialized for catalog page');

            $searchInput.on('input', function () {
                clearTimeout(searchTimeout);

                var searchTerm = $(this).val().trim();

                // Управление кнопкой очистки
                if (searchTerm.length > 0) {
                    $searchInput.next('.clear-search-icon').show();
                } else {
                    $searchInput.next('.clear-search-icon').hide();
                }

                // Если поле очищено — показываем всё
                if (searchTerm.length === 0) {
                    currentSearch = '';
                    currentPage = 1;

                    if (currentFilter === 'all') {
                        loadInitialPosts();
                    } else {
                        performCombinedSearch();
                    }
                    return;
                }

                // Меньше 3 символов — показываем подсказку, не ищем
                if (searchTerm.length < MIN_SEARCH_LENGTH) {
                    currentSearch = '';
                    currentPage = 1;

                    $loadMoreBtn.hide();
                    $cardsGrid.html(
                        '<p class="search-hint">Введите минимум ' +
                        MIN_SEARCH_LENGTH +
                        ' символа для поиска</p>'
                    );
                    return;
                }

                // 3+ символа — ищем
                currentSearch = searchTerm;
                currentPage = 1;

                searchTimeout = setTimeout(function () {
                    performCombinedSearch();
                }, DEBOUNCE_DELAY);
            });

            // Кнопка очистки поиска
            if (!$searchInput.next('.clear-search-icon').length) {
                $searchInput.after(
                    '<span class="clear-search-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999; display: none;">✕</span>'
                );
            }

            $searchInput.next('.clear-search-icon').on('click', function () {
                $searchInput.val('');
                $(this).hide();
                currentSearch = '';
                currentPage = 1;

                if (currentFilter === 'all') {
                    loadInitialPosts();
                } else {
                    performCombinedSearch();
                }
            });
        }

        // Кнопка "Показать ещё" в каталоге
        $loadMoreBtn.on('click', function (e) {
            e.preventDefault();

            var $button = $(this);
            currentPage++;

            $button.hide();

            // Скелетоны внизу
            appendSkeletons($cardsGrid, 4);

            $.ajax({
                url: ajax_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'combined_search',
                    page: currentPage,
                    post_type: $button.data('post-type') || 'staff',
                    filter: currentFilter,
                    search: currentSearch,
                    nonce: ajax_params.nonce
                },
                success: function (response) {
                    removeSkeletons($cardsGrid);

                    // Убираем старые служебные сообщения
                    $cardsGrid.find('.no-results-message').remove();
                    $cardsGrid.find('.search-info').remove();

                    // Вставляем карточки
                    appendCardsFromResponse($cardsGrid, response);

                    // Показ/скрытие кнопки по маркеру
                    if (hasMorePosts(response)) {
                        $button.show();
                    } else {
                        $button.hide();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Catalog load more error:', error);
                    removeSkeletons($cardsGrid);
                    currentPage--;
                    $button.show();
                }
            });
        });

        // ========================
        // ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ КАТАЛОГА
        // ========================

        function performCombinedSearch() {
            console.log('Performing combined search:', {
                search: currentSearch,
                filter: currentFilter,
                page: currentPage
            });

            $loadMoreBtn.hide();
            showSkeletons($cardsGrid, 8);

            $.ajax({
                url: ajax_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'combined_search',
                    page: 1,
                    post_type: $loadMoreBtn.data('post-type') || 'staff',
                    filter: currentFilter,
                    search: currentSearch,
                    nonce: ajax_params.nonce
                },
                success: function (response) {
                    $cardsGrid.html(response);
                    $cardsGrid.find('.ajax-meta').remove();

                    $loadMoreBtn.data('page', 1);

                    if (hasMorePosts(response)) {
                        $loadMoreBtn.show();
                    } else {
                        $loadMoreBtn.hide();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Catalog search error:', error);
                    $cardsGrid.html(
                        '<p class="no-results-message">Ошибка загрузки. Попробуйте позже.</p>'
                    );
                    $loadMoreBtn.hide();
                }
            });
        }

        function loadInitialPosts() {
            console.log('Loading initial catalog posts');

            showSkeletons($cardsGrid, 8);

            $.ajax({
                url: ajax_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_more_posts',
                    page: 0,
                    post_type: $loadMoreBtn.data('post-type') || 'staff',
                    filter: 'all',
                    nonce: ajax_params.nonce
                },
                success: function (response) {
                    $cardsGrid.html(response);
                    $cardsGrid.find('.ajax-meta').remove();

                    currentPage = 1;
                    $loadMoreBtn.data('page', currentPage);

                    if (hasMorePosts(response)) {
                        $loadMoreBtn.show();
                    } else {
                        $loadMoreBtn.hide();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Catalog initial load error:', error);
                    $cardsGrid.html(
                        '<p class="no-results-message">Ошибка загрузки.</p>'
                    );
                    $loadMoreBtn.hide();
                }
            });
        }

        console.log('Catalog page initialized successfully');
    }

    /**
     * Инициализация AI калькулятора
     */
    function initAICalculator() {
        console.log('Initializing AI Calculator...');

        // 1. Инициализация выпадающих списков
        const selectTriggers = document.querySelectorAll('.ai-custom-select');

        selectTriggers.forEach(trigger => {
            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                const dropdownId = this.id.replace('-trigger', '-dropdown');
                const dropdown = document.getElementById(dropdownId);

                document.querySelectorAll('.ai-dropdown').forEach(d => {
                    if (d.id !== dropdownId) {
                        d.classList.remove('active');
                    }
                });

                dropdown.classList.toggle('active');

                dropdown.querySelectorAll('.ai-dropdown-item').forEach(item => {
                    item.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const value = this.getAttribute('data-value');
                        const text = this.textContent;

                        trigger.querySelector('span').textContent = text;

                        const input = trigger.parentElement.querySelector('input[type="hidden"]');
                        input.value = value;

                        trigger.parentElement.classList.remove('error');

                        dropdown.classList.remove('active');
                    });
                });
            });
        });

        // Закрытие dropdown при клике вне
        document.addEventListener('click', function () {
            document.querySelectorAll('.ai-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        });

        // 2. Обновление значения слайдера
        const peopleSlider = document.getElementById('people-slider');
        const sliderValue = document.querySelector('.ai-slider-value');

        if (peopleSlider && sliderValue) {
            peopleSlider.addEventListener('input', function () {
                sliderValue.textContent = this.value;
            });
        }

        // 3. Обработка отправки формы
        const aiForm = document.getElementById('ai-form');
        const aiSubmitBtn = document.getElementById('ai-submit-btn');
        const resultsContainer = document.getElementById('ai-results-grid');

        if (aiForm && aiSubmitBtn && resultsContainer) {
            console.log('AI Calculator elements found');

            $(aiSubmitBtn).on('click', function (e) {
                e.preventDefault();
                console.log('AI Calculator form submitted');

                // Проверка обязательных полей
                let hasErrors = false;
                $('.ai-select-wrapper').each(function () {
                    const input = $(this).find('input[type="hidden"]');
                    if (!input.val()) {
                        $(this).addClass('error');
                        hasErrors = true;
                    } else {
                        $(this).removeClass('error');
                    }
                });

                if (hasErrors) {
                    console.log('Form has errors - not all fields are selected');
                    return;
                }

                const $aiSubmitBtn = $(aiSubmitBtn);
                const originalText = $aiSubmitBtn.text();
                $aiSubmitBtn.text('Генерируем...');
                $aiSubmitBtn.prop('disabled', true);

                const $resultsContainer = $(resultsContainer);

                // СКЕЛЕТОНЫ для AI
                $resultsContainer.html(generateSkeletons(4));

                const data = {
                    action: 'ai_calculator_search',
                    nonce: aiCalculatorData.nonce,
                    event_type: $('input[name="event_type"]').val(),
                    duration: $('input[name="duration"]').val(),
                    format: $('input[name="format"]').val(),
                    people_count: $('input[name="people_count"]').val()
                };

                console.log('Sending AI request:', data);

                $.ajax({
                    url: aiCalculatorData.ajax_url,
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        console.log('AJAX success:', response);
                        if (response.success) {
                            $resultsContainer.html(response.data.html);
                        } else {
                            $resultsContainer.html(
                                '<div class="ai-error">Ошибка: ' +
                                (response.data || 'Неизвестная ошибка') +
                                '</div>'
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX error:', error, xhr.responseText);
                        $resultsContainer.html(
                            '<div class="ai-error">Ошибка подключения к серверу.</div>'
                        );
                    },
                    complete: function () {
                        $aiSubmitBtn.text(originalText);
                        $aiSubmitBtn.prop('disabled', false);
                    }
                });
            });

            console.log('AI Calculator form handler attached successfully');
        } else {
            console.log('AI Calculator elements not found:', {
                form: !!aiForm,
                button: !!aiSubmitBtn,
                container: !!resultsContainer
            });
        }
    }

    // ========================
    // ИНИЦИАЛИЗАЦИЯ ПРИ ЗАГРУЗКЕ
    // ========================

    $(document).ready(function () {
        console.log('Document ready, initializing catalogs...');

        var isHomePage = $('body').hasClass('home');
        var hasCatalogSection = $('section.catalog-main').length > 0;

        console.log('Page info:', {
            isHomePage: isHomePage,
            hasCatalogSection: hasCatalogSection
        });

        // Главная страница
        if (isHomePage && hasCatalogSection) {
            console.log('Initializing home page catalog');
            var $homeCatalog = $('#home-catalog');
            if ($homeCatalog.length) {
                initCatalog('#home-catalog', false, true);
            } else {
                initCatalog('section.catalog-main:first', false, true);
            }
        }

        // Страница каталога
        if (!isHomePage) {
            var isCatalogPage = $('body').hasClass('page-template-catalog') ||
                window.location.pathname.includes('/catalog') ||
                ($('section.catalog-main').length && $('#product-search').length);

            if (isCatalogPage) {
                console.log('Initializing catalog page');
                initCatalog('section.catalog-main', true, false);
            }
        }

        // AI калькулятор
        console.log('Initializing AI Calculator...');
        initAICalculator();

        console.log('All initializations complete');
    });

})(jQuery);