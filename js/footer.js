(function($) {
    'use strict';

    const imageBasePath = footerVars.templateUri + '/images/';
    console.log('Image base path:', imageBasePath);

    // Функция debounce для оптимизации обработки resize
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    // Инициализация футера
    function initFooterSection() {
        const $footerInner = $('.footer__inner').first();
        if (!$footerInner.length) return;

        // Данные для футера
        const footerData = {
            logo: imageBasePath + 'logo.png',
            mainLinks: [
                { text: 'Категории', href: '#sweets', class: 'main-link' },
                { text: 'О нас', href: '#about', class: 'main-link' },
                { text: 'Блог', href: '#blog', class: 'main-link' },
                { text: 'Галерея', href: '#gallery', class: 'main-link' }
            ],
            subLinks: [
                { text: 'Рахат-лукум', href: "<?php echo esc_url(get_term_link('рахат-лукум', 'product_cat')); ?>", class: 'submenu__link' },
                { text: 'Пахлава', href: "<?php echo esc_url(get_term_link('пахлава', 'product_cat')); ?>", class: 'submenu__link' },
                { text: 'Кофе', href: "<?php echo esc_url(get_term_link('кофе', 'product_cat')); ?>", class: 'submenu__link' },
                { text: 'Пишмание', href: "<?php echo esc_url(get_term_link('пишмание', 'product_cat')); ?>", class: 'submenu__link' }
            ],
            contacts: {
                phone: '8 (800) 111-11-11',
                email: 'email@mail.ru'
            },
            policyText: 'Политика конфиденциальности и обработки персональных данных',
            socialIcons: ['whatsapp', 'telegram', 'mail', 'number']
        };

        // Функция определения типа устройства
        function getDeviceType() {
            const width = window.innerWidth;
            if (width < 768) return 'mobile';
            if (width < 1024) return 'tablet';
            return 'desktop';
        }

        // Генерация HTML для футера
        function generateFooterHTML(deviceType) {
            let html = '';
            
            if (deviceType === 'mobile') {
                html = `
                    <div class="footer__nav">
                        <div class="footer__nav-menu">
                            <div class="footer__menu-items">
                                <nav class="footer__menu-main">
                                    <div class="footer__menu-logo"><img src="${footerData.logo}" alt=""></div>
                                    <a href="#about" class="main-link">О нас</a>
                                    <a href="#blog" class="main-link">Блог</a>
                                    <a href="#gallery" class="main-link">Галерея</a>
                                </nav>
                                <nav class="footer__menu-submain">
                                    <a href="#sweets" class="main-link">Категории</a>
                                    ${footerData.subLinks.map(link => `
                                        <a href="${link.href}" class="${link.class}">${link.text}</a>
                                    `).join('')}
                                </nav>
                            </div>
                        </div>
                        <div class="footer__nav-contact">
                            <h4>Контакты</h4>
                            <div class="footer__contact-address">
                                <p class="contact-footer">телефон ${footerData.contacts.phone}</p>
                                <p class="contact-footer">почта ${footerData.contacts.email}</p>
                            </div>
                            <div class="footer__social">
                                ${footerData.socialIcons.map(icon => `
                                    <svg class="social__icon"><use xlink:href="${imageBasePath}sprite.svg#${icon}"></use></svg>
                                `).join('')}
                            </div>
                        </div>
                        <p class="footer_notescript">${footerData.policyText}</p>
                    </div>
                `;
            } 
            else if (deviceType === 'tablet') {
                html = `
                    <div class="footer__nav-menu">
                        <div class="footer__menu-logo"><img src="${footerData.logo}" alt=""></div>
                        <div class="footer__menu-items">
                            <nav class="footer__menu-main">
                                ${footerData.mainLinks.map(link => `
                                    <a href="${link.href}" class="${link.class}">${link.text}</a>
                                `).join('')}
                            </nav>
                            <nav class="footer__menu-submain">
                                ${footerData.subLinks.map(link => `
                                    <a href="${link.href}" class="${link.class}">${link.text}</a>
                                `).join('')}
                            </nav>
                        </div>
                    </div>
                    <div class="footer__nav">
                        <p class="footer_notescript">${footerData.policyText}</p>
                        <div class="footer__nav-contact">
                            <h4>Контакты</h4>
                            <div class="footer__contact-address">
                                <p class="contact-footer">телефон ${footerData.contacts.phone}</p>
                                <p class="contact-footer">почта ${footerData.contacts.email}</p>
                            </div>
                            <div class="footer__social">
                                ${footerData.socialIcons.map(icon => `
                                    <svg class="social__icon"><use xlink:href="${imageBasePath}sprite.svg#${icon}"></use></svg>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            } 
            else {
                // Desktop version
                html = `
                    <div class="footer__nav">
                        <div class="footer__nav-menu">
                            <div class="footer__menu-logo"><img src="${footerData.logo}" alt=""></div>
                            <div class="footer__menu-items">
                                <nav class="footer__menu-main">
                                    ${footerData.mainLinks.map(link => `
                                        <a href="${link.href}" class="${link.class}">${link.text}</a>
                                    `).join('')}
                                </nav>
                                <nav class="footer__menu-submain">
                                    ${footerData.subLinks.map(link => `
                                        <a href="${link.href}" class="${link.class}">${link.text}</a>
                                    `).join('')}
                                </nav>
                            </div>
                        </div>
                        <div class="footer__nav-contact">
                            <h4>Контакты</h4>
                            <div class="footer__contact-address">
                                <p class="contact-footer">телефон ${footerData.contacts.phone}</p>
                                <p class="contact-footer">почта ${footerData.contacts.email}</p>
                            </div>
                            <div class="footer__social">
                                ${footerData.socialIcons.map(icon => `
                                    <svg class="social__icon"><use xlink:href="${imageBasePath}sprite.svg#${icon}"></use></svg>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                    <p class="footer_notescript">${footerData.policyText}</p>
                `;
            }

            return html;
        }

        // Рендер футера
        function renderFooter() {
            const deviceType = getDeviceType();
            const footerHTML = generateFooterHTML(deviceType);
            
            $footerInner.html(footerHTML);
            
            // Инициализация обработчиков событий после рендера
            initFooterInteractions();
        }

        // Инициализация взаимодействий с футером
        function initFooterInteractions() {
            // Обработка кликов по ссылкам
            $('.main-link, .submenu__link').on('click', function(e) {
                e.preventDefault();
                const href = $(this).attr('href');
                
                if (href.startsWith('#')) {
                    const target = $(href);
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top
                        }, 800);
                    }
                } else {
                    window.location.href = href;
                }
            });

            // Обработка кликов по соц. иконкам
            $('.social__icon').on('click', function() {
                const network = $(this).find('use').attr('xlink:href').split('#')[1];
                console.log('Social network clicked:', network);
                
                // Здесь можно добавить ссылки на соц. сети
                const socialLinks = {
                    whatsapp: 'https://wa.me/78001111111',
                    telegram: 'https://t.me/yourchannel',
                    mail: 'mailto:email@mail.ru',
                    number: 'tel:88001111111'
                };
                
                if (socialLinks[network]) {
                    window.open(socialLinks[network], network === 'mail' || network === 'number' ? '_self' : '_blank');
                }
            });
        }

        // Первоначальный рендер
        renderFooter();

        // Реакция на изменение размера окна
        $(window).on('resize', debounce(function() {
            renderFooter();
        }, 300));
    }

    // Инициализация при загрузке документа
    $(document).ready(function() {
        initFooterSection();
    });

})(jQuery);