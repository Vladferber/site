<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('nav').classList.toggle('show');
        });
        
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    if (document.querySelector('nav').classList.contains('show')) {
                        document.querySelector('nav').classList.remove('show');
                    }
                }
            });
        });
        
        // FAQ Accordion functionality - исправленная версия
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', (e) => {
                // Проверяем, был ли клик именно по кнопке закрытия
                const isToggleClick = e.target.classList.contains('faq-toggle') || 
                                     e.target.parentElement.classList.contains('faq-toggle');
                
                const faqItem = question.parentElement;
                const isActive = faqItem.classList.contains('active');
                
                // Если клик был по кнопке и элемент активен - закрываем
                if (isToggleClick && isActive) {
                    faqItem.classList.remove('active');
                    return;
                }
                
                // Закрываем все другие элементы
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (item !== faqItem) {
                        item.classList.remove('active');
                    }
                });
                
                // Открываем текущий элемент, если он не был активен
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });

        // GSAP animations
        gsap.registerPlugin(ScrollTrigger);
        
        // Animate elements on scroll
        gsap.utils.toArray('.animate__animated').forEach(element => {
            const animation = element.classList.contains('animate__fadeInUp') ? 'fadeInUp' : 
                             element.classList.contains('animate__fadeInRight') ? 'fadeInRight' : 'fadeIn';
            
            gsap.from(element, {
                scrollTrigger: {
                    trigger: element,
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                },
                opacity: 0,
                y: animation === 'fadeInUp' ? 50 : 0,
                x: animation === 'fadeInRight' ? 50 : 0,
                duration: 1,
                ease: 'power3.out'
            });
        });
        
        // Form submission
        document.getElementById('application-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Here you would normally send the form data to the server
            // For demo purposes, we'll just show an alert
            alert('Заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.');
            this.reset();
        });
    </script>