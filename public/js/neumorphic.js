document.addEventListener('DOMContentLoaded', () => {
    
    // ✨ Effet pressé sur les boutons
    document.querySelectorAll('.neo-btn').forEach(btn => {
        btn.addEventListener('mousedown', () => btn.classList.add('neo-card--pressed'));
        btn.addEventListener('mouseup', () => btn.classList.remove('neo-card--pressed'));
        btn.addEventListener('mouseleave', () => btn.classList.remove('neo-card--pressed'));
    });

    // 🎴 Effet hover subtil sur les cartes
    document.querySelectorAll('.neo-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateY = (x - centerX) / 40;
            const rotateX = (centerY - y) / 40;
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });

    // ✨ Particules dynamiques
    function createParticle() {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + '%';
        p.style.animationDuration = (10 + Math.random() * 10) + 's';
        document.querySelector('.particles')?.appendChild(p);
        setTimeout(() => p.remove(), 20000);
    }
    setInterval(createParticle, 3000);

    // 🔔 Auto-hide notifications
    document.querySelectorAll('.neo-notification').forEach(notif => {
        setTimeout(() => {
            notif.style.opacity = '0';
            notif.style.transform = 'translateX(100%)';
            setTimeout(() => notif.remove(), 300);
        }, 5000);
    });
});