<template>
  <div ref="container" class="w-full h-[420px] md:h-[500px]"></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import * as THREE from 'three';

const container = ref(null);
let renderer, scene, camera, clock;
let animId;
let heart, box, globe, particles;
let floatGroup;

onMounted(() => {
  const w = container.value.clientWidth;
  const h = container.value.clientHeight;

  // Setup
  scene = new THREE.Scene();
  camera = new THREE.PerspectiveCamera(50, w / h, 0.1, 100);
  camera.position.set(0, 0, 10);

  renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
  renderer.setSize(w, h);
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setClearColor(0x000000, 0);
  container.value.appendChild(renderer.domElement);

  clock = new THREE.Clock();

  // Lighting
  const ambientLight = new THREE.AmbientLight(0xffffff, 1.0);
  scene.add(ambientLight);

  const dirLight = new THREE.DirectionalLight(0x4ade80, 2.5);
  dirLight.position.set(5, 5, 5);
  scene.add(dirLight);

  const blueLight = new THREE.PointLight(0x3b82f6, 2, 20);
  blueLight.position.set(-5, 3, 3);
  scene.add(blueLight);

  floatGroup = new THREE.Group();
  scene.add(floatGroup);

  buildHeart();
  buildDonationBox();
  buildGlobe();
  buildParticles();
  buildOrbitRing();

  animate();

  window.addEventListener('resize', onResize);
});

onUnmounted(() => {
  cancelAnimationFrame(animId);
  window.removeEventListener('resize', onResize);
  renderer.dispose();
});

// ===== BUILD OBJECTS =====

const buildHeart = () => {
  // Heart shape using custom geometry
  const shape = new THREE.Shape();
  shape.moveTo(0, 0.5);
  shape.bezierCurveTo(0, 0.9, 0.6, 0.9, 0.6, 0.5);
  shape.bezierCurveTo(0.6, 0.1, 0, -0.3, 0, -0.7);
  shape.bezierCurveTo(0, -0.3, -0.6, 0.1, -0.6, 0.5);
  shape.bezierCurveTo(-0.6, 0.9, 0, 0.9, 0, 0.5);

  const extrudeSettings = { depth: 0.25, bevelEnabled: true, bevelSize: 0.05, bevelThickness: 0.05 };
  const geo = new THREE.ExtrudeGeometry(shape, extrudeSettings);
  const mat = new THREE.MeshPhysicalMaterial({
    color: 0x22c55e,
    roughness: 0.2,
    metalness: 0.1,
    emissive: 0x16a34a,
    emissiveIntensity: 0.3,
  });
  heart = new THREE.Mesh(geo, mat);
  heart.position.set(0, 1.5, 0);
  heart.scale.set(1.2, 1.2, 1.2);
  floatGroup.add(heart);
};

const buildDonationBox = () => {
  const group = new THREE.Group();

  // Main box body
  const bodyGeo = new THREE.BoxGeometry(2.2, 1.8, 1.8);
  const bodyMat = new THREE.MeshPhysicalMaterial({
    color: 0xd97706,
    roughness: 0.3,
    metalness: 0.0,
  });
  const body = new THREE.Mesh(bodyGeo, bodyMat);
  group.add(body);

  // Box lid
  const lidGeo = new THREE.BoxGeometry(2.3, 0.3, 1.9);
  const lidMat = new THREE.MeshPhysicalMaterial({ color: 0xb45309, roughness: 0.4 });
  const lid = new THREE.Mesh(lidGeo, lidMat);
  lid.position.y = 1.05;
  group.add(lid);

  // Heart on box
  const heartGeo = new THREE.TorusGeometry(0.25, 0.08, 12, 30);
  const heartMat = new THREE.MeshPhysicalMaterial({ color: 0x22c55e, emissive: 0x22c55e, emissiveIntensity: 0.5 });
  const heartDecal = new THREE.Mesh(heartGeo, heartMat);
  heartDecal.position.set(0, 0, 0.92);
  group.add(heartDecal);

  // Ribbon
  const ribbonGeo = new THREE.BoxGeometry(0.12, 2.15, 2.1);
  const ribbonMat = new THREE.MeshPhysicalMaterial({ color: 0x22c55e });
  const ribbon = new THREE.Mesh(ribbonGeo, ribbonMat);
  group.add(ribbon);

  const ribbonH = new THREE.Mesh(new THREE.BoxGeometry(2.35, 0.12, 2.1), ribbonMat);
  group.add(ribbonH);

  group.position.set(0, -1.2, 0);
  box = group;
  floatGroup.add(box);
};

const buildGlobe = () => {
  const geoGlobe = new THREE.SphereGeometry(0.6, 32, 32);
  const matGlobe = new THREE.MeshPhysicalMaterial({
    color: 0x3b82f6,
    roughness: 0.1,
    metalness: 0.2,
    transparent: true,
    opacity: 0.85,
    emissive: 0x1d4ed8,
    emissiveIntensity: 0.2,
  });
  globe = new THREE.Mesh(geoGlobe, matGlobe);
  globe.position.set(-2.8, 0.5, 0.5);
  floatGroup.add(globe);

  // Globe lines
  for(let i = 0; i < 3; i++) {
    const lineGeo = new THREE.TorusGeometry(0.6, 0.01, 8, 60);
    const lineMat = new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.4 });
    const line = new THREE.Mesh(lineGeo, lineMat);
    line.rotation.x = (i * Math.PI) / 3;
    globe.add(line);
  }
};

const buildParticles = () => {
  const count = 80;
  const geo = new THREE.BufferGeometry();
  const pos = new Float32Array(count * 3);
  for(let i = 0; i < count; i++) {
    pos[i * 3]     = (Math.random() - 0.5) * 12;
    pos[i * 3 + 1] = (Math.random() - 0.5) * 10;
    pos[i * 3 + 2] = (Math.random() - 0.5) * 4 - 2;
  }
  geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
  const mat = new THREE.PointsMaterial({
    size: 0.08,
    color: 0x22c55e,
    transparent: true,
    opacity: 0.6,
  });
  particles = new THREE.Points(geo, mat);
  scene.add(particles);
};

const buildOrbitRing = () => {
  const ringGeo = new THREE.TorusGeometry(3.5, 0.02, 8, 100);
  const ringMat = new THREE.MeshBasicMaterial({ color: 0x22c55e, transparent: true, opacity: 0.15 });
  const ring = new THREE.Mesh(ringGeo, ringMat);
  ring.rotation.x = Math.PI / 2.5;
  floatGroup.add(ring);

  // Small orbiting sphere
  const smallGeo = new THREE.SphereGeometry(0.15, 16, 16);
  const smallMat = new THREE.MeshPhysicalMaterial({ color: 0xfbbf24, emissive: 0xf59e0b, emissiveIntensity: 0.5 });
  const orb = new THREE.Mesh(smallGeo, smallMat);
  orb.name = 'orbiter';
  floatGroup.add(orb);
};

// ===== ANIMATE =====
const animate = () => {
  animId = requestAnimationFrame(animate);
  const t = clock.getElapsedTime();

  // Float group gentle rotation
  floatGroup.rotation.y = Math.sin(t * 0.3) * 0.25;
  floatGroup.position.y = Math.sin(t * 0.7) * 0.2;

  // Heart pulse
  if(heart) {
    const scale = 1.2 + Math.sin(t * 2) * 0.07;
    heart.scale.set(scale, scale, scale);
    heart.rotation.z = Math.sin(t * 0.5) * 0.1;
  }

  // Box gentle tilt
  if(box) {
    box.rotation.y = t * 0.4;
    box.position.y = -1.2 + Math.sin(t * 0.9 + 1) * 0.15;
  }

  // Globe spin
  if(globe) {
    globe.rotation.y = t * 0.5;
    globe.position.y = 0.5 + Math.sin(t * 1.1 + 2) * 0.2;
  }

  // Orbiter
  const orb = floatGroup.getObjectByName('orbiter');
  if(orb) {
    orb.position.x = Math.cos(t * 0.8) * 3.5;
    orb.position.z = Math.sin(t * 0.8) * 3.5 * Math.cos(Math.PI / 2.5);
    orb.position.y = Math.sin(t * 0.8) * 3.5 * Math.sin(Math.PI / 2.5);
  }

  // Particles drift
  if(particles) {
    particles.rotation.y = t * 0.05;
    const pos = particles.geometry.attributes.position;
    for(let i = 0; i < pos.count; i++) {
      let y = pos.getY(i);
      y += 0.005;
      if(y > 5) y = -5;
      pos.setY(i, y);
    }
    pos.needsUpdate = true;
  }

  renderer.render(scene, camera);
};

const onResize = () => {
  if(!container.value) return;
  const w = container.value.clientWidth;
  const h = container.value.clientHeight;
  camera.aspect = w / h;
  camera.updateProjectionMatrix();
  renderer.setSize(w, h);
};
</script>
