/**
 * 🎮 Interactions 3D pour Hôtel Madagascar
 */

document.addEventListener('DOMContentLoaded', () => {
    
    // 🎴 Effet Tilt 3D sur les cartes
    const cards = document.querySelectorAll('.card-3d');
    cards.forEach(card => {
        let isHovering = false;
        
        card.addEventListener('mousemove', (e) => {
            if (!isHovering) return;
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateY = (x - centerX) / 25;
            const rotateX = (centerY - y) / 25;
            card.style.transform = `perspective(1200px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });
        
        card.addEventListener('mouseenter', () => { isHovering = true; });
        card.addEventListener('mouseleave', () => {
            isHovering = false;
            card.style.transform = 'perspective(1200px) rotateX(0) rotateY(0) scale(1)';
        });
    });

    // ✨ Générateur de particules
    function createParticles(count = 20) {
        const container = document.getElementById('particles');
        if(!container) return;
        
        for(let i = 0; i < count; i++) {
            setTimeout(() => {
                const p = document.createElement('div');
                p.className = 'particle';
                p.style.left = Math.random() * 100 + '%';
                p.style.animationDelay = Math.random() * 8 + 's';
                p.style.animationDuration = (6 + Math.random() * 6) + 's';
                container.appendChild(p);
                setTimeout(() => p.remove(), 12000);
            }, i * 200);
        }
    }
    createParticles();
    setInterval(() => createParticles(3), 5000);

    // 🎲 Effet parallaxe au scroll
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        document.querySelectorAll('.wave-bg').forEach(wave => {
            wave.style.transform = `translateY(${scrolled * 0.2}px)`;
        });
    });
});