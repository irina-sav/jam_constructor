<?php
require "controller.php"; 
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Краткое описание сайта" />
    <title>Конструктор варенья</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/media.css" />
    <link rel="stylesheet" href="css/fonts.css" />
    <link rel="shortcut" href="/favicon.ico" type="image/x-icon" />
  </head>
  <body>
    <header>
      <div class="container header__container">
        <div class="header__wrapper">
          <div class="header__logo">
            <p>
              <a href="#"> maminy <br />nishtyaki</a>
            </p>
          </div>
          <div class="header__menu">
            <nav>
              <ul>
                <li><a href="#">О нас </a></li>
                <li><a href="#">Конструктор </a></li>
                <li><a href="#">Контакты </a></li>
              </ul>
            </nav>
          </div>
          <div class="header__contacts">
            <div class="contacts__wrapper">
              <a href="tel: +79153560935">+7 915 356 09 35</a>
            </div>
          </div>
        </div>
      </div>
    </header>
    <section class="description">
      <div class="container">
        <h1>Мастерская варенья</h1>
        <div class="description__wrapper">
          <p>Вы попали в мастерскую варенья.</p>
          <p>
            Стань создателем своего вкуса. <br />
            Быть кондитером не так уж и сложно: выбери ингредиенты, отправь
            заказ, а дальше мама все сделает в лучшем виде.
          </p>
          <p>Варенье как у мамы с любимым вкусом - это легко!</p>
        </div>
      </div>
    </section>
    <section class="filters">
      <div class="container">
        <div class="filters__wrapper">
          <?
          foreach($components as $component) {
            echo "<div class='taste' id='apple' style='background-image: url(../{$component["picture"]})'>{$component["name"]}</div>";
          }
        ?>
        </div>
      </div>
    </section>
    <section class="constructor">
      <div class="container">
        <div class="constructor_wrapper">
          <div class="customer__choice">
            <div class="jamjar">
              <div class="taste">1 слой</div>
              <div class="taste">2 слой</div>
              <div class="taste">3 слой</div>
            </div>
            <div class="final__choice">
              <input type="text" placeholder="Введите ваше название" />
              <button type="submit" class="choice_button">авада кедавра</button>
            </div>
          </div>
          <div class="sidebar__trash">
            <h2>Корзина</h2>
            <div class="counter">1</div>
            <div class="total_amount">
              <p>ВСЕГО</p>
              <input type="text" placeholder="сумма заказа" />
            </div>
            <button type="submit" class="choice_button">оформить заказ</button>
          </div>
        </div>
      </div>
    </section>
    <section class="ready__jam">
      <h2>готовое варенье</h2>
      <ul>
        <li>Яблоко-банан</li>
        <li>слива-шоколад</li>
        <li>абрикос-клубника</li>
        <li>слива-апельсин</li>
        <li>груша-киви</li>
      </ul>
    </section>
    <footer>
      <div class="footer__contacts">
        <a href="tel: +79153560935">+7 915 356 09 35</a>
        <a href="https://www.instagram.com/maminy_nishtyaki/">
          @maminy_nishtyaki
        </a>
        <p>адрес</p>
      </div>
    </footer>
    
  </body>
</html>
