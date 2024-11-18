(function() {
  jQuery(function($) {
    $(".swipebox").swipebox({
      hideBarsDelay: 0,
    });
  });


  
  /**
   * Global tools
   */
  const fader = document.querySelector('#fader');
  const isNumeric = (num) => (typeof(num) === 'number' || typeof(num) === "string" && num.trim() !== '') && !isNaN(num);



  /**
   * Show/Hide mobile menu
   */
  const mobileMenu = document.getElementById('header-flyout');
  if (mobileMenu !== null) {

    function closeMobileMenu() {
      mobileMenu.classList.remove('active');
      document.documentElement.classList.remove('noscroll');
      document.body.classList.remove('noscroll');
      if (fader !== null) {
        fader.classList.remove('active');
      }
    }
    function detectClickOutsideMobileMenu(e) {
      if (e.target === fader) {
        closeMobileMenu();
        window.removeEventListener(e.type, detectClickOutsideMobileMenu);
      }
      if (!mobileMenu.classList.contains('active')) {
        window.removeEventListener(e.type, detectClickOutsideMobileMenu);
      }
    }
    function openMobileMenu() {
      mobileMenu.classList.add('active');
      document.documentElement.classList.add('noscroll');
      document.body.classList.add('noscroll');
      if (fader !== null) {
        fader.classList.add('active');
      }
      window.addEventListener('click', detectClickOutsideMobileMenu);
    }
  
    const openMenuBtns = document.querySelectorAll('.open-mobile-menu');
    for (const btn of openMenuBtns) {
      btn.addEventListener('click', openMobileMenu);
    }
  
    const closeMenuBtns = document.querySelectorAll('.close-mobile-menu');
    for (const btn of closeMenuBtns) {
      btn.addEventListener('click', closeMobileMenu);
    }
    
  }



  /**
   * Update cart counter
   */
  jQuery(function($) {
    $(document.body).on('added_to_cart', function() {
      const cartCounter = document.querySelector('.header-cart__counter');
      const quantity = document.querySelector('input[name="quantity"].qty');

      cartCounter.textContent = +cartCounter.textContent + (quantity ? +quantity.value : 1); // optimistic

      wp.ajax.post("get_cart_items_count", {})
        .done(function(response) {
          // got results
          if (cartCounter !== null) {
            response = JSON.parse(response);
            if (isNumeric(response['count'])) {
              cartCounter.textContent = response['count'];
            }
          }
        })
        .fail(function(response) {
          // got no results or error
          console.log('Ошибка при обновлении количества товаров в корзине');
        });
    });
  });



  /**
   * Enable masked input for phones
   */
  jQuery(function($){
    $("input[type='tel']").mask("+7 (999) 999-99-99");
  });
})();