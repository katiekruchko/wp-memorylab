(function($) {
    'use strict';

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
        var MIN_SEARCH_LENGTH = 2;

        // ========================
        // ФУНКЦИИ ДЛЯ ГЛАВНОЙ СТРАНИЦЫ (без поиска)
        // ========================

        if (isHomePage) {
            console.log('Setting up home page catalog (no search)');

            // Обработчик фильтров для главной
            $filters.on('click', '.filter-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $button = $(this);
                var newFilter = $button.data('filter');

                console.log('Home filter clicked:', newFilter);

                // Если фильтр не изменился
                if (newFilter === currentFilter) {
                    return;
                }

                // Обновляем активный фильтр
                currentFilter = newFilter;
                currentPage = 1;

                // Обновляем UI
                $filters.find('.filter-btn').removeClass('active');
                $button.addClass('active');

                // Загружаем отфильтрованные посты
                loadHomeFilteredPosts();
            });

            // Обработчик кнопки "Показать ещё" для главной
            $loadMoreBtn.on('click', function(e) {
                e.preventDefault();

                var $button = $(this);
                var page = parseInt($button.data('page')) || 1;
                var maxPages = parseInt($button.data('max-pages')) || 1;

                console.log('Home load more clicked. Page:', page, 'Max:', maxPages);

                if (page >= maxPages) {
                    $button.hide();
                    return;
                }

                $button.hide();
                if ($loadingSpinner.length) {
                    $loadingSpinner.show();
                }

                var postData = {
                    action: 'load_more_posts',
                    page: page,
                    post_type: $button.data('post-type') || 'staff',
                    filter: currentFilter,
                    nonce: ajax_params.nonce
                };

                $.ajax({
                    url: ajax_params.ajax_url,
                    type: 'POST',
                    data: postData,
                    success: function(response) {
                        if ($loadingSpinner.length) {
                            $loadingSpinner.hide();
                        }

                        // Добавляем новые карточки
                        $cardsGrid.append(response);

                        var newPage = page + 1;
                        $button.data('page', newPage);

                        if (newPage >= maxPages ||
                            response.includes('no-more-posts') ||
                            response.includes('no-results-message')) {
                            $button.hide();
                        } else {
                            $button.show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Home load more error:', error);
                        if ($loadingSpinner.length) {
                            $loadingSpinner.hide();
                        }
                        $button.show();
                    }
                });
            });

            // Функция загрузки отфильтрованных постов для главной
            function loadHomeFilteredPosts() {
                console.log('Loading home filtered posts:', currentFilter);

                if ($loadingSpinner.length) {
                    $loadingSpinner.show();
                }
                $loadMoreBtn.hide();

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
                    success: function(response) {
                        if ($loadingSpinner.length) {
                            $loadingSpinner.hide();
                        }

                        // Заменяем все карточки
                        $cardsGrid.html(response);

                        // Сбрасываем счетчик страниц
                        currentPage = 1;
                        $loadMoreBtn.data('page', currentPage);

                        // Показываем кнопку если есть что грузить
                        if (!response.includes('no-more-posts') &&
                            !response.includes('no-results-message')) {
                            // Проверяем количество загруженных карточек
                            var cardCount = $cardsGrid.find('.staff-card').length;
                            if (cardCount >= 6) {
                                $loadMoreBtn.show();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Home filter error:', error);
                        if ($loadingSpinner.length) {
                            $loadingSpinner.hide();
                        }
                        $loadMoreBtn.show();
                    }
                });
            }

            console.log('Home page catalog initialized successfully');
            return; // Завершаем инициализацию для главной
        }

        // ========================
        // ФУНКЦИИ ДЛЯ СТРАНИЦЫ КАТАЛОГА (с поиском)
        // ========================

        // Обработчик фильтров
        $filters.on('click', '.filter-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $button = $(this);
            var newFilter = $button.data('filter');

            console.log('Catalog filter clicked:', newFilter, 'Current filter:', currentFilter, 'Current search:', currentSearch);

            // Если фильтр не изменился
            if (newFilter === currentFilter) {
                return;
            }

            // Обновляем активный фильтр
            currentFilter = newFilter;
            currentPage = 1;

            // Обновляем UI
            $filters.find('.filter-btn').removeClass('active');
            $button.addClass('active');

            // Выполняем комбинированный поиск
            performCombinedSearch();
        });

        // Обработчик поиска
        if (hasSearch && $searchInput) {
            console.log('Search initialized for catalog page');

            $searchInput.on('input', function() {
                clearTimeout(searchTimeout);

                var searchTerm = $(this).val().trim();

                // Управление кнопкой очистки
                if (searchTerm.length > 0) {
                    $searchInput.next('.clear-search-icon').show();
                } else {
                    $searchInput.next('.clear-search-icon').hide();
                }

                // Если поле очищено
                if (searchTerm.length === 0) {
                    currentSearch = '';
                    currentPage = 1;

                    // Если нет активного фильтра - показываем все
                    if (currentFilter === 'all') {
                        loadInitialPosts();
                    } else {
                        // Иначе применяем только фильтр
                        performCombinedSearch();
                    }
                    return;
                }

                // Обновляем переменные
                currentSearch = searchTerm;
                currentPage = 1;

                // Debounce для поиска
                searchTimeout = setTimeout(function() {
                    performCombinedSearch();
                }, DEBOUNCE_DELAY);
            });

            // Добавляем кнопку очистки поиска
            if (!$searchInput.next('.clear-search-icon').length) {
                $searchInput.after(
                    '<span class="clear-search-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999; display: none;">✕</span>'
                );
            }

            // Обработчик кнопки очистки
            $searchInput.next('.clear-search-icon').on('click', function() {
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

        // Обработчик кнопки "Показать ещё" для каталога
        $loadMoreBtn.on('click', function(e) {
            e.preventDefault();

            var $button = $(this);
            currentPage++;

            $button.hide();
            if ($loadingSpinner.length) {
                $loadingSpinner.show();
            }

            var postData = {
                action: 'combined_search',
                page: currentPage,
                post_type: $button.data('post-type') || 'staff',
                filter: currentFilter,
                search: currentSearch,
                nonce: ajax_params.nonce
            };

            $.ajax({
                url: ajax_params.ajax_url,
                type: 'POST',
                data: postData,
                success: function(response) {
                    if ($loadingSpinner.length) {
                        $loadingSpinner.hide();
                    }

                    // Удаляем предыдущее сообщение "нет результатов"
                    $cardsGrid.find('.no-results-message').remove();

                    // Удаляем предыдущую информацию о поиске
                    $cardsGrid.find('.search-info').remove();

                    // Добавляем новые карточки
                    $cardsGrid.append(response);

                    // Проверяем, есть ли еще посты для загрузки
                    var $searchInfo = $cardsGrid.find('.search-info');
                    var totalPosts = $searchInfo.length ? parseInt($searchInfo.data('total')) : 0;
                    var loadedPosts = $cardsGrid.find('.staff-card').length;

                    if (totalPosts > 0 && loadedPosts < totalPosts) {
                        $button.show();
                    } else {
                        $button.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Catalog load more error:', error);
                    if ($loadingSpinner.length) {
                        $loadingSpinner.hide();
                    }
                    currentPage--;
                    $button.show();
                }
            });
        });

        // ========================
        // ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ДЛЯ КАТАЛОГА
        // ========================

        function performCombinedSearch() {
            console.log('Performing combined search:', {
                search: currentSearch,
                filter: currentFilter,
                page: currentPage
            });

            if ($loadingSpinner.length) {
                $loadingSpinner.show();
            }
            $loadMoreBtn.hide();

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
                success: function(response) {
                    if ($loadingSpinner.length) {
                        $loadingSpinner.hide();
                    }

                    // Полностью заменяем содержимое
                    $cardsGrid.html(response);

                    // Сбрасываем счетчик страниц
                    $loadMoreBtn.data('page', 1);

                    // Показываем/скрываем кнопку "Показать ещё"
                    var $searchInfo = $cardsGrid.find('.search-info');
                    if ($searchInfo.length) {
                        var totalPosts = parseInt($searchInfo.data('total'));
                        var loadedPosts = $cardsGrid.find('.staff-card').length;

                        if (totalPosts > loadedPosts) {
                            $loadMoreBtn.show();
                        }
                    } else {
                        // Если нет результатов или их меньше 6
                        var cardCount = $cardsGrid.find('.staff-card').length;
                        if (cardCount >= 6 && !$cardsGrid.find('.no-results-message').length) {
                            $loadMoreBtn.show();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Catalog search error:', error);
                    if ($loadingSpinner.length) {
                        $loadingSpinner.hide();
                    }
                    $loadMoreBtn.show();
                }
            });
        }

        function loadInitialPosts() {
            console.log('Loading initial catalog posts');

            if ($loadingSpinner.length) {
                $loadingSpinner.show();
            }

            // Используем стандартную загрузку через load_more_posts
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
                success: function(response) {
                    if ($loadingSpinner.length) {
                        $loadingSpinner.hide();
                    }

                    $cardsGrid.html(response);
                    currentPage = 1;
                    $loadMoreBtn.data('page', currentPage);

                    // Показываем кнопку если есть что грузить дальше
                    if (!$cardsGrid.find('.no-more-posts').length) {
                        $loadMoreBtn.show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Catalog initial load error:', error);
                    if ($loadingSpinner.length) {
                        $loadingSpinner.hide();
                    }
                    $loadMoreBtn.show();
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
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdownId = this.id.replace('-trigger', '-dropdown');
                const dropdown = document.getElementById(dropdownId);

                // Закрываем все открытые dropdown
                document.querySelectorAll('.ai-dropdown').forEach(d => {
                    if (d.id !== dropdownId) {
                        d.classList.remove('active');
                    }
                });

                // Переключаем текущий dropdown
                dropdown.classList.toggle('active');

                // Обработка выбора элемента
                dropdown.querySelectorAll('.ai-dropdown-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.getAttribute('data-value');
                        const text = this.textContent;

                        // Обновляем текст триггера
                        trigger.querySelector('span').textContent = text;

                        // Обновляем скрытое поле
                        const input = trigger.parentElement.querySelector('input[type="hidden"]');
                        input.value = value;

                        // Убираем ошибку, если была
                        trigger.parentElement.classList.remove('error');

                        // Закрываем dropdown
                        dropdown.classList.remove('active');
                    });
                });
            });
        });

        // Закрытие dropdown при клике вне
        document.addEventListener('click', function() {
            document.querySelectorAll('.ai-dropdown').forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        });

        // 2. Обновление значения слайдера
        const peopleSlider = document.getElementById('people-slider');
        const sliderValue = document.querySelector('.ai-slider-value');

        if (peopleSlider && sliderValue) {
            peopleSlider.addEventListener('input', function() {
                sliderValue.textContent = this.value;
            });
        }

        // 3. Обработка отправки формы
        const aiForm = document.getElementById('ai-form');
        const aiSubmitBtn = document.getElementById('ai-submit-btn');
        const resultsContainer = document.getElementById('ai-results-grid');

        // 3. Обработка отправки формы (ЗАМЕНА НА jQuery.ajax)
        if (aiForm && aiSubmitBtn && resultsContainer) {
            console.log('AI Calculator elements found');

            $(aiSubmitBtn).on('click', function(e) {
                e.preventDefault();
                console.log('AI Calculator form submitted');

                // Проверка обязательных полей
                let hasErrors = false;
                $('.ai-select-wrapper').each(function() {
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

                // Показ загрузки
                const $aiSubmitBtn = $(aiSubmitBtn); // Конвертируем в jQuery объект
                const originalText = $aiSubmitBtn.text();
                $aiSubmitBtn.text('Ищем...');
                $aiSubmitBtn.prop('disabled', true);

                // Используем jQuery для resultsContainer
                const $resultsContainer = $(resultsContainer);
                $resultsContainer.html('<div class="ai-loading">Ищем подходящие варианты...</div>');

                // Данные формы
                const data = {
                    action: 'ai_calculator_search',
                    nonce: aiCalculatorData.nonce,
                    event_type: $('input[name="event_type"]').val(),
                    duration: $('input[name="duration"]').val(),
                    format: $('input[name="format"]').val(),
                    people_count: $('input[name="people_count"]').val()
                };

                console.log('Sending AI request:', data);

                // AJAX-запрос через jQuery (работает с WordPress)
                $.ajax({
                    url: aiCalculatorData.ajax_url,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        console.log('AJAX success:', response);
                        if (response.success) {
                            $resultsContainer.html(response.data.html);
                        } else {
                            $resultsContainer.html('<div class="ai-error">Ошибка: ' + (response.data || 'Неизвестная ошибка') + '</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error, xhr.responseText);
                        $resultsContainer.html('<div class="ai-error">Ошибка подключения к серверу.</div>');
                    },
                    complete: function() {
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

    $(document).ready(function() {
        console.log('Document ready, initializing catalogs...');

        // Проверяем на главной странице
        var isHomePage = $('body').hasClass('home');
        var hasCatalogSection = $('section.catalog-main').length > 0;

        console.log('Page info:', {
            isHomePage: isHomePage,
            hasCatalogSection: hasCatalogSection
        });

        // Инициализация главной страницы
        if (isHomePage && hasCatalogSection) {
            console.log('Initializing home page catalog');
            // Находим каталог на главной
            var $homeCatalog = $('#home-catalog');
            if ($homeCatalog.length) {
                initCatalog('#home-catalog', false, true);
            } else {
                // Или просто первый каталог на странице
                initCatalog('section.catalog-main:first', false, true);
            }
        }

        // Инициализация страницы каталога
        if (!isHomePage) {
            // Проверяем по разным признакам
            var isCatalogPage = $('body').hasClass('page-template-catalog') ||
                               window.location.pathname.includes('/catalog') ||
                               ($('section.catalog-main').length && $('#product-search').length);

            if (isCatalogPage) {
                console.log('Initializing catalog page');
                initCatalog('section.catalog-main', true, false);
            }
        }

        // Инициализация AI калькулятора
        console.log('Initializing AI Calculator...');
        initAICalculator();

        console.log('All initializations complete');
    });

})(jQuery);
