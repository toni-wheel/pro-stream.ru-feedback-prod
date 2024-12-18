<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/font.css" />
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="css/style.css" />

    <link
      rel="stylesheet"
      href="./libraries/vanilla-calendar/build/vanilla-calendar.min.css"
    />

    <title>Document</title>
  </head>
  <body>
   
    <section class="feedback">
      <div class="container feedback__container">
        <h1 class="feedback__title"><?php if (isset($_GET['zal'])) {echo $_GET['zal'];} ?> зал </h1>
		 
        <form action="#!" class="feedback__form" id="booking-form">
          <div class="feedback__box">
            <div class="feedback__input">
              <input
                type="text"
                name="name"
                id="name"
                placeholder="Ваше имя *"
              />
            </div>
          </div>

          <div class="feedback__box mb-50">
            <div class="feedback__input">
              <input
                type="text"
                name="phone"
                id="phone"
                placeholder="Ваш телефон *"
              />
            </div>
          </div>
     
          <div class="feedback__box mb-50">
            <h3 class="feedback__label">Основная услуга</h3>

            <ul class="feedback__switcher">
              <li class="feedback__switcher-item">
                <label for="rent">
                  <input
                    type="radio"
                    name="main-service"
                    id="rent"
                    value="rent"
                    <?php if ($_GET['service'] == '1') {echo 'checked="checked"';} ?>
                  />
                  <div class="feedback__switcher-radio"></div>
                  <span class="feedback__switcher-text">Аренда</span>
                </label>
              </li>
              <li class="feedback__switcher-item">
                <label for="rent-shooting">
                  <input
                    type="radio"
                    name="main-service"
                    id="rent-shooting"
                    value="rent-shooting"
					<?php if ($_GET['service'] == '2') {echo 'checked="checked"';} ?>
                  />
                  <div class="feedback__switcher-radio"></div>
                  <span class="feedback__switcher-text">Аренда + съемка</span>
                </label>
              </li>
              <li class="feedback__switcher-item">
                <label for="rent-shooting-broadcast">
                  <input
                    type="radio"
                    name="main-service"
                    id="rent-shooting-broadcast"
                    value="rent-shooting-broadcast"
					<?php if ($_GET['service'] == '3') {echo 'checked="checked"';} ?>
                  />
                  <div class="feedback__switcher-radio"></div>
                  <span class="feedback__switcher-text"
                    >Аренда + съемка + трансляция</span
                  >
                </label>
              </li>
            </ul>
          </div>

          <div class="feedback__box mb-50">
            <h3 class="feedback__label">Дополнительные услуги</h3>
            <ul class="feedback__option">
              <li class="feedback__option-item">
                <label for="flipchart">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="flipchart"
                    value="flipchart"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text">Флипчарт</span>
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="make-up-table">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="make-up-table"
                    value="make-up-table"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text">Гримерный стол</span>
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="teleprompter">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="teleprompter"
                    value="teleprompter"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text">Телесуфлер</span>
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="parking-space">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="parking-space"
                    value="parking-space"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text">Парковка</span>
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="plasma-panel">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="plasma-panel"
                    value="plasma-panel"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text">Плазменная панель</span>
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="add-tablet">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="add-tablet"
                    value="add-tablet"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text"
                    >Дополнительный планшет</span
                  >
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="add-laptop">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="add-laptop"
                    value="add-laptop"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text"
                    >Дополнительный ноутбук</span
                  >
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="add-microphone">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="add-microphone"
                    value="add-microphone"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text"
                    >Дополнительный микрофон</span
                  >
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="add-camera">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="add-camera"
                    value="add-camera"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text"
                    >Дополнительная камера
                  </span>
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="add-videographer">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="add-videographer"
                    value="add-videographer"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text"
                    >Дополнительный видеооператор</span
                  >
                </label>
              </li>
              <li class="feedback__option-item">
                <label for="add-engineer">
                  <input
                    type="checkbox"
                    name="add-services"
                    id="add-engineer"
                    value="add-engineer"
                  />
                  <div class="feedback__option-mark"></div>
                  <span class="feedback__option-text"
                    >Дополнительный инженер</span
                  >
                </label>
              </li>
            </ul>
          </div>

          <div class="feedback__box mb-50">
            <h3 class="feedback__label">Выберите дату</h3>
            <div id="calendar"></div>
          </div>

          <div class="feedback__box mb-50">
            <h3 class="feedback__label">Выберите время начала</h3>
            <div class="feedback__time" id="time-container"></div>
          </div>

          <div class="feedback__box mb-50">
            <h3 class="feedback__label">
              Время аренды <span id="end-time-text"></span>
              <span id="free-time-text"></span>
            </h3>
            <div class="feedback__counter">
              <button id="counter-minus">–</button>
              <input
                type="text"
                name="counter-num"
                id="counter-num"
                value="01:00"
                data-increment="2"
                readonly
              />
              <button id="counter-plus">+</button>
            </div>
          </div>

          <div class="feedback__box mb-50">
            <label for="personal-data" class="feedback__checkbox">
              <input
                type="checkbox"
                name="personal-data"
                id="personal-data"
                checked="checked"
              />
              <div class="feedback__checkbox-mark"></div>
              <span class="feedback__checkbox-info"
                >Я согласен с
                <a href="https://pro-stream.ru/politica" class="feedback__checkbox-link"
                  >политикой обработки персональных данных</a
                ></span
              >
            </label>
          </div>
          <input type="hidden" id="zal" name="zal" <?php if (isset($_GET['zal'])) {echo 'value="'.$_GET['zal'].'"';} ?> />
          <div class="feedback__box">
            <input type="submit" value="Предварительная бронь" id="form-send" />
          </div>
        </form>
      </div>
    </section>

    <script src="./libraries/vanilla-calendar/build/vanilla-calendar.min.js"></script>
    <script src="./js/script.js"></script>
  </body>
</html>