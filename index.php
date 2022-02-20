<?php
require 'controller.php'; ?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Краткое описание сайта" />
    <title>Конструктор варенья</title>
    <link rel="stylesheet" href="css/styles.css" />
    <link rel="stylesheet" href="css/stylesmedia.css" />
    <link rel="stylesheet" href="css/media.css" />
    <link rel="stylesheet" href="css/fonts.css" />
    <link rel="shortcut" href="/favicon.ico" type="image/x-icon" />
  </head>
  <body>
    <header>
      <div class="container header__container">
      <div class="header__logo">
        <a href="#"> maminy <br />nishtyaki</a>
      </div>
     
      <div class="header__menu">
            <nav>
              <ul>
                <li><a href="#">О нас </a></li>
                <li><a href="#">Конструктор </a></li>
                <li><a href="#">готовое варенье </a></li>
                <li><a href="#">Контакты </a></li>
              </ul>
            </nav>
      </div>
      <div class="header__burger">
        <span></span>
</div>
          <div class="header__contacts">
                          <a href="tel: +79153560935">+7 915 356 09 35</a>
                     </div>
           </div>
    </header>
    <div id="orderPopUp">
      <strong id="closeOrderPopUp">x</strong>
      <form id="popUpForm" action="">
        <h4>Ваш заказ:</h4>
        <ul>
         
        </ul>

        <input type="text" name="trashItems" hidden>
        <div><input type="text" placeholder="Как в вам обращаться" name="name"  required></div>
        <div><input type="tel" placeholder="+79999999999" pattern="\+7[0-9]{10}" name="phone" required></div>
        <div><input type="email" placeholder="test@test.com" name="email" required></div>
        <div><textarea name="comment" placeholder="Комментарий  к заказу"></textarea></div>
        <div class="total_amount">
          <label>
            ВСЕГО: 
            <strong id="fullOrderPrice"></strong>
          </label>
        </div>
        <div>
          <label><input type="checkbox" oninput="this.checked ? orderButton.disabled = false : orderButton.disabled = true"> Даю согласие на обработку </label><strong id="goToAgreement" onclick="agreementForm.hidden = !agreementForm.hidden">персональных данных</strong>

           <div id="agreementForm" hidden>Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis illum odio hic dolore ad aliquam placeat eos harum numquam maxime! Doloremque, facere quibusdam. Atque aliquam esse officia optio, sed quod.</div>
        </div>
        <div><button id="orderButton" type="submit" class="choice_button" disabled>Заказать</button></div>
        
      </form>
    </div>
    <section class="description">
      <div class="container">
        <h1>Мастерская варенья</h1>
        <div class="description__wrapper">
          <p>Вы попали в мастерскую варенья.</p>
          <p>
            Стань создателем своего вкуса. <br />
            Быть кондитером не так уж и сложно: <br> выбери ингредиенты, отправь
            заказ, а дальше мама все сделает в лучшем виде.
          </p>
          <p>Варенье как у мамы с любимым вкусом - это легко!</p>
        </div>
      </div>
    </section>
    <section class="filters">
      <div class="container">
        <div class="filters__wrapper">
          <?php foreach ($components as $component) { ?>
            <div class='taste tasteStack'  data-id='<?= $component[
                'id'
            ] ?>' style='background-image: url(../<?= $component[
    'picture'
] ?>)'>
             <?= $component['name'] ?>
            </div>
          <?php } ?>
        </div>
      </div>
    </section>
    <section class="constructor">
      <div class="container">
        <div class="constructor_wrapper">
          <div class="customer__choice">
            <div class="jamjar">
              
                           
            </div>
            <div class="final__choice">
              <input id="jamName" data-componentsList="" type="text" placeholder="Введите ваше название" />
              <button id="addToTrash" class="choice_button">В корзину</button>
              <button id="clearJar" class="choice_button">Очистить</button>
            </div>
          </div>
          <div class="sidebar__trash">
            <h2>Корзина</h2>
            <ul>
         
             
            </ul>
            <div class="total_amount">
              <p>ВСЕГО: </p>
                <strong id="fullPrice"></strong>
            </div>
            <button id="placeOrder" class="choice_button">оформить заказ</button>
          </div>
        </div>
      </div>
    </section>
    <section class="ready__jam">
      <h2>готовое варенье</h2>
     
      <ul id="readyJamList">
      <?php foreach ($readyJams as $readyJam) { ?>
            <li data-id="<?= $readyJam['id'] ?>" data-price="<?= $readyJam[
    'price'
] ?>">
             <?= $readyJam['name'] ?>
            </li>
          <?php } ?>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>
