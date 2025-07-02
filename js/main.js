$('.intro__slider').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows:true,
  autoplay:5000,
  prevArrow:'.intro__arrow-prev',
  nextArrow:'.intro__arrow-next',
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
  console.log("Ширина экрана:", screenWidth);
  
  const mobileContent = document.querySelector('.mobile-content');
  const tableContent = document.querySelector('.table-content');
  const desktopContent = document.querySelector('.desktop-content');
  
  console.log("Найденные элементы:", { mobileContent, tableContent, desktopContent });

  if (screenWidth < 768) {
    mobileContent.style.display = "flex";
    tableContent.style.display = "none";
    desktopContent.style.display = "none";
  } 
  else if (screenWidth < 1024) {
    mobileContent.style.display = "none";
    tableContent.style.display = "flex";
    desktopContent.style.display = "none";
  } 
  else {
    mobileContent.style.display = "none";
    tableContent.style.display = "none";
    desktopContent.style.display = "flex";
  }
}

window.addEventListener('load', updateLayout);
window.addEventListener('resize', updateLayout);