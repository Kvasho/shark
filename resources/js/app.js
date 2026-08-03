import $ from 'jquery';
window.$ = window.jQuery = $;

import 'bootstrap/dist/css/bootstrap.min.css';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import '@fortawesome/fontawesome-free/css/all.min.css';

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

import Lenis from 'lenis';

const lenis = new Lenis({
    duration: 1.2,
    smoothWheel: true,
});

function smoothScroll(time) {
    lenis.raf(time);
    requestAnimationFrame(smoothScroll);
}

requestAnimationFrame(smoothScroll);
