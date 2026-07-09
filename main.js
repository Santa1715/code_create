/* ============================================
   PROYECTO: CODE & CREATE
   Taller de Desarrollo Web para Jóvenes
   Torre Guayacán - UBA Servicio Comunitario
   Autor: Leonel Rondón - CI: 32.079.527
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {
    
    // 1. GUARDAR Y RECUPERAR INICIALES
    const initialsInput = document.getElementById('userInitials');
    const savedInitials = localStorage.getItem('userInitials');
    if (savedInitials) {
        initialsInput.value = savedInitials;
    }

    initialsInput.addEventListener('input', (e) => {
        const value = e.target.value.toUpperCase();
        e.target.value = value;
        localStorage.setItem('userInitials', value);
    });

    initialsInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            initialsInput.blur();
        }
    });

    // 2. GUARDAR Y RECUPERAR NOMBRE DEL USUARIO
    const userName = document.getElementById('userName');
    const savedName = localStorage.getItem('userName');
    if (savedName) {
        userName.textContent = savedName;
    }

    userName.addEventListener('input', () => {
        localStorage.setItem('userName', userName.textContent.trim());
    });

    userName.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            userName.blur();
        }
    });

    // 3. GUARDAR Y RECUPERAR "SOBRE MÍ"
    const aboutMe = document.getElementById('aboutMe');
    const savedAbout = localStorage.getItem('aboutMe');
    if (savedAbout) {
        aboutMe.textContent = savedAbout;
    }

    aboutMe.addEventListener('input', () => {
        localStorage.setItem('aboutMe', aboutMe.textContent.trim());
    });

    // 4. GUARDAR Y RECUPERAR TÍTULO DEL PROYECTO
    const projectTitle = document.getElementById('projectTitle');
    const savedTitle = localStorage.getItem('projectTitle');
    if (savedTitle) {
        projectTitle.textContent = savedTitle;
    }

    projectTitle.addEventListener('input', () => {
        localStorage.setItem('projectTitle', projectTitle.textContent.trim());
    });

    // 5. GUARDAR Y RECUPERAR DESCRIPCIÓN DEL PROYECTO
    const projectDesc = document.getElementById('projectDesc');
    const savedDesc = localStorage.getItem('projectDesc');
    if (savedDesc) {
        projectDesc.textContent = savedDesc;
    }

    projectDesc.addEventListener('input', () => {
        localStorage.setItem('projectDesc', projectDesc.textContent.trim());
    });

    // 6. GUARDAR Y RECUPERAR NOMBRE DEL FOOTER
    const footerName = document.getElementById('footerName');
    const savedFooter = localStorage.getItem('footerName');
    if (savedFooter) {
        footerName.textContent = savedFooter;
    }

    footerName.addEventListener('input', () => {
        localStorage.setItem('footerName', footerName.textContent.trim());
    });

    // 7. AÑO DINÁMICO EN FOOTER
    document.getElementById('year').textContent = new Date().getFullYear();

    // 8. TEMA OSCURO/CLARO CON PERSISTENCIA
    const toggle = document.getElementById('theme-toggle');
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    document.documentElement.setAttribute('data-theme', savedTheme);
    toggle.textContent = savedTheme === 'dark' ? '️' : '🌙';

    toggle.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
        toggle.textContent = next === 'dark' ? '☀️' : '🌙';
    });

    // 9. ENVÍO SEGURO DEL FORMULARIO DE CONTACTO
    const form = document.getElementById('contactForm');
    const status = document.getElementById('formStatus');

    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            status.textContent = ' Enviando...';
            status.style.color = 'var(--primary)';

            const data = new FormData(form);

            try {
                const res = await fetch('php/contacto.php', {
                    method: 'POST',
                    body: data
                });

                const result = await res.json();

                if (result.success) {
                    status.textContent = '✅ ¡Mensaje enviado! Responderé pronto.';
                    status.style.color = '#059669';
                    form.reset();
                } else {
                    status.textContent = `❌ ${result.message || 'Error al enviar.'}`;
                    status.style.color = '#dc2626';
                }
            } catch (error) {
                status.textContent = '❌ Error de red. Verifica tu conexión.';
                status.style.color = '#dc2626';
                console.error('Error:', error);
            }
        });
    }

    // 10. SCROLL SUAVE PARA NAVEGACIÓN
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    console.log('%c🎨 Code & Create - Taller de Desarrollo Web', 'color: #4f46e5; font-size: 16px; font-weight: bold;');
    console.log('%c👨‍💻 Proyecto de Servicio Comunitario UBA', 'color: #7c3aed; font-size: 12px;');
    console.log('%c📍 Torre Guayacán - Barcelona, Anzoátegui', 'color: #10b981; font-size: 12px;');
});