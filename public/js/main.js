$(function() {
'use strict';

let filterItems = [];

// 管理画面 サイドバー開閉（SP時）
$('.js-click-toggle').on('click', function() {
  $('.js-click-toggle-icon-t').toggleClass('active');
  $('.js-click-toggle-icon-f').toggleClass('active');
  $('.js-click-toggle-sidebar').toggleClass('active');
});

// 管理画面 フォローリストページ ソートメニュー
$('.js-click-menu-toggle1').on('click', function() {
  $('.js-click-menu1').toggleClass('active');
  $('.js-whole').toggleClass('active')
});
$('.js-click-menu-item1').on('click', function() {
  $('.js-click-menu-display1').text($(this).text());
  $('.js-click-menu1').removeClass('active');
  $('.js-whole').removeClass('active');
});

// 管理画面 フォローリストページ フィルターメニュー
$('.js-click-menu-toggle2').on('click', function() {
  $('.js-click-menu2').toggleClass('active');
  $('.js-whole').toggleClass('active');
});

$('.js-whole').on('click', function() {
  $('.js-click-menu1').removeClass('active');
  $('.js-click-menu2').removeClass('active');
  $('.js-whole').removeClass('active');
  $('.js-filter-form').submit();
});

$('.js-click-checkbox-target').on('click', function() {
  let filterItem = $(this).children('.checkbox-label').text();

  if ($(this).children('.js-click-checkbox').prop('checked') == true) {
    $(this).addClass('active');
    filterItems.push(filterItem);
  } else {
    $(this).removeClass('active');
    filterItems = filterItems.filter(function(element) {
      return element !== filterItem;
    });
  }
});

// アカウント削除 確認ダイアログ
$('.js-click-confirm').on('click', function() {
  if (confirm('本当に削除しますか？')) {
    $('.js-click-confirm-target').submit();
  }
});

// Swiper
let swiperDashboard = new Swiper('.swiper-dashboard', {
  loop: true,
  pagination: {
    el: '.swiper-pagination',
    type: 'bullets',
    clickable: true,
  },
});

let swiperTop = new Swiper('.swiper-top', {
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },
  loop: true,
  pagination: {
    el: '.swiper-pagination',
    type: 'bullets',
    clickable: true,
  },
});

// フラッシュメッセージ
$('.flash_message').fadeOut(3000);
});
