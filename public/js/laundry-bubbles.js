/**
 * Dual-Layer Soap Bubbles Engine (60 FPS GPU-Accelerated)
 * FreshWash Laundry UMKM
 * Layer 1: Background Bubbles (Behind content, z-index: 0)
 * Layer 2: Foreground Floating Bubbles (In front of content, z-index: 9999)
 */

(function() {
    'use strict';

    // Play subtle bubble pop sound using Web Audio API
    function playBubblePop() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(580 + Math.random() * 220, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(110, ctx.currentTime + 0.08);

            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.08);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start();
            osc.stop(ctx.currentTime + 0.09);
        } catch (e) {}
    }

    function createBubbleContainers() {
        if (document.getElementById('soap-bubbles-bg')) return;

        // Inject Styles for Dual-Layer Bubbles
        if (!document.getElementById('soap-bubble-css')) {
            const style = document.createElement('style');
            style.id = 'soap-bubble-css';
            style.textContent = `
                /* Background Bubble Container (Behind cards) */
                .bubble-container-bg {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    height: 100vh;
                    pointer-events: none;
                    z-index: 0;
                    overflow: hidden;
                }

                /* Foreground Bubble Container (In front of cards & content!) */
                .bubble-container-fg {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    height: 100vh;
                    pointer-events: none;
                    z-index: 9999;
                    overflow: hidden;
                }

                /* Shared Bubble Styling */
                .soap-bubble {
                    position: absolute;
                    bottom: -90px;
                    border-radius: 50%;
                    pointer-events: auto;
                    cursor: pointer;
                    animation: floatBubbleUp linear forwards;
                    transition: transform 0.15s ease, opacity 0.15s ease;
                    user-select: none;
                }

                /* Background bubble: softer, ambient */
                .soap-bubble-bg {
                    background: radial-gradient(circle at 35% 30%, 
                        rgba(255, 255, 255, 0.9) 0%, 
                        rgba(224, 242, 254, 0.45) 35%, 
                        rgba(56, 189, 248, 0.3) 65%, 
                        rgba(244, 114, 182, 0.25) 85%,
                        rgba(168, 85, 247, 0.18) 100%
                    );
                    box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.8), 
                                0 4px 12px rgba(2, 132, 199, 0.1);
                    opacity: 0.7;
                }

                /* Foreground bubble: glassy, vivid, drifting right across the screen */
                .soap-bubble-fg {
                    background: radial-gradient(circle at 32% 28%, 
                        rgba(255, 255, 255, 0.92) 0%, 
                        rgba(224, 242, 254, 0.3) 30%, 
                        rgba(6, 182, 212, 0.25) 60%, 
                        rgba(251, 113, 133, 0.28) 80%,
                        rgba(129, 140, 248, 0.25) 100%
                    );
                    box-shadow: inset 0 0 14px rgba(255, 255, 255, 0.95), 
                                0 6px 20px rgba(2, 132, 199, 0.2),
                                0 0 12px rgba(56, 189, 248, 0.3);
                    backdrop-filter: blur(1.5px);
                    -webkit-backdrop-filter: blur(1.5px);
                    border: 1px solid rgba(255, 255, 255, 0.6);
                }

                /* Bubble Glare Reflection */
                .soap-bubble::after {
                    content: '';
                    position: absolute;
                    top: 16%;
                    left: 20%;
                    width: 28%;
                    height: 20%;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.95);
                    transform: rotate(-35deg);
                }

                /* Secondary micro glare */
                .soap-bubble-fg::before {
                    content: '';
                    position: absolute;
                    bottom: 18%;
                    right: 22%;
                    width: 14%;
                    height: 10%;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.75);
                }

                .soap-bubble:hover {
                    transform: scale(1.28);
                }

                @keyframes floatBubbleUp {
                    0% {
                        transform: translateY(0) translateX(0) scale(0.9);
                        opacity: 0;
                    }
                    8% {
                        opacity: 0.88;
                    }
                    50% {
                        transform: translateY(-55vh) translateX(30px) scale(1.0);
                    }
                    88% {
                        opacity: 0.85;
                    }
                    100% {
                        transform: translateY(-118vh) translateX(-20px) scale(1.1);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }

        // Layer 1: Background Wrapper
        const wrapperBg = document.createElement('div');
        wrapperBg.id = 'soap-bubbles-bg';
        wrapperBg.className = 'bubble-container-bg';
        document.body.appendChild(wrapperBg);

        // Layer 2: Foreground Wrapper (In front of everything!)
        const wrapperFg = document.createElement('div');
        wrapperFg.id = 'soap-bubbles-fg';
        wrapperFg.className = 'bubble-container-fg';
        document.body.appendChild(wrapperFg);

        const isMobile = window.innerWidth < 768;
        const maxBgBubbles = isMobile ? 6 : 10;
        const maxFgBubbles = isMobile ? 5 : 8;
        let currentBgBubbles = 0;
        let currentFgBubbles = 0;

        function spawnBubble(isForeground) {
            if (document.hidden) return;
            const container = isForeground ? wrapperFg : wrapperBg;
            const currentCount = isForeground ? currentFgBubbles : currentBgBubbles;
            const maxCount = isForeground ? maxFgBubbles : maxBgBubbles;

            if (currentCount >= maxCount) return;

            const bubble = document.createElement('div');
            bubble.className = `soap-bubble ${isForeground ? 'soap-bubble-fg' : 'soap-bubble-bg'}`;

            // Foreground bubbles are slightly larger & clearer (28px - 68px)
            const minSize = isForeground ? 28 : 20;
            const maxSize = isForeground ? 68 : 55;
            const size = Math.floor(Math.random() * (maxSize - minSize)) + minSize;
            bubble.style.width = size + 'px';
            bubble.style.height = size + 'px';

            // Random horizontal start (0 to 95vw)
            const left = Math.random() * 94;
            bubble.style.left = left + 'vw';

            // Floating speed (10s to 20s)
            const duration = (Math.random() * 9 + 10).toFixed(1);
            bubble.style.animationDuration = duration + 's';

            // Click to pop!
            bubble.addEventListener('click', function(e) {
                e.stopPropagation();
                playBubblePop();
                bubble.style.transform = 'scale(1.8)';
                bubble.style.opacity = '0';
                setTimeout(() => {
                    if (bubble.parentNode) bubble.remove();
                    if (isForeground) currentFgBubbles--; else currentBgBubbles--;
                }, 140);
            });

            container.appendChild(bubble);
            if (isForeground) currentFgBubbles++; else currentBgBubbles++;

            // Auto cleanup when done
            setTimeout(() => {
                if (bubble.parentNode) bubble.remove();
                if (isForeground) currentFgBubbles--; else currentBgBubbles--;
            }, duration * 1000);
        }

        // Initial Batches for both layers
        for (let i = 0; i < 5; i++) {
            setTimeout(() => spawnBubble(false), i * 350); // Background
        }
        for (let i = 0; i < 4; i++) {
            setTimeout(() => spawnBubble(true), i * 500 + 200); // Foreground!
        }

        // Periodic Spawning: Alternate background & foreground
        setInterval(() => spawnBubble(false), 1600);
        setInterval(() => spawnBubble(true), 2100);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', createBubbleContainers);
    } else {
        createBubbleContainers();
    }
})();