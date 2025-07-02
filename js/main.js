$('.intro__slider').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows:true,
  autoplay:5000,
  prevArrow:'.intro__arrow-prev',
  nextArrow:'.intro__arrow-next',
});
function initSlickSlider() {
  const screenWidth = window.innerWidth;
  const $gallery = $('.gallery__wrapper');
  const mobileBreakpoint = 1200;
  if (screenWidth < mobileBreakpoint && !$gallery.hasClass('slick-initialized')) {
    $gallery.slick({
      slidesToScroll: 1,
      slidesToShow:3,
      infinite:true,
      centerMode: true,
      variableWidth: true,
      arrows: true,
      autoplay: 5000,
      prevArrow: '.about__arrow-prev',
      nextArrow: '.about__arrow-next',
    });
  } else if (screenWidth >= mobileBreakpoint && $gallery.hasClass('slick-initialized')) {
    $gallery.slick('unslick');
  }
}
$(document).ready(function() {
  initSlickSlider();

  $(window).on('resize', function() {
    initSlickSlider();
  });
});
document.addEventListener('DOMContentLoaded', function() {
  document.addEventListener('click', function(e) {
    let targetElement = e.target.closest('[data-target]');
    if (targetElement) {
      e.preventDefault();
      const targetId = targetElement.getAttribute('data-target');
      scrollToElement(targetId);
    } 
    if (e.target.tagName === 'A' && e.target.getAttribute('href')?.startsWith('#')) {
      e.preventDefault();
      const targetId = e.target.getAttribute('href').substring(1);
      scrollToElement(targetId);
    }
  });
  function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
      const headerHeight = document.querySelector('header')?.offsetHeight || 0;
      const offset = 20;
      const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
      const offsetPosition = elementPosition - headerHeight - offset;
      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  }
  if (window.location.hash) {
    const targetId = window.location.hash.substring(1);
    setTimeout(() => {
      scrollToElement(targetId);
    }, 100);
  }
});
function updateLayout() {
  const screenWidth = window.innerWidth;
  const allDeviceElements = document.querySelectorAll('[data-device]');
  allDeviceElements.forEach(el => {
    el.style.display = 'none';
  });
  if (screenWidth < 768) {
    document.querySelectorAll('[data-device="mobile"]').forEach(el => {
      el.style.display = 'flex';
    });
  } 
  else if (screenWidth < 1024) {
    document.querySelectorAll('[data-device="tablet"]').forEach(el => {
      el.style.display = 'flex';
    });
  } 
  else {
    document.querySelectorAll('[data-device="desktop"]').forEach(el => {
      el.style.display = 'flex';
    });
  }
}
updateLayout();
window.addEventListener('resize', updateLayout);