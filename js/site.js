
    /*
    mobile menu
    */
    document.addEventListener('DOMContentLoaded', function() {
      const burgerBtn = document.getElementById('burger-menu');
      const sidebar = document.querySelector('.mm-sidebar');
      const closeBtn = document.querySelector('.mm-close-btn');
      const overlay = document.querySelector('.mm-overlay');
      const body = document.body;
    
      // Универсальный обработчик кликов по документу
      document.addEventListener('click', function(e) {
        // Проверяем, был ли клик внутри кнопки меню
        if (burgerBtn && burgerBtn.contains(e.target)) {
          e.preventDefault(); // предотвращаем возможные действия по умолчанию
          sidebar.classList.add('open');
          overlay.classList.add('active');
          body.classList.add('no-scroll');
          return;
        }
    
        // Закрытие меню по клику на оверлей
        if (overlay && overlay.classList.contains('active') && e.target === overlay) {
          sidebar.classList.remove('open');
          overlay.classList.remove('active');
          body.classList.remove('no-scroll');
          return;
        }
    
        // Закрытие меню по клику на × (внутри кнопки)
        if (closeBtn && closeBtn.contains(e.target)) {
          sidebar.classList.remove('open');
          overlay.classList.remove('active');
          body.classList.remove('no-scroll');
          return;
        }
    
        // Закрытие меню при клике вне его (опционально)
        if (sidebar && sidebar.classList.contains('open')) {
          if (!sidebar.contains(e.target) && !burgerBtn.contains(e.target)) {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
            body.classList.remove('no-scroll');
          }
        }
      });
    });
        /*
        end mobile menu
        */



/**/
/*
Заполнение формы
/**/
// Функция для открытия/закрытия выпадающих списков
function toggleDropdown(triggerId, dropdownId) {
  const trigger = document.getElementById(triggerId);
  const dropdown = document.getElementById(dropdownId);

  // Закрываем все другие выпадающие списки И убираем класс 'open' у их триггеров
  document.querySelectorAll(".ai-dropdown").forEach((d) => {
    if (d !== dropdown) {
      d.style.display = "none";
      // Находим соответствующий триггер по ID (например, "event-type-dropdown" → "event-type-trigger")
      const otherTriggerId = d.id.replace("-dropdown", "-trigger");
      const otherTrigger = document.getElementById(otherTriggerId);
      if (otherTrigger) {
        otherTrigger.classList.remove("open");
      }
    }
  });

  // Переключаем текущий
  if (dropdown.style.display === "block") {
    dropdown.style.display = "none";
    trigger.classList.remove("open");
  } else {
    dropdown.style.display = "block";
    trigger.classList.add("open");
  }
}

// Закрытие при клике вне выпадающего списка
document.addEventListener("click", function (event) {
  if (!event.target.closest(".ai-select-wrapper")) {
    document.querySelectorAll(".ai-dropdown").forEach((d) => {
      d.style.display = "none";
    });
    document.querySelectorAll(".ai-custom-select").forEach((s) => {
      s.classList.remove("open");
    });
  }
});

function safeAddClickListener(triggerId, dropdownId) {
  const trigger = document.getElementById(triggerId);
  if (trigger) {
    trigger.addEventListener("click", function () {
      toggleDropdown(triggerId, dropdownId);
    });
  }
}

// Используем безопасную функцию
safeAddClickListener("event-type-trigger", "event-type-dropdown");
safeAddClickListener("duration-trigger", "duration-dropdown");
safeAddClickListener("format-trigger", "format-dropdown");

// Обработка выбора в выпадающем списке
document.querySelectorAll(".ai-dropdown-item").forEach((item) => {
  item.addEventListener("click", function () {
    const wrapper = this.closest(".ai-select-wrapper");
    const select = wrapper.querySelector(".ai-custom-select");
    const hiddenInput = wrapper.querySelector("input[type='hidden']");
    const errorMsg = wrapper.querySelector(".ai-error-message");
    const label = this.textContent.trim();
    const dropdown = this.closest(".ai-dropdown");

    // Определяем значение
    let value = this.dataset.value !== undefined ? this.dataset.value : label;

    // Снимаем галочку со всех пунктов в этом списке
    dropdown.querySelectorAll(".ai-dropdown-item").forEach(el => {
      el.classList.remove("selected");
    });

    // Ставим галочку на выбранный
    this.classList.add("selected");

    // Обновляем отображение триггера
    select.querySelector("span").textContent = label;

    // Сохраняем в скрытый инпут
    if (hiddenInput) {
      hiddenInput.value = value;
    }

    // Скрываем ошибку
    if (errorMsg) {
      errorMsg.style.display = "none";
      wrapper.classList.remove("ai-select-wrapper-error");
    }

    // Закрываем выпадающий список
    dropdown.style.display = "none";
    select.classList.remove("open");
  });
});






// Валидация при нажатии на кнопку
document.addEventListener("DOMContentLoaded", () => {
  const submitBtn = document.getElementById("ai-submit-btn");
  if (submitBtn) {
    submitBtn.addEventListener("click", function (e) {
      e.preventDefault();

      let isValid = true;




// Проверяем ТОЛЬКО выпадающие списки (игнорируем слайдер)
document.querySelectorAll(".ai-select-wrapper").forEach((wrapper) => {
  const hiddenInput = wrapper.querySelector("input[type='hidden']");
  const errorMsg = wrapper.querySelector(".ai-error-message");

  if (!hiddenInput || !hiddenInput.value.trim()) {
    if (errorMsg) {
      errorMsg.style.display = "block";
      wrapper.classList.add("ai-select-wrapper-error");
    }
    isValid = false;
  } else {
    if (errorMsg) {
      errorMsg.style.display = "none";
      wrapper.classList.remove("ai-select-wrapper-error");
    }
    wrapper.classList.remove("ai-select-wrapper-error");
  }
});


      if (isValid) {
        console.log("Все селекты заполнены. Форма готова к отправке.");
        // Здесь можно отправить данные, например:
        // const formData = new FormData(document.querySelector('.ai-form'));
        // fetch('/submit', { method: 'POST', body: formData });
        document.getElementById("show-ai-results").style.display = "block";

      } else {
        console.log("Заполните все выпадающие списки.");
      }
    });
  }

  // Инициализация слайдера (без валидации)
  const slider = document.getElementById("people-slider");
  const valueDisplay = document.querySelector(".ai-slider-value");

  if (slider && valueDisplay) {
    const ratio = 100 / (+slider.max - +slider.min);
    slider.addEventListener("input", function () {
      valueDisplay.textContent = this.value;
      const value = ratio * (this.value - this.min);
      this.parentElement.style.setProperty("--value", value);
      this.style.setProperty("--progress", `${value}%`);
    });
    slider.dispatchEvent(new Event("input"));
  }
});

/* 
-------------------
Прокрутка изображений в блоке Инстаграм
-------------------
*/
document.addEventListener('DOMContentLoaded', () => {
  // Вспомогательная функция: ждём загрузки всех изображений в контейнере
  function waitForImages(container) {
    const images = container.querySelectorAll('img');
    return Promise.all(
      Array.from(images).map(img =>
        (img.complete && img.naturalHeight > 0)
          ? Promise.resolve()
          : new Promise(r => { img.onload = img.onerror = r; })
      )
    );
  }

  // Инициализация одного ряда
  function initRow(row, direction) {
    // Сохраняем оригинальные элементы ДО клонирования
    const originalItems = Array.from(row.querySelectorAll('.instagram-item'));
    if (originalItems.length === 0) return;

    // Ждём, пока все картинки подгрузятся → только потом клонируем и измеряем
    waitForImages(row).then(() => {
      // Удваиваем контент: добавляем копию оригинала в конец
      const fragment = document.createDocumentFragment();
      originalItems.forEach(item => {
        fragment.appendChild(item.cloneNode(true));
      });
      row.appendChild(fragment);

      // Теперь можно точно измерить ширину одного комплекта
      const singleWidth = originalItems.reduce((total, item) => {
        return total + item.getBoundingClientRect().width;
      }, 0);

      // Инициализируем позицию в зависимости от направления
      let pos = direction === 'right' ? -singleWidth : 0;

      function animate() {
        if (direction === 'left') {
          pos -= 0.35; // движемся влево → уменьшаем translateX
          if (pos <= -singleWidth) pos = 0;
        } else if (direction === 'right') {
          pos += 0.35; // движемся вправо → увеличиваем translateX
          if (pos >= 0) pos = -singleWidth;
        }

        row.style.transform = `translateX(${pos}px)`;
        requestAnimationFrame(animate);
      }

      animate();
    });
  }

  // Запускаем анимацию для обоих рядов
  const row1 = document.querySelector('.row-insta-1');
  const row2 = document.querySelector('.row-insta-2');

  if (row1) initRow(row1, 'right'); // → вправо
  if (row2) initRow(row2, 'left');  // ← влево
});
/* 
-------------------
Конец - Прокрутка изображений в блоке Инстаграм
-------------------
*/

/*
------------------
Скрыть header при скролле вниз и показать при скролле наверх
------------------
*/
let lastScrollY = window.scrollY;
const header = document.querySelector('.site-header');

window.addEventListener('scroll', () => {
  // Отключаем обработчик во время анимации прокрутки (например, при smooth scroll)
  if (window.scrollY > lastScrollY && window.scrollY > 50) {
    // Прокрутка вниз — скрываем
    header.classList.add('hidden');
  } else {
    // Прокрутка вверх — показываем
    header.classList.remove('hidden');
  }

  lastScrollY = window.scrollY;
});

/*
------------------
Конец - Скрыть header при скролле вниз и показать при скролле наверх
------------------
*/

