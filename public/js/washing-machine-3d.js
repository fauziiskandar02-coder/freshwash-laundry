/**
 * Si Wangi (Washy) - Optimized 3D Chibi Mascot
 * Features:
 * - 60 FPS buttery smooth Three.js rendering
 * - Automatic continuous 360 rotation (no click needed!)
 * - Interactive mouse/touch drag
 * - Turbo spin mode ("Putar Cepat") with bubble jingle
 * - 2D SVG fallback
 */

(function() {
    'use strict';

    // 2D SVG Fallback
    function render2DFallback(container) {
        container.innerHTML = `
            <div style="width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; position:relative; overflow:hidden;">
                <div style="cursor:grab; text-align:center;">
                    <div style="font-size:3.5rem; animation: floatUp 3s infinite ease-in-out;">🧼</div>
                    <div style="font-weight:800; font-size:1rem; color:#0284c7; margin-top:8px;">Si Wangi (Washy)</div>
                    <div style="font-size:0.75rem; color:#64748b;">Maskot FreshWash Laundry</div>
                </div>
            </div>
        `;
    }

    // Cheerful chime sound
    function playBubbleJingle() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            const notes = [523.25, 659.25, 783.99, 1046.50];
            notes.forEach((freq, idx) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + idx * 0.08);
                gain.gain.setValueAtTime(0.1, ctx.currentTime + idx * 0.08);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + idx * 0.08 + 0.3);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + idx * 0.08);
                osc.stop(ctx.currentTime + idx * 0.08 + 0.35);
            });
        } catch (e) {}
    }

    window.initWashBot3D = function(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (typeof THREE === 'undefined') {
            render2DFallback(container);
            return;
        }

        const width = container.clientWidth || 340;
        const height = container.clientHeight || 240;

        // Show subtle loading state
        container.innerHTML = `
            <div id="mascot-loading-box" style="width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#0284c7; font-family:'Nunito',sans-serif;">
                <div style="font-size:2.2rem; animation: floatUp 2s infinite ease-in-out;" class="mb-2">🫧</div>
                <div style="font-weight:800; font-size:0.9rem;">Memuat Maskot Si Wangi...</div>
            </div>
        `;

        function ensureGLTFLoader(callback) {
            if (THREE.GLTFLoader) {
                callback();
                return;
            }
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js';
            script.onload = () => callback();
            script.onerror = () => render2DFallback(container);
            document.head.appendChild(script);
        }

        ensureGLTFLoader(() => {
            initThreeScene(container, width, height);
        });
    };

    function initThreeScene(container, width, height) {
        try {
            const scene = new THREE.Scene();

            const camera = new THREE.PerspectiveCamera(38, width / height, 0.1, 100);
            camera.position.set(0, 0.2, 3.4);

            // Fast, lag-free WebGL Renderer (set pixel ratio to 1 for high FPS)
            const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
            renderer.setSize(width, height);
            renderer.setPixelRatio(1); // Crucial for zero lag!
            renderer.outputEncoding = THREE.sRGBEncoding;

            // Balanced, Natural 3D Studio Lighting (No over-exposure)
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.65);
            scene.add(ambientLight);

            const hemiLight = new THREE.HemisphereLight(0xffffff, 0x0284c7, 0.35);
            hemiLight.position.set(0, 20, 0);
            scene.add(hemiLight);

            const dirLight = new THREE.DirectionalLight(0xffffff, 0.85);
            dirLight.position.set(3, 5, 4);
            scene.add(dirLight);

            // Soft cyan-blue fill light to bring out depth and folds in white dress
            const fillLight = new THREE.DirectionalLight(0x38bdf8, 0.45);
            fillLight.position.set(-3, 2, -2);
            scene.add(fillLight);

            // Circular Water Podium (Rich Ocean Blue for Sharp Footing Contrast)
            const podiumGroup = new THREE.Group();
            podiumGroup.position.y = -1.05;
            scene.add(podiumGroup);

            const puddleGeo = new THREE.CylinderGeometry(1.35, 1.45, 0.05, 32);
            const puddleMat = new THREE.MeshBasicMaterial({ color: 0x0284c7, transparent: true, opacity: 0.85 });
            const puddle = new THREE.Mesh(puddleGeo, puddleMat);
            podiumGroup.add(puddle);

            const ringGeo = new THREE.RingGeometry(1.1, 1.25, 32);
            const ringMat = new THREE.MeshBasicMaterial({ color: 0x38bdf8, side: THREE.DoubleSide });
            const ring = new THREE.Mesh(ringGeo, ringMat);
            ring.rotation.x = -Math.PI / 2;
            ring.position.y = 0.03;
            podiumGroup.add(ring);

            // Floating 3D Bubbles
            const bubbleGeo = new THREE.SphereGeometry(0.065, 8, 8);
            const bubbleMat = new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.75 });
            const bubbles = [];
            for (let i = 0; i < 10; i++) {
                const b = new THREE.Mesh(bubbleGeo, bubbleMat);
                b.userData = {
                    angle: Math.random() * Math.PI * 2,
                    radius: 0.7 + Math.random() * 0.7,
                    speedY: 0.008 + Math.random() * 0.012
                };
                b.position.set(
                    Math.cos(b.userData.angle) * b.userData.radius,
                    -0.9 + Math.random() * 1.8,
                    Math.sin(b.userData.angle) * b.userData.radius
                );
                scene.add(b);
                bubbles.push(b);
            }

            const mascotGroup = new THREE.Group();
            scene.add(mascotGroup);

            let isSpinningFast = false;
            let isDragging = false;
            let prevMouseX = 0;
            let currentRotationY = 0;
            let targetRotationY = 0;

            // Load Optimized GLB
            const loader = new THREE.GLTFLoader();
            loader.load(
                '/models/mascot.glb',
                function(gltf) {
                    const loadingBox = document.getElementById('mascot-loading-box');
                    if (loadingBox) loadingBox.remove();
                    container.innerHTML = '';
                    container.appendChild(renderer.domElement);

                    const model = gltf.scene;

                    // Compute Bounding Box to center and scale
                    const box = new THREE.Box3().setFromObject(model);
                    const size = box.getSize(new THREE.Vector3());
                    const center = box.getCenter(new THREE.Vector3());

                    const targetHeight = 2.45;
                    const maxDim = Math.max(size.x, size.y, size.z);
                    const scaleFactor = targetHeight / (maxDim || 1);
                    model.scale.set(scaleFactor, scaleFactor, scaleFactor);

                    model.position.x = -center.x * scaleFactor;
                    model.position.y = -box.min.y * scaleFactor - 1.05;
                    model.position.z = -center.z * scaleFactor;

                    // Initial orientation: face forward
                    model.rotation.y = Math.PI;

                    mascotGroup.add(model);

                    // Interactive fast wash spin
                    window.triggerWashSpin = function() {
                        isSpinningFast = true;
                        playBubbleJingle();
                        setTimeout(() => {
                            isSpinningFast = false;
                        }, 3500);
                    };
                },
                undefined,
                function(error) {
                    console.error('Error loading mascot.glb:', error);
                    render2DFallback(container);
                }
            );

            // Mouse & Touch Dragging
            const domEl = renderer.domElement;
            domEl.addEventListener('mousedown', e => { isDragging = true; prevMouseX = e.clientX; });
            window.addEventListener('mouseup', () => { isDragging = false; });
            domEl.addEventListener('mousemove', e => {
                if (!isDragging) return;
                targetRotationY += (e.clientX - prevMouseX) * 0.015;
                prevMouseX = e.clientX;
            });

            domEl.addEventListener('touchstart', e => {
                if (e.touches.length === 1) { isDragging = true; prevMouseX = e.touches[0].clientX; }
            }, { passive: true });
            window.addEventListener('touchend', () => { isDragging = false; });
            domEl.addEventListener('touchmove', e => {
                if (!isDragging || e.touches.length !== 1) return;
                targetRotationY += (e.touches[0].clientX - prevMouseX) * 0.015;
                prevMouseX = e.touches[0].clientX;
            }, { passive: true });

            // 60 FPS Render Loop with Automatic Continuous Rotation
            const clock = new THREE.Clock();
            function animate() {
                requestAnimationFrame(animate);
                const elapsedTime = clock.getElapsedTime();

                // Gentle floating bob
                mascotGroup.position.y = Math.sin(elapsedTime * 2.2) * 0.035;
                ring.rotation.z += 0.01;

                // Animate bubbles
                bubbles.forEach(b => {
                    b.position.y += b.userData.speedY;
                    if (b.position.y > 1.2) b.position.y = -0.9;
                });

                // AUTOMATIC CONTINUOUS ROTATION!
                if (isSpinningFast) {
                    targetRotationY += 0.12; // Turbo wash spin
                } else if (!isDragging) {
                    targetRotationY += 0.012; // Steady, smooth auto-rotation
                }

                currentRotationY += (targetRotationY - currentRotationY) * 0.1;
                mascotGroup.rotation.y = currentRotationY;

                renderer.render(scene, camera);
            }
            animate();

            // Resize handling
            function onResize() {
                if (!container) return;
                const w = container.clientWidth || 340;
                const h = container.clientHeight || 240;
                camera.aspect = w / h;
                camera.updateProjectionMatrix();
                renderer.setSize(w, h);
            }

            window.addEventListener('resize', onResize);

            document.querySelectorAll('[data-toggle="sidebar"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    setTimeout(onResize, 100);
                    setTimeout(onResize, 250);
                    setTimeout(onResize, 350);
                });
            });

        } catch (err) {
            console.error('Three.js failed:', err);
            render2DFallback(container);
        }
    }
})();